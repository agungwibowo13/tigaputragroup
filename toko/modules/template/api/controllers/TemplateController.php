<?php
	class TemplateController extends ApiController {
		public function login() {
			if($this->request_type == 'POST') {
				$model = new Outlet;
				$model->username = isset($this->params['username']) ? $this->params['username'] : '';
				$model->password = isset($this->params['password']) ? $this->params['password'] : '';
				$valid_user = $model->validateApiLogin();

				if($valid_user) {
					$model = Outlet::model()->findByPk($model->outlet_id);

					$last_login = OutletApiLoginHistory::model()->findByAttribute(array(
						'condition' => 'outlet_id = :outlet_id AND clock_out = "0000-00-00 00:00:00" ORDER BY clock_in DESC',
						'params'	=> array(':outlet_id' => $model->outlet_id)
					));

					if($last_login != NULL) {
						$last_login->clock_out =  Snl::app()->dateNow();
						$last_login->save();
					}

					$log = new OutletApiLoginHistory;
					$log->outlet_id = $model->outlet_id;
					$log->ip_address  = Snl::get_client_ip();
					$log->clock_in	  = Snl::app()->dateNow();
					$log->save();

					$result = array(
						'status'        => 200,
						'user_token'    => SecurityHelper::encrypt($log->api_login_history_id),
						'data'			=> array(
							'outlet_id'	=> $model->outlet_id,
							'name'		=> $model->name,
							'address'	=> $model->address,
							'phone'		=> $model->phone,
							'username'	=> $model->username,
						),
					);

					$this->renderJSON($result);
				} else {
					$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('password' => 'Username atau Password tidak cocok.'))));
				}
			} else {
				$this->renderErrorMessage(405, 'MethodNotAllowed');
			}
		}

		public function logout() {
			if($this->request_type == 'POST') {
				$user_token = $this->user_token;
				if(empty($user_token) || $user_token == '') {
					$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('user_token' => 'User Token not found.'))));
				} else {
					$login_id = (int) SecurityHelper::decrypt($user_token);
					$model = OutletApiLoginHistory::model()->findByPk($login_id);
					if($model != NULL) {
						$model->clock_out = Snl::app()->dateNow();
						$model->save();

						$result = array(
							'status' => 200,
						);
						$this->renderJSON($result);
					} else {
						// $this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('user_token' => 'User Token not found.'))));
						$this->renderInvalidUserToken();
					}
				}
			} else {
				$this->renderErrorMessage(405, 'MethodNotAllowed');
			}
		}

		public function listmachine() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					
					$result = array(
						'status' => 200,
						'data'	 => Outlet::getOutletAvailableMachine($this->outlet_id)
					);

					$this->renderJSON($result);
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

		public function listproduct() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					$data = [];
					$products = Product::model()->findAll(array('condition' => 'is_deleted = 0'));
					if($products) {
						foreach ($products as $product) {
							$data[] = array(
								'product_id' => $product->product_id,
								'name' => $product->name,
								'uom' => $product->uom,
								'package' => $product->package,
								'netto' => $product->netto,
							);
						}
					}

					$result = array(
						'status' => 200,
						'data'	 => $data
					);

					$this->renderJSON($result);
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

		public function packageitems() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					$package = Package::model()->findByAttribute(array(
						'condition' => 'is_deleted = 0 ORDER BY created_on DESC'
					));

					if($package != NULL) {
						$result = array(
							'status' => 200,
							'data'	 => array(
								'items' => $package->getPackageItems(),
								'price'	=> $package->price,
							)
						);

						$this->renderJSON($result);
					} else {
						$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('package' => 'No package found.'))));
					}
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

		public function submitcustomerorder() {
			if($this->valid_user_token) {
				if($this->request_type == 'POST') {
					$data = array(
						'outlet_machine_id'	=> isset($this->params['outlet_machine_id']) ? $this->params['outlet_machine_id'] : '',
						'name' 			=> isset($this->params['name']) ? $this->params['name'] : '',
						'phone' 		=> isset($this->params['phone']) ? $this->params['phone'] : '',
						'address' 		=> isset($this->params['address']) ? $this->params['address'] : '',
						'car_brand' 	=> isset($this->params['car_brand']) ? $this->params['car_brand'] : '',
						'car_type' 		=> isset($this->params['car_type']) ? $this->params['car_type'] : '',
						'car_number' 	=> isset($this->params['car_number']) ? $this->params['car_number'] : '',
						'start_machine_counter' => isset($this->params['start_machine_counter']) ? $this->params['start_machine_counter'] : '',
						'order_status' 	=> isset($this->params['order_status']) ? $this->params['order_status'] : 1,
						'remarks' 		=> isset($this->params['remarks']) ? $this->params['remarks'] : '',
						'order_date' 	=> isset($this->params['order_date']) ? $this->params['order_date'] : '',
					);

					$model = new CustomerOrder;
					$model->setAttributes($data);
					if($model->validate()) {
						if($model->save()) {
							$result = array(
								'status'    => 200,
							);

							$this->renderJSON($result);
						} else {
							$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage($model->errors)));	
						}
					} else {
						$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage($model->errors)));
					}
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

		public function listcustomerorder() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					$data 		 = array();
					$page 		 = isset($this->params['page']) ? $this->params['page'] : 1;
					$display_per_page = isset($this->params['display_per_page']) ? $this->params['display_per_page'] : Snl::app()->config(TRUE)->display_per_page;

					$offset 	 = $display_per_page * ($page - 1);
					$pagination  = ' LIMIT '.$display_per_page.' OFFSET '.$offset;

					$total_orders = new CustomerOrder();
					$total_orders->join = ['tbl_outlet_machine','tbl_machine'];
					$total_orders = $total_orders->count(array(
						'condition'	=> 'tbl_customer_order.outlet_machine_id = tbl_outlet_machine.outlet_machine_id AND tbl_outlet_machine.machine_id = tbl_machine.machine_id AND tbl_outlet_machine.outlet_id = :outlet_id',
						'params'	=> array(
							':outlet_id' => $this->outlet_id
						)
					));


					$total_pages = ceil($total_orders / $display_per_page);

					$orders = new CustomerOrder();
					$orders->join = ['tbl_outlet_machine','tbl_machine'];
					$orders = $orders->findAll(array(
						'condition'	=> 'tbl_customer_order.outlet_machine_id = tbl_outlet_machine.outlet_machine_id AND tbl_outlet_machine.machine_id = tbl_machine.machine_id AND tbl_outlet_machine.outlet_id = :outlet_id ORDER BY tbl_customer_order.order_date DESC'.$pagination,
						'params'	=> array(
							':outlet_id' => $this->outlet_id
						)
					));

					if($orders != NULL) {
						foreach ($orders as $order) {
							$data[] = array(
								'customer_order_id' => $order->customer_order_id,
								'name' => $order->name,
								'address' => $order->address,
								'phone' => $order->phone,
								'car_brand'	=> $order->car_brand,
								'car_type'	=> $order->car_type,
								'car_number'	=> $order->car_number,
								'order_status' => $order->order_status,
								'order_date' => Snl::app()->dateTimeFormat($order->order_date),
								'machine_id' => $order->machine_id,	
								'machine_no' => $order->machine_no,
								'start_machine_counter'	=> $order->start_machine_counter,
								'remarks'	 => $order->remarks,
							);
						}
					}

					$result = array(
						'status' 		=> 200,
						'total_orders' 	=> $total_orders,
						'total_pages'	=> $total_pages,
						'display_per_page' => $display_per_page,
						'active_page' 	=> $page,
						'data'		  	=> $data,
					);

					$this->renderJSON($result);

				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

		public function submitoutletorder() {
			if($this->valid_user_token) {
				if($this->request_type == 'POST') {
					$package_qty = isset($this->params['qty']) ? $this->params['qty'] : '';
					$order_date = isset($this->params['order_date']) ? $this->params['order_date'] : '';

					if($package_qty == '' || $package_qty < 1) {
						$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('items' => 'Please at least put 1 item.'))));
					} else {
						$package_model = Package::model()->findByAttribute(array(
							'condition' => 'is_deleted = 0 ORDER BY created_on DESC',
						));

						if($package_model != NULL) {
							$outlet_order = new OutletOrder;
							$outlet_order->scenario = 'api';
							$outlet_order->outlet_id = $this->outlet_id;
							$outlet_order->generateOrderID();
							$outlet_order->package_id = $package_model->package_id;
							$outlet_order->qty = $package_qty;
							$outlet_order->order_date = $order_date;

							if($outlet_order->save()) {
								$result = array(
									'status' 	=> 200,
									'data'		=> array(
										'order_id' 	=> $outlet_order->order_printed_id,
										'status'	=> $outlet_order->status,
									)
								);
									
								$this->renderJSON($result);
							} else {
								$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage($outlet_order->errors)));	
							}
						} else {
							$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('package' => 'No package found.'))));
						}

					}
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

		public function listoutletorder() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					$data 		 = array();
					$page 		 = isset($this->params['page']) ? $this->params['page'] : 1;
					$display_per_page = isset($this->params['display_per_page']) ? $this->params['display_per_page'] : Snl::app()->config(TRUE)->display_per_page;

					$offset 	 = $display_per_page * ($page - 1);
					$pagination  = ' LIMIT '.$display_per_page.' OFFSET '.$offset;

					$total_orders = OutletOrder::model()->count(array(
						'condition' => 'is_deleted = 0 AND outlet_id = :outlet_id ORDER BY order_date DESC',
						'params'	=> array(':outlet_id' => $this->outlet_id)
					));
					$total_pages = ceil($total_orders / $display_per_page);

					$orders = OutletOrder::model()->findAll(array(
						'condition' => 'is_deleted = 0 AND outlet_id = :outlet_id ORDER BY order_date DESC'.$pagination,
						'params'	=> array(':outlet_id' => $this->outlet_id)
					));

					if($orders != NULL) {
						foreach ($orders as $order) {
							/*
							$product = new Product;
							$product->join = ['tbl_outlet_order_detail'];
							$product = $product->findAll(array(
								'condition' => 'tbl_product.product_id = tbl_outlet_order_detail.product_id AND tbl_outlet_order_detail.outlet_order_id = :outlet_order_id',
								'params'	=> [':outlet_order_id' => $order->outlet_order_id]
							));

							$products = NULL;
							if($product) {
								foreach ($product as $key => $value) {
									$products .= $value->name.' ('.$value->qty.' '.$value->package.'), ';
								}

								$products = rtrim($products, ', ');
							}
							*/
							
							$package = Package::model()->findByPk($order->package_id);
							$package_items = '';
							if($package != NULL) {
								$package_items = $package->getPackageItems();
							}

							$data[] = array(
								'outlet_order_id' 	=> $order->outlet_order_id,
								'order_printed_id' 	=> $order->order_printed_id,
								'order_date' 		=> Snl::app()->dateTimeFormat($order->order_date),
								'status' 	=> $order->status,
								'qty'		=> $order->qty,
								'package_items' 	=> $package_items,
							);
						}
					}

					$result = array(
						'status' 		=> 200,
						'total_orders' 	=> $total_orders,
						'total_pages'	=> $total_pages,
						'display_per_page' => $display_per_page,
						'active_page' 	=> $page,
						'data'		  	=> $data,
					);

					$this->renderJSON($result);

				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

	} // end of class