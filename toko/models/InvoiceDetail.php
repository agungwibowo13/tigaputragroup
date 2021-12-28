<?php
	class InvoiceDetail extends SnlActiveRecord {
		public $invoice_detail_id, $invoice_id, $product_master_id, $original_price, $price, $qty, $profit;
		
		public function __construct() {
		    $this->classname = 'InvoiceDetail';
			$this->table_name = 'tbl_invoice_detail';
			$this->primary_key = 'invoice_detail_id';
		}

		public function rules() {
			return array();
		}

		public static function model() {
			$model = new InvoiceDetail();
			return $model;
		}

		public function getLabel($field, $with_rule = FALSE) {
			$labels = array(
				'invoice_detail_id' => 'Invoice Detail Id',
				'invoice_id' => 'Invoice Id',
				'product_master_id' => 'Product Master Id',
				'original_price' => 'Original Price',
				'price' => 'Price',
				'qty' => 'Qty',
				'profit' => 'Profit',
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
	}