<?php
	class DefaultController extends FrontendController {
		public function __construct() {
			$this->views = 'modules/default/frontend/views/default/';
		}

		public function index() {
		    $this->redirect('default/cashier');
			return $this->render('index');
		}

		public function cashier() {
			return $this->render('cashier');
		}

		public function error() {
			return $this->render('error');	
		}

		// ALL AJAX FUNCTION
		public function searchproduct() {
			$keyword = "";
			$result  = [];

			if(isset($_GET['q'])) {
				$keyword = $_GET['q'];
			}

			$products = ProductMaster::model()->findAll(array(
				'condition' => 'name LIKE "%'.$keyword.'%" ORDER BY rating DESC'
			));

			if($products != NULL) {
				foreach ($products as $key => $product) {
					$result[] = array(
						'id' 	=> $product->product_master_id,
						'name' 	=> strtoupper($product->name),
						'price' => Snl::app()->formatPrice($product->price),
					);
				}
			}

			echo json_encode(array('items' => $result, 'total' => count($products)));
		}

		public function search() {
			$gets = isset($_GET) ? $_GET : array();
			
			$data = array();
			$pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 1;
			$pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;
			$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'updated_on';
			$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'desc';
			$offset = ($pageIndex - 1) * $pageSize;
			$search_query = $this->parseSearchQuery(new ProductMaster, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = count(ProductMaster::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query)));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$products = ProductMaster::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query));

			if($products !== NULL) {
				foreach ($products as $product) {
					$data[] = [
						'product_master_id' => $product->product_master_id,
						'name' => ucwords($product->name),
						'hpp' => Snl::app()->formatPrice($product->hpp),
						'price' => Snl::app()->formatPrice($product->price),
						'remarks' => $product->remarks,
						'updated_on' => Snl::app()->dateTimeFormat($product->updated_on),
						'updated_by' => $product->updated_by,
					];
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}

		public function getproductname() {
			$product_id = $_POST['product_id'];
			$qty 	= $_POST['qty'];
			$price_correction = $_POST['price_correction'];
			$name 	= 'Tidak diketahui';
			$price 	= 0;
			$result = [];
			$product = ProductMaster::model()->findByPk($product_id);
			if($product != NULL) {
				$name = $product->name;
				$price = $product->price;

				if($price_correction > 0) {
					$price = $price_correction;
				}

				$name_formatted = strtoupper($product->name);
				$price_formatted = Snl::app()->formatPrice($price);
				$subtotal = $qty * $price;

				if($product->rounded_price) {
					$subtotal = $product->calculateRoundedPrice($subtotal);
				}

				$subtotal_formatted = Snl::app()->formatPrice($subtotal);
				$result = [
					'product_id' 	=> $product->product_master_id,
					'name' 	=> $name,
					'original_price' => $product->hpp,
					'price' => $price,
					'subtotal' 	=> $subtotal,
					'name_formatted' 	=> $name_formatted,
					'price_formatted'	=> $price_formatted,
					'subtotal_formatted' 	=> $subtotal_formatted,
					'rounded_price' 	=> $product->rounded_price,
				];
			}

			echo json_encode($result);
		}

		public function formatprice() {
			$price = $_POST['total'];
			$price = Snl::app()->formatPrice($price);
			echo json_encode(['price' => $price]);
		}

		public function submitinvoice() {
			$data = json_decode($_POST['post']);
			$total = $_POST['total'];
			$total_profit = 0;

			$invoice = new Invoice();
			$invoice->invoice_date = date('Y-m-d H:i:s');
			// $invoice->invoice_date = '2019-07-20 10:00:00';
			$invoice->total = $total;
			if($invoice->save()) {
				foreach ($data as $key => $value) {
					$profit = $value->subtotal - ($value->original_price * $value->qty);
					$total_profit += $profit;

					$detail = new InvoiceDetail();
					$detail->invoice_id 		= $invoice->invoice_id;
					$detail->product_master_id 	= $value->product_id;
					$detail->original_price 	= $value->original_price;
					$detail->price 	= $value->price;
					$detail->qty 	= $value->qty;
					$detail->profit = $profit;
					$detail->save();

					ProductMaster::model()->updateByAttribute(array(
						'update' 	=> 'rating = rating + 1',
						'condition' => 'product_master_id = :id',
						'params'	=> [':id' => $value->product_id]
					));

					// $product = ProductMaster::model()->findByPk($value->product_id);
					// $product->rating = $product->rating + 1;
					// $product->save();
				}

				$invoice->profit = $total_profit;
				$invoice->save();
			}

			// Snl::app()->setFlashMessage('Barang baru berhasil ditambahkan.', 'success');
			echo true;
		}
	}