<?php
	class OutletController extends FrontendController {
		public function __construct() {
			$this->views = 'modules/outlet/frontend/views/outlet/';
		}

		public function login() {
			$model = new Outlet;
			$nextUrl = isset($_GET['next']) ? $_GET['next'] : '';

			if(isset($_POST['Outlet'])) {
				$model->username 	 = $_POST['Outlet']['username'];
				$model->password = $_POST['Outlet']['password'];
				$valid_user = $model->validateApiLogin(TRUE);

				if($valid_user) {
					if($nextUrl == '') {
						$this->redirect('outlet/customerorder');
					} else {
						$this->redirect($nextUrl);
					}
				} else { // invalid username or password
					Snl::app()->setFlashMessage('Username atau password salah.', 'danger');
				}
			}

			return $this->render('login', array(
				'model' => $model
			));
		}

		public function logout() {
			Snl::session()->unsetSession(SecurityHelper::encrypt('frontendlogin'));
			$this->redirect('outlet/login');
		}

		public function loadOutlet() {
			$model = Outlet::model()->findByPk(Snl::app()->outlet()->outlet_id);

			if($model == NULL) {
				$this->redirect('default/error');
			} else {
				return $model;
			}
		}

		public function customerorder() {
			$this->page_title = 'Customer Order';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'outlet/createcustomerorder" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-plus"></i></a>';

			return $this->render('customer_order', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function createcustomerorder() {
			$model = new CustomerOrder;
			
			if(isset($_POST['CustomerOrder'])) {
				$model->setAttributes($_POST['CustomerOrder']);
				if($model->save()) {
					Snl::app()->setFlashMessage('Order customer berhasil disimpan.', 'success');
					$this->redirect('outlet/customerorder');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Input Customer Order';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'outlet/customerorderlist" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';

			return $this->render('customer_order_form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
			));
		}

		public function outletorder() {
			$this->page_title = 'Outlet Order';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'outlet/createoutletorder" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-plus"></i></a>';

			return $this->render('outlet_order', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function createoutletorder() {
			$model = new OutletOrder;
			$packages = Package::model()->findAll(array('condition' => 'is_deleted = 0'));
			$is_success = FALSE;
			if(isset($_POST['OutletOrder'])) {
				$data = $_POST['OutletOrder'];

				foreach ($data['package'] as $index => $package_id) {
					if(isset($data['qty'][$package_id]) && $data['qty'][$package_id] != '' && $data['qty'][$package_id] > 0) {
						$model = new OutletOrder;
						$model->generateOrderID();
						$model->package_id = $package_id;
						$model->qty = $data['qty'][$package_id];
						if($model->save()) {
							$is_success = TRUE;
						}
					}
				}
				
				if($is_success) {
					Snl::app()->setFlashMessage('Pesanan anda telah berhasil dikirim ke pusat. Silahkan menunggu respon dari pusat', 'success');
					$this->redirect('outlet/outletorder');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Input Outlet Order';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'outlet/orderlist" class="btn btn-default btn-sm pull-right m-l-5"><i class="glyphicon glyphicon-remove"></i></a>';

			$this->toolbarElement .= '<button type="button" id="submit-outlet-order" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-floppy-disk"></i> Submit</button>';

			return $this->render('outlet_order_form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
				'packages'=> $packages,
			));
		}

		// All ajax function
		public function validate() {
			$post = $_POST['post'];
			$data = array();
			$result = array();
			$model = new CustomerOrder;
			
			if(count($post) > 0) {
				foreach ($post as $key => $value) {
					$name = str_replace(']', '', str_replace('[', '', str_replace($model->classname, '', $value['name'])));
					$data[$name] = $value['value'];
				}
			}

			$id = isset($data['customer_order_id']) ? $data['customer_order_id'] : 0;
			if($id > 0) {
				$model = CustomerOrder::model()->findByPk($id);
			}

			$model->setAttributes($data);
			if($model->validate()) {
				$result = array(
					'valid' => TRUE
				);
			} else {
				$result = array(
					'valid' => FALSE,
					'msg'	=> $model->errors
				);
			}

			echo json_encode($result);
		}

		public function search() {
			$gets = isset($_GET) ? $_GET : array();
			
			$data = array();
			$pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 1;
			$pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;
			$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'order_date';
			$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'desc';
			$offset = ($pageIndex - 1) * $pageSize;
			$search_query = $this->parseSearchQuery(new CustomerOrder, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			// $itemsCount = count(CustomerOrder::model()->findAll(array('condition' => '1 = 1'.$search_query)));

			$orders = new CustomerOrder();
			$orders->join = ['tbl_outlet_machine','tbl_machine'];
			$itemsCount = $orders->count(array(
				'condition'	=> 'tbl_customer_order.outlet_machine_id = tbl_outlet_machine.outlet_machine_id AND tbl_outlet_machine.machine_id = tbl_machine.machine_id AND tbl_outlet_machine.outlet_id = :outlet_id'.$search_query,
				'params'	=> array(
					':outlet_id' => Snl::app()->outlet()->outlet_id
				)
			));


			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			// $orders = CustomerOrder::model()->findAll(array('condition' => '1 = 1'.$search_query));
			$orders = new CustomerOrder();
			$orders->join = ['tbl_outlet_machine','tbl_machine'];
			$orders = $orders->findAll(array(
				'condition'	=> 'tbl_customer_order.outlet_machine_id = tbl_outlet_machine.outlet_machine_id AND tbl_outlet_machine.machine_id = tbl_machine.machine_id AND tbl_outlet_machine.outlet_id = :outlet_id'.$search_query,
				'params'	=> array(
					':outlet_id' => Snl::app()->outlet()->outlet_id
				)
			));

			if($orders !== NULL) {
				foreach ($orders as $order) {
					// $machine = new Machine;
					// $machine->join = ['tbl_outlet_machine'];
					// $machine = $machine->findAll(array(
					// 	'condition' => 'tbl_machine.machine_id = tbl_outlet_machine.machine_id AND tbl_outlet_machine.outlet_machine_id = :outlet_machine_id',
					// 	'params'	=> [':outlet_machine_id' => $order->outlet_machine_id]
					// ));

					$data[] = array(
						'customer_order_id' => $order->customer_order_id,
						'name' => $order->name,
						'address' => $order->address,
						'phone' => $order->phone,
						'car_info' => $order->car_brand.' - '.$order->car_type.' - ('.strtoupper($order->car_number).')',
						'order_status' => $order->order_status ? 'Sukses' : 'Gagal',
						'order_date' => Snl::app()->dateTimeFormat($order->order_date),
						'machine_no' => $order->machine_no,
						'start_machine_counter'	=> $order->start_machine_counter,
						'remarks'	 => $order->remarks,
					);
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}

		public function searchoutletorder() {
			$gets = isset($_GET) ? $_GET : array();
			
			$data = array();
			$pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 1;
			$pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;
			$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'printed_id_index';
			$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'desc';
			$offset = ($pageIndex - 1) * $pageSize;
			$search_query = $this->parseSearchQuery(new OutletOrder, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = OutletOrder::model()->count(array(
				'condition' => 'is_deleted = 0 AND outlet_id = :outlet_id'.$search_query,
				'params'	=> array(':outlet_id' => Snl::app()->outlet()->outlet_id)
			));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$orders = OutletOrder::model()->findAll(array(
				'condition' => 'is_deleted = 0 AND outlet_id = :outlet_id'.$search_query,
				'params'	=> array(':outlet_id' => Snl::app()->outlet()->outlet_id)
			));

			if($orders !== NULL) {
				foreach ($orders as $order) {
					$package = Package::model()->findByPk($order->package_id);
					$package_items = '';
					if($package != NULL) {
						$package_items = $package->getPackageItems();
					}

					// $product = new Product;
					// $product->join = ['tbl_outlet_order_detail'];
					// $product = $product->findAll(array(
					// 	'condition' => 'tbl_product.product_id = tbl_outlet_order_detail.product_id AND tbl_outlet_order_detail.outlet_order_id = :outlet_order_id',
					// 	'params'	=> [':outlet_order_id' => $order->outlet_order_id]
					// ));

					// $products = NULL;
					// if($product) {
					// 	foreach ($product as $key => $value) {
					// 		$products .= $value->name.' ('.$value->qty.' '.$value->packaging.'), ';
					// 	}

					// 	$products = rtrim($products, ', ');
					// }
					$order_label = ' label-danger';
					if(strtolower($order->status) == 'approved') {
						$order_label = ' label-info';
					} elseif(strtolower($order->status) == 'delivered') {
						$order_label = ' label-success';
					}

					$payment_status_label = ' label-danger';
					if(strtolower($order->payment_status) == 'invoiced') {
						$payment_status_label = ' label-warning';
					} elseif(strtolower($order->payment_status) == 'paid') {
						$payment_status_label = ' label-success';
					}

					$data[] = array(
						'outlet_order_id' => $order->outlet_order_id,
						'order_printed_id' => $order->order_printed_id,
						'order_date' => Snl::app()->dateTimeFormat($order->order_date),
						'status' 	=> $order->status,
						'payment_status' => $order->payment_status,
						'print_status' 	=> "<span class='label".$order_label."'>".$order->status."</span>",
						'print_payment_status' => "<span class='label".$payment_status_label."'>".$order->payment_status."</span>",

						'qty'		=> $order->qty,
						'items' 	=> $package_items,
						'package_price'	=> Snl::app()->formatPrice($order->package_price),
						'delivery_date'	=> $order->delivery_date != '' ? Snl::app()->dateFormat($order->delivery_date) : '',
					);
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}

		public function blankinvoice() {
			echo "<h3>Belum ada penagihan</h3>";
		}

		public function openinvoice() {
			$outlet_order_id = $_GET['outlet_order_id'];
			$order = OutletOrder::model()->findByPk($outlet_order_id);
			$outlet = Outlet::model()->findByPk($order->outlet_id);
			$package = Package::model()->findByPk($order->package_id);

			echo $this->render('_invoice', array(
				'order' 	=> $order,
				'outlet' 	=> $outlet,
				'package' 	=> $package,
			));
		}
	}