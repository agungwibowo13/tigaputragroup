<?php
	class ProductMaster extends SnlActiveRecord {
		public $product_master_id, $name, $hpp, $price, $rounded_price, $remarks, $rating, $created_on, $created_by, $updated_on, $updated_by, $is_deleted;
		
		public function __construct() {
		    $this->classname = 'ProductMaster';
			$this->table_name = 'tbl_product_master';
			$this->primary_key = 'product_master_id';
		}

		public function rules() {
			return array(
				'required'	=> array('name','hpp','price'),
			);
		}

		public static function model() {
			$model = new ProductMaster();
			return $model;
		}

		public function getLabel($field, $with_rule = FALSE) {
			$labels = array(
				'product_master_id' => 'Product Master ID',
				'name' => 'Nama Barang',
				'hpp' => 'HPP',
				'price' => 'Harga Satuan',
				'rounded_price' => 'Bulatkan Harga',
				'remarks' => 'Keterangan',
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
				$this->created_on = Snl::app()->dateNow();
				$this->created_by = Snl::app()->user()->user_id;
				$this->updated_on = Snl::app()->dateNow();
				$this->updated_by = Snl::app()->user()->user_id;
			} else {
				$this->updated_on = Snl::app()->dateNow();
				$this->updated_by = Snl::app()->user()->user_id;
			}
			return TRUE;
		}

		public function calculateRoundedPrice($value) {
			$price = $value/1000;
		    $n = floor($price);
		    $m = $price - $n;

		    if($m > 0) {
			    if($m <= 0.5) {
			        $m = 0.5;
			    } else {
			        $m = 1;
			    }
		    }

		    $price = ($n + $m) * 1000;
		    return $price;
		}
	}