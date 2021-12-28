<?php
	class ConfigurationController extends FrontendController {
		const CLASSNAME = 'InvoiceDetail';
		const TABLENAME = 'tbl_invoice_detail';

		public function __construct() {
		    $this->views = 'modules/configuration/frontend/views/configuration/';
		}

		public function generatesecretkey() {
			echo md5('Marketplace18102018');
		}

		public function generateadminuser() {
			echo SecurityHelper::encrypt('MarketplaceTesting123!');
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
			$model->classname = self::CLASSNAME;
			$model->table_name = self::TABLENAME;
			
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
			$model->classname = self::CLASSNAME;
			$model->table_name = self::TABLENAME;
			
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