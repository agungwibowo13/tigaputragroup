<?php
	class Category extends SnlActiveRecord {
		public $category_id, $name, $url_key, $image_url, $status, $created_on, $created_by, $updated_on, $updated_by, $is_deleted;

		public function __construct() {
		    $this->classname = 'Category';
			$this->table_name = 'tbl_category';
			$this->primary_key = 'category_id';
		}

		public function rules() {
			return array(
				'required'	=> array('name'),
				'unique'	=> array('name'),
			);
		}

		public static function model() {
			$model = new Category();
			return $model;
		}

		public function getLabel($field, $with_rule = FALSE) {
			$labels = array(
				'category_id' => 'Category Id',
				'name' => 'Nama Kategori',
				'url_key' => 'URL Key',
				'image_url' => 'Upload Gambar',
				'status' => 'Status',
				'created_on' => 'Created On',
				'created_by' => 'Created By',
				'updated_on' => 'Updated On',
				'updated_by' => 'Updated By',
				'is_deleted' => 'Is Deleted',
				'current_image' => 'Gambar'
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
				$this->created_by = Snl::app()->vendor()->vendor_id;
				$this->updated_on = Snl::app()->dateNow();
				$this->updated_by = Snl::app()->vendor()->vendor_id;
				
				$this->url_key = Snl::generateSlug($this->name);
			} else {
				$this->updated_on = Snl::app()->dateNow();
				$this->updated_by = Snl::app()->vendor()->vendor_id;
			}

			return TRUE;
		}

		public function getImage() {
			$image_url 	= Snl::app()->config()->no_banner;
			$image_file = Snl::app()->rootDirectory().'uploads/category/images/'.$this->image_url;

			if(file_exists($image_file) && $this->image_url != '') {
				$image_url = Snl::app()->baseUrl().'uploads/category/images/'.$this->image_url;
			}

			return $image_url;
		}

		public static function getCategoriesForDropdown() {
			$result = array();
			$result[0] = 'Please select';
			$categories = Category::model()->findAll(array(
				'condition' => 'is_deleted = 0 AND status = "Enable" ORDER BY name ASC'
			));

			if($categories != NULL) {
				foreach ($categories as $category) {
					$result[$category->category_id] = ucwords(strtolower($category->name));
				}
			}

			return $result;
		}
	}