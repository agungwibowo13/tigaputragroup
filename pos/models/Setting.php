<?php
	class Setting extends SnlActiveRecord {
		public $setting_id, $name, $identifier, $value, $remarks, $input_type, $created_on, $created_by, $updated_on, $updated_by;

		public function __construct() {
		    $this->classname = 'Setting';
			$this->table_name = 'tbl_setting';
			$this->primary_key = 'setting_id';
		}

		public function rules() {
			return array(
				'required'	=> array('type'),
			);
		}

		public static function model() {
			$model = new Setting();
			return $model;
		}

		public function getLabel($field, $with_rule = FALSE) {
			$labels = array(
				'setting_id' => 'Setting',
				'name' => 'Nama',
				'identifier' => 'Identifier',
				'value' => 'Value',
				'remarks' => 'Keterangan',
				'input_type' => 'Input Type',
				'created_on' => 'Created On',
				'created_by' => 'Created By',
				'updated_on' => 'Updated On',
				'updated_by' => 'Updated By',
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
				$this->created_on = Snl::app()->dateNow();
				$this->created_by = Snl::app()->user()->user_id;
				$this->updated_on = Snl::app()->dateNow();
				$this->updated_by = Snl::app()->user()->user_id;
			} else {
				$this->updated_on = Snl::app()->dateNow();
				$this->updated_by = Snl::app()->user()->user_id;
			}
		}

		public function getImage() {
			$image_url 	= Snl::app()->config()->no_image;
			$image_file = Snl::app()->rootDirectory().'uploads/setting/images/'.$this->value;

			if(file_exists($image_file) && $this->value != '') {
				$image_url = Snl::app()->baseUrl().'uploads/setting/images/'.$this->value;
			}

			return $image_url;
		}

		public static function getSetting($identifier) {
			$setting = Setting::model()->findByAttribute(array(
				'condition' => 'identifier = :identifier',
				'params'	=> array(':identifier' => $identifier)
			));

			if($setting != NULL) {
				if($setting->input_type == 'file') {
					return $setting->getImage();
				} else {
					return $setting->value;
				}
			}

			return 'Not set';
		}
	}