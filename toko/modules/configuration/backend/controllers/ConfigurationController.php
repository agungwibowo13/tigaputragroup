<?php
	class ConfigurationController extends BackendController {
		public function __construct() {
		    $this->views = 'modules/configuration/backend/views/configuration/';
		}

		public function testencodeimage() {
			$product = Product::model()->findByPk(12);
			$product_image = ProductImage::model()->findByAttribute(array(
				'condition' => 'product_id = :product_id',
				'params'	=> [':product_id' => 12]
			));

			$image_file = Snl::app()->rootDirectory().'uploads/product/images/'.$product->image_url;

			if($product_image != null) {
				$image_file = Snl::app()->rootDirectory().'uploads/product/images/'.$product_image->image_url;
			}

			if(file_exists($image_file) && $product_image != null) {
				$type = pathinfo($image_file, PATHINFO_EXTENSION);
				$data = file_get_contents($image_file);
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
				$filename = 'donni2.jpg';
				$location = Snl::app()->rootDirectory().'uploads/product/encoded_image/'.$filename;
				// echo Snl::base64_to_jpeg($base64, $location);
				echo $base64;
			}
		}

		public function shoppingcartsimulation() {
			// init shopping cart
			Snl::cart()->initShoppingCart();

			// add to cart
			if(Snl::cart()->addToCart(2, 1)) {
				echo 'added to cart';
			} else {
				echo 'failed';
			}
			
			// remove item from cart
			if(Snl::cart()->removeItem(2)) {
				echo 'item removed';
			} else {
				echo 'failed';
			}

			echo '<hr />';

			// get cart information
			$cart = json_decode(Snl::session()->getSession('mycart'));
			if($cart != FALSE) {
				// echo $cart->cart_id;
				$items = json_decode($cart->items);
				if(count($items) > 0) {
					foreach ($items as $item) {
						echo $item->product_id.' - '.$item->name.' - '.$item->sku.' - '.$item->brand.' - '.$item->qty.' - '.$item->original_subtotal.' - '.$item->promo_subtotal;
						echo '<br />';
					}
				}
			}
		}

		public function generateModelProperty() {
			$model = new SnlActiveRecord;
			$model->classname = 'Product';
			$model->table_name = 'tbl_product';
			
			$model->openConnection();
			$query = $model->conn->prepare("DESCRIBE {$model->table_name}");
			$query->execute();
			$model->closeConnection();

			$property = '';
			foreach ($query->fetchAll(PDO::FETCH_COLUMN) as $field) {
				$property .= '$'.$field.', ';
			}

			$property = 'public '.rtrim($property, ', ').';';
			return $property;
		}

		public function generateModelLabel() {
			$model = new SnlActiveRecord;
			$model->classname = 'Product';
			$model->table_name = 'tbl_product';
			
			$model->openConnection();
			$query = $model->conn->prepare("DESCRIBE {$model->table_name}");
			$query->execute();
			$model->closeConnection();

			$label = '';
			foreach ($query->fetchAll(PDO::FETCH_COLUMN) as $field) {
				$label .= "'{$field}' => '".ucwords(str_replace('_', ' ', $field))."',<br />";
			}

			return $label;
		}
	}