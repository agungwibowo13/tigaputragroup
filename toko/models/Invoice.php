<?php
	class Invoice extends SnlActiveRecord {
		public $invoice_id, $invoice_number, $invoice_date, $total, $profit, $created_on, $created_by, $updated_on, $updated_by, $is_deleted;
		
		public function __construct() {
		    $this->classname = 'Invoice';
			$this->table_name = 'tbl_invoice';
			$this->primary_key = 'invoice_id';
		}

		public function rules() {
			return array();
		}

		public static function model() {
			$model = new Invoice();
			return $model;
		}

		public function getLabel($field, $with_rule = FALSE) {
			$labels = array(
				'invoice_id' => 'Invoice Id',
				'invoice_number' => 'Invoice Number',
				'invoice_date' => 'Invoice Date',
				'total' => 'Total',
				'profit' => 'Profit',
				'created_on' => 'Created On',
				'created_by' => 'Created By',
				'updated_on' => 'Updated On',
				'updated_by' => 'Updated By',
				'is_deleted' => 'Is Deleted',
			);

			$label = isset($labels[$field]) ? $labels[$field] : ucwords(str_replace('_', ' ', $field));

			if($with_rule) {
				if(isset($this->rules()['required'])) {
					foreach($this->rules()['required'] as $value) {
						if($value == $field) {
							return $label.' <span class="required">*</span>';
						}
					}
				}
			}

			return $label;
		}

		public function getData() {
			$data = array();
			$refclass = new ReflectionClass($this);
			foreach ($refclass->getProperties() as $property) {
			    $name = $property->name;
			    if ($property->class == $refclass->name) {
			    	$data[$property->name] = $this->$name;
			    }
			}

			return $data;
		}

		public function setAttributes($post = array()) {
			$attributes = $this->getData();
			foreach ($attributes as $key => $value) {
				if(isset($post[$key])) {
					$this->$key = $post[$key];
				}
			}
		}

		public function beforeSave() {
			if($this->isNewRecord) {
				$this->is_deleted = 0;
			}
			
			return TRUE;
		}
	}