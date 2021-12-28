<?php
	class SnlActiveRecord {
		public $conn;
		public $classname;
		public $table_name;
		public $primary_key;
		public $scenario = '';
		public $isNewRecord = TRUE;
		public $errors = array();
		public $allowedFileType = array('jpg', 'png', 'jpeg');
		public $join = array();
		
		public function openConnection() {
			$this->conn = new PDO("mysql:host=".Config::SERVER.";dbname=".Config::DB_NAME, Config::DB_USERNAME, Config::DB_PASSWORD);
			// set the PDO error mode to exception
    		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function closeConnection() {
			return TRUE;
		}

		public function errorMessage() {
			return array(
				'required' 	=> '{attribute} wajib diisi.',
				'unique'	=> '{attribute} sudah terdaftar.',
				'integer'	=> '{attribute} harus angka.',
				'repeat'	=> '{attribute} tidak cocok.',
				'email'		=> 'Format {attribute} salah.',
			);
		}

		public function findByPk($id) {
			$is_deleted = 0;
			$isDeletedCondition = '';
			if(in_array('is_deleted', $this->getTableFields())) {
			    $isDeletedCondition = ' AND is_deleted = :is_deleted';
			}

			$this->openConnection();
			$query = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = :id".$isDeletedCondition);
			$query->bindParam(':id', $id);

			if($isDeletedCondition != '') {
				$query->bindParam(':is_deleted', $is_deleted);
			}

			$query->execute();
			$this->closeConnection();
			if($query->rowCount() > 0) {
				$models = $this->convertToObject($query);
				return isset($models[0]) ? $models[0] : NULL;
			}

			return NULL;
		}

		public function findByAttribute($params = array()) {
			$condition = $this->extractParams($params);

			$this->openConnection();
			$query = $this->conn->prepare("SELECT * FROM {$this->table_name}".$condition['stmt']);
			foreach($condition['params'] as $key => &$value) {
				$query->bindParam($key, $value);
			}

			$query->execute();
			$this->closeConnection();

			if($query->rowCount() > 0) {
				$models = $this->convertToObject($query);
				return isset($models[0]) ? $models[0] : NULL;
			}

			return NULL;
		}

		public function findAll($params = array()) {
			$relation 	= $this->extractRelation();
			$select 	= $relation['select'];
			$from 		= $relation['from'];
			$condition 	= $this->extractParams($params);
			
			$this->openConnection();
			$query = $this->conn->prepare("SELECT {$select} FROM {$from}".$condition['stmt']);
			foreach($condition['params'] as $key => &$value) {
				$query->bindParam($key, $value);
			}

			$query->execute();
			$this->closeConnection();

			if($query->rowCount() > 0) {
				$models = $this->convertToObject($query);
				return count($models) > 0 ? $models : NULL;
			}

			return NULL;
		}

		public function count($params = array()) {
			$relation 	= $this->extractRelation();
			$select 	= isset($params['select']) ? $params['select'] : $relation['select'];
			$from 		= $relation['from'];
			$condition 	= $this->extractParams($params);
			
			$this->openConnection();
			$query = $this->conn->prepare("SELECT {$select} FROM {$from}".$condition['stmt']);
			foreach($condition['params'] as $key => &$value) {
				$query->bindParam($key, $value);
			}

			$query->execute();
			$this->closeConnection();

			return $query->rowCount();
		}

		public function updateByAttribute($params = array()) {
			$condition = $this->extractParams($params);

			$this->openConnection();
			$query = $this->conn->prepare("UPDATE {$this->table_name}".$condition['stmt']);

			foreach($condition['params'] as $key => &$value) {
				$query->bindParam($key, $value);
			}

			$query->execute();
			$this->closeConnection();

			return $query->rowCount();
		}

		public function deleteByAttribute($params = array()) {
			$condition = $this->extractParams($params);

			$this->openConnection();
			$query = $this->conn->prepare("DELETE FROM {$this->table_name}".$condition['stmt']);

			foreach($condition['params'] as $key => &$value) {
				$query->bindParam($key, $value);
			}

			$query->execute();
			$this->closeConnection();

			return $query->rowCount();
		}

		public function validate() {
			$this->errors = array();
			$data = $this->getData();
			if(count($data) > 0) {
				$rules = $this->rules();
				foreach ($data as $key => $value) {
					if(isset($rules['required']) && count($rules['required']) > 0) {
						foreach($rules['required'] as $attribute) {
							if($key == $attribute) {
								$this->requiredValidation($key, $value);
							}
						}
					}

					if(isset($rules['unique']) && count($rules['unique']) > 0) {
						foreach($rules['unique'] as $attribute) {
							if($key == $attribute) {
								$this->uniqueValidation($key, $value);
							}
						}
					}

					if(isset($rules['integer']) && count($rules['integer']) > 0) {
						foreach($rules['integer'] as $attribute) {
							if($key == $attribute) {
								$this->integerValidation($key, $value);
							}
						}
					}

					if(isset($rules['repeat']) && count($rules['repeat']) > 0) {
						foreach($rules['repeat'] as $attribute) {
							if($key == $attribute) {
								$this->repeatValidation($key, $value);
							}
						}
					}

					if(isset($rules['email']) && count($rules['email']) > 0) {
						foreach($rules['email'] as $attribute) {
							if($key == $attribute) {
								$this->emailValidation($key, $value);
							}
						}
					}
				}
			}

			if(count($this->errors) > 0) {
				return FALSE;
			} else {
				return TRUE;
			}
		}

		public function requiredValidation($attribute, $value) {
			if(!isset($this->errors[$attribute])) {
				// if($value == '' || empty($value) || is_null($value)) {
				if($value == '' || is_null($value)) {
					$this->setError($attribute, 'required');
				}
			}
		}

		public function uniqueValidation($attribute, $value) {
			if(!isset($this->errors[$attribute])) {
				$model = $this->findByAttribute(array(
					"condition" => "{$attribute} = :value",
					"params"	=> array(":value" => $value)
				));

				if(count($model) > 0) {
					$primary_key = $this->primary_key;
					if($model->$primary_key != $this->$primary_key) {
						$this->setError($attribute, 'unique');
					}
				}
			}	
		}

		public function integerValidation($attribute, $value) {
			if(!isset($this->errors[$attribute])) {
				if(!preg_match('/^\d+$/',$value)) {
					$this->setError($attribute, 'integer');
				}
			}
		}

		public function repeatValidation($attribute, $value) {
			$compare_field = $attribute.'_repeat';
			$compare_value = $this->$compare_field;

			if(!isset($this->errors[$attribute])) {
				if($value != $compare_value) {
					$this->setError($attribute, 'repeat');
				}
			}
		}

		public function emailValidation($attribute, $value) {
			if(!isset($this->errors[$attribute])) {
				if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
					$this->setError($attribute, 'email');
				}
			}
		}

		public function setError($attribute, $type) {
			$label = $this->getLabel($attribute);
			$this->errors[$attribute] = str_replace('{attribute}', ucwords(str_replace('_', ' ', $label)), $this->errorMessage()[$type]);
		}

		public function getErrors() {
			$msg = '';
			if(count($this->errors) > 0) {
				foreach ($this->errors as $key => $value) {
					$msg .= $value . '<br />';
				}
			}

			return $msg;
		}

		public function save() {
			$this->beforeSave();
			
			if($this->isNewRecord) {
				return $this->insert();
			} else {
				return $this->update();
			}
		}

		public function delete() {
			$primary_key = $this->primary_key;
			$id = htmlspecialchars($this->$primary_key);
			
			$this->openConnection();
			$command = $this->conn->prepare("DELETE FROM `{$this->table_name}` WHERE `{$this->primary_key}` = :id");
			$command->bindParam(':id', $id);
			$result = $command->execute();
			$this->closeConnection();

			if($result) {
			    return TRUE;
			}

			return FALSE;
		}

		public function insert() {
			$query = $this->generateInsertQuery();
			if(empty($query)) {
				return FALSE;
			} else {
				$this->openConnection();
				$command = $this->conn->prepare($query['stmt']);
				foreach($query['params'] as $key => &$value) {
					$command->bindParam($key, $value);
				}

				if($command->execute()) {
					$this->closeConnection();
					$primary_key = $this->primary_key;
					$this->$primary_key = $this->conn->lastInsertId();
					$this->isNewRecord  = FALSE;
					return $query['params'];
					return TRUE;
				} 
				
				$this->closeConnection();
			}

			return FALSE;
		}

		public function update() {
			$query = $this->generateUpdateQuery();
			
			if(empty($query)) {
				return FALSE;
			} else {
				$this->openConnection();
				$command = $this->conn->prepare($query['stmt']);
				foreach($query['params'] as $key => &$value) {
					$command->bindParam($key, $value);
				}

				if($command->execute()) {
					$this->closeConnection();
				    return TRUE;
				} else {
					$this->closeConnection();
				    return FALSE;
				}
			}
		}

		public function generateInsertQuery() {
			$query = NULL;
			$data = $this->getData();
			$params = array();
			if(count($data) > 0) {
				$fields = '';
				$values = '';
				foreach ($data as $key => $value) {
					if(in_array($key, $this->getTableFields())) {
					    $fields .= "`".$key."`, ";
						$values .= ":{$key}, ";
						$params[":{$key}"] = $key == 'is_deleted' ? 0 : $value;
					}
				}

				$fields = ' ('.rtrim($fields, ', ').') ';
				$values = ' ('.rtrim($values, ', ').')';

				$query = "INSERT INTO `{$this->table_name}`{$fields}VALUES{$values}";
			}

			return array(
				'stmt' 	 => $query,
				'params' => $params
			);
		}

		public function generateUpdateQuery() {
			$data = $this->getData();
			$query = '';
			$params = array();
			$id = 0;
			if(count($data) > 0) {
				foreach ($data as $key => $value) {
					if(in_array($key, $this->getTableFields())) {
						if($key != $this->primary_key) {
							$query .= "`{$key}` = :{$key}, ";
							$params[":{$key}"] = $value;
						}
					}

					if($key == $this->primary_key) {
						$id = $value;
					}
				}
			}

			$query = rtrim($query, ', ');
			$query = "UPDATE `{$this->table_name}` SET ".$query." WHERE `{$this->primary_key}` = :id";
			$params[":id"] = $id;
			return array(
				'stmt' 	 => $query,
				'params' => $params,
			);
		}

		public function convertToObject($query) {
			$objects = array();
			while($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$classname = $this->classname;
				$model = new $classname;
				$model->isNewRecord = FALSE;
				$fields = array_keys($row);
				foreach ($fields as $field) {
					$key = $field;
					$model->$key = $row[$key];
				}

				$objects[] = $model;
			}

			return $objects;
		}

		public function extractParams($params) {
			$condition = '';
			$update    = '';
			$bindParams = array();

			if(isset($params['update']) && !empty($params['update'])) {
				$condition = ' SET '.$params['update'];
			}

			if(isset($params['condition']) && !empty($params['condition'])) {
				$condition .= ' WHERE '.$params['condition'];
			}

			if(isset($params['params']) && count($params['params']) > 0) {
				foreach ($params['params'] as $key => $value) {
					$bindParams[$key] = htmlspecialchars($value);
				}
			}

			return array(
				'stmt' 	 => $condition,
				'params' => $bindParams
			);
		}

		public function extractRelation() {
			$select = '';
			$from 	= '';

			if(count($this->join) > 0) {
				$select .= $this->table_name.'.*,';
				$from 	.= $this->table_name.',';
				foreach ($this->join as $table_relation) {
					$select .= $table_relation.'.*,';
					$from 	.= $table_relation.',';
				}

				$select = rtrim($select, ',');
				$from 	= rtrim($from, ',');
			} else {
				$select = '*';
				$from 	= $this->table_name;
			}

			return array(
				'select' => $select,
				'from'	 => $from
			);
		}

		public function getTableFields() {
			$this->openConnection();
			$query = $this->conn->prepare("DESCRIBE {$this->table_name}");
			$query->execute();
			$this->closeConnection();

			$fields = array();
			foreach ($query->fetchAll(PDO::FETCH_COLUMN) as $field) {
				array_push($fields, $field);
			}

			return $fields;
		}
	}