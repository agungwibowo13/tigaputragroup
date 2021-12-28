<?php
	class ShoppingCart {
		public $ip_address;
		public $session_id;
		public $cart;
		public $cart_id;
		public $isLogin = FALSE;
		public $error_msg;
		public $product_qty = 0;

		public function __construct() {
		    $this->ip_address = Snl::get_client_ip();
			$this->session_id = $this->generateSessionID();
			$this->isLogin 	  = Snl::app()->isCustomer();
			
			if(!Snl::session()->isSessionExist('mycart')) {
				if($this->isLogin) {
					$this->cart = CheckoutCart::model()->findByAttribute(array(
						'condition' => 'is_active = 1 AND ip_address = :ip_address AND session_id = :session_id AND is_customer = 1 AND customer_id = :customer_id ORDER BY cart_id DESC',
						'params'	=> array(
							':ip_address' => $this->ip_address,
							':session_id' => $this->session_id,
							':customer_id' => $this->getCustomerID()
						)
					));
				} else {
					$this->cart = CheckoutCart::model()->findByAttribute(array(
						'condition' => 'is_active = 1 AND is_customer = 0 AND ip_address = :ip_address AND session_id = :session_id ORDER BY cart_id DESC',
						'params'	=> array(
							':ip_address' => $this->ip_address,
							':session_id' => $this->session_id
						)
					));
				}

				$this->cart_id = $this->cart != NULL ? $this->cart->cart_id : 0;
			} else {
				$cart = json_decode(Snl::session()->getSession('mycart'));
				if($cart != FALSE) {
					$this->cart_id = $cart->cart_id;
					$this->cart    = CheckoutCart::model()->findByPk($this->cart_id);
				}
			}
		}

		protected function generateSessionID() {
			return SecurityHelper::encrypt($this->ip_address);
		}

		/**
		 * SHOULD BE NOT USED ANYMORE
		 */
		protected function getCart() {
			$cart = NULL;
			if($this->isLogin) {
				$cart = CheckoutCart::model()->findByAttribute(array(
					'condition' => 'is_active = 1 AND ip_address = :ip_address AND session_id = :session_id AND is_customer = 1 AND customer_id = :customer_id ORDER BY cart_id DESC',
					'params'	=> array(
						':ip_address' => $this->ip_address,
						':session_id' => $this->session_id,
						':customer_id' => $this->getCustomerID()
					)
				));
			} else {
				$cart = CheckoutCart::model()->findByAttribute(array(
					'condition' => 'is_active = 1 AND is_customer = 0 AND ip_address = :ip_address AND session_id = :session_id ORDER BY cart_id DESC',
					'params'	=> array(
						':ip_address' => $this->ip_address,
						':session_id' => $this->session_id
					)
				));
			}

			return $cart;
		}

		public function initShoppingCart() {
			if(!Snl::session()->isSessionExist('mycart')) {
				if($this->cart_id > 0) {
					if($this->cart->is_customer) {
						if($this->isLogin && $this->cart->customer_id == $this->getCustomerID()) {
							$this->generateSession();
						}
					} else {
						$this->cart->is_active = 0;
						$this->cart->save();
					}
				}
			}
		}

		public function flushShoppingCart() {
			$carts = CheckoutCart::model()->findAll(array(
				'condition' => 'is_active = 1 AND is_customer = 1 AND customer_id = :customer_id',
				'params'	=> array(':customer_id' => $this->getCustomerID())
			));

			if($carts != NULL) {
				foreach($carts as $cart) {
					$cart->is_active = 0;
					$cart->save();
				}
			}

			if($this->cart_id > 0) {
				$this->cart->is_active = 0;
				$this->cart->save();
			}

			Snl::session()->unsetSession('mycart');
		}

		public function getTotalPrice() {
			$total = 0;
			$cart = json_decode(Snl::session()->getSession('mycart'));
			if($cart != FALSE) {
				$items = json_decode($cart->items);
				if(count($items) > 0) {
					foreach ($items as $item) {
						$total += $item->promo_subtotal;
					}
				}
			}

			return $total;
		}

		public function getTotalItems() {
			$total = 0;
			$cart = json_decode(Snl::session()->getSession('mycart'));
			if($cart != FALSE) {
				$items = json_decode($cart->items);
				if(count($items) > 0) {
					foreach ($items as $item) {
						$total += $item->qty;
					}
				}
			}

			return $total;
		}

		public function getTotalWeight() {
			$total = 0;
			$cart = json_decode(Snl::session()->getSession('mycart'));
			if($cart != FALSE) {
				$items = json_decode($cart->items);
				if(count($items) > 0) {
					foreach ($items as $item) {
						$product = Product::model()->findByPk($item->product_id);
						$total += $item->qty * $product->weight;
					}
				}
			}

			return $total;
		}

		public function addToCart($product_id, $qty = 1, $action = 'plus') {
			$result = FALSE;
			if($this->cart_id == 0) {
				$result = $this->createNewCart($product_id, $qty, $action);
			} else {
				$result = $this->addItemToCart($product_id, $qty, $action);
			}

			if($result) {
				$result = new stdClass();
				$result->success = TRUE;
				$result->qty 	 = $this->product_qty;
			} else {
				$result = new stdClass();
				$result->success = FALSE;
				$result->message = $this->error_msg;
			}

			return $result;
		}

		public function getProductInfo($product_id) {
			$cart_item = CheckoutCartItem::model()->findByAttribute(array(
				'condition' => 'cart_id = :cart_id AND product_id = :product_id',
				'params'	=> array(
					':cart_id' 		=> $this->cart_id,
					':product_id'	=> $product_id
				)
			));

			$result = new stdClass();

			if($cart_item != NULL) {
				$result->is_exist = TRUE;
				$result->qty = $cart_item->qty;
			} else {
				$result->is_exist = FALSE;				
			}

			return $result;
		}

		public function removeItem($product_id) {
			$result = new stdClass();
			$result->success = FALSE;

			$cart_item = CheckoutCartItem::model()->findByAttribute(array(
				'condition' => 'cart_id = :cart_id AND product_id = :product_id',
				'params'	=> array(
					':cart_id' 		=> $this->cart_id,
					':product_id'	=> $product_id
				)
			));

			if($cart_item != NULL) {
				if($cart_item->delete()) {
					$this->generateSession();
					$result->success = TRUE;
				} else {
					$this->error_msg = 'Kesalahan server.';
				}
			} else {
				$this->error_msg = 'Produk sudah tidak ada di keranjang.';
			}

			$result->message = $this->error_msg;
			return $result;
		}

		protected function createNewCart($product_id, $qty, $action) {
			$this->cart = new CheckoutCart;
			$this->cart->ip_address = $this->ip_address;
			$this->cart->session_id = $this->session_id;
			$this->cart->is_customer = $this->isLogin;
			$this->cart->customer_id = $this->isLogin ? $this->getCustomerID() : 0;
			if($this->cart->save()) {
				$this->cart_id = $this->cart->cart_id;
				return $this->addItemToCart($product_id, $qty, $action);
			} else {
				$this->error_msg = 'Internal server error. Cannot create your shopping cart.';
				return FALSE;
			}
		}

		protected function addItemToCart($product_id, $qty, $action = 'plus') {
			$cart_item = CheckoutCartItem::model()->findByAttribute(array(
				'condition' => 'cart_id = :cart_id AND product_id = :product_id',
				'params'	=> array(
					':cart_id'	  => $this->cart_id,
					':product_id' => $product_id
				)
			));

			$product = Product::model()->findByPk($product_id);

			if($product == NULL) {
				$this->error_msg = 'Produk tidak ditemukan.';
				return FALSE;
			}

			if(!$product->isAvailable()) {
				$this->error_msg = "Stok untuk {$product->name} telah habis.";
				return FALSE;				
			}

			if($cart_item == NULL) {
				$cart_item = new CheckoutCartItem;
				$cart_item->cart_id = $this->cart_id;
			}

			if($action == 'minus') {
				$cart_item->qty -= $qty;
			} else {
				$cart_item->qty += $qty;
			}

			if($cart_item->qty > $product->inventory_qty) {
				$this->error_msg = "Stok tidak cukup.";
				return FALSE;				
			}

			$cart_item->product_id 	= $product_id;
			$cart_item->price 		= $product->price;
			$cart_item->discount_type 	= $product->isOnPromo() ? $product->discount_type : '';
			$cart_item->discount_amount = $product->isOnPromo() ? $product->discount_amount : 0;
			if($cart_item->save()) {
				if($cart_item->qty > 0) {
					$this->product_qty = $cart_item->qty;
				} else {
					$this->product_qty = 0;
					$this->removeItem($product_id);
				}
				
				$this->generateSession();
				return TRUE;
			} else {
				$this->error_msg = 'Internal server error. Cannot add your item to the cart.';
				return FALSE;
			}
		}

		protected function generateSession() {
			$items = array();
			$cart_items = $this->cart->getItems();
			if($cart_items != NULL) {
				foreach ($cart_items as $cart_item) {
					$product = Product::model()->findByPk($cart_item->product_id);

					$tmp = new stdClass();
					$tmp->product_id = $product != NULL ? $product->product_id : 0;
					$tmp->name 	= $product != NULL ? $product->name : '';
					$tmp->sku 	= $product != NULL ? $product->sku : '';
					$tmp->brand = $product != NULL && $product->getBrand() != NULL ? $product->getBrand()->name : '';
					$tmp->qty 	= $cart_item->qty;
					$tmp->price = $cart_item->price;
					$tmp->final_price 		= $cart_item->getFinalPrice();
					$tmp->original_subtotal =  $cart_item->getOriginalSubtotal();
					$tmp->promo_subtotal 	=  $cart_item->getPromoSubtotal();

					$items[] = $tmp;
				}
			}

			$data = new stdClass();
			$data->cart_id = $this->cart_id;
			$data->items   = json_encode($items);

			Snl::session()->unsetSession('mycart');
			Snl::session()->createSession('mycart', json_encode($data));
		}

		protected function getCustomerID() {
			return Snl::app()->customer()->customer_id;
		}
	}