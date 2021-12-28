<?php
	class ProductImage extends SnlActiveRecord {
		public $product_image_id, $product_id, $image_url;

		public function __construct() {
		    $this->classname = 'ProductImage';
			$this->table_name = 'tbl_product_image';
			$this->primary_key = 'product_image_id';
		}

		public function rules() {
			return array();
		}

		public static function model() {
			$model = new ProductImage();
			return $model;
		}

		public function getLabel($field, $with_rule = FALSE) {
			$labels = array(
				'product_image_id' => 'Product Image Id',
				'product_id' => 'Product Id',
				'image_url' => 'Image Url',
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
			return TRUE;
		}

		public function getImage() {
			$image_url 	= Snl::app()->config()->no_banner;
			$image_file = Snl::app()->rootDirectory().'uploads/product/images/'.$this->image_url;

			if(file_exists($image_file) && $this->image_url != '') {
				$image_url = Snl::app()->baseUrl().'uploads/product/images/'.$this->image_url;
			}

			return $image_url;
		}
	}