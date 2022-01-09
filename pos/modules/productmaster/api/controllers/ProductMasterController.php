<?php
	class ProductMasterController extends ApiController {
		public function productsearch() {
			if($this->request_type == 'GET') {
				$data 		 = array();
				$keyword 	 = isset($this->params['keyword']) ? $this->params['keyword'] : 0;
				//$page 		 = isset($this->params['page']) ? $this->params['page'] : 1;
				//$display_per_page = 10;

				//$offset 	 = $display_per_page * ($page - 1);
				//$pagination  = ' LIMIT '.$display_per_page.' OFFSET '.$offset;
				$order 		 = ' ORDER BY tbl_product_master.rating DESC';

				//$total_products = Product::model()->searchCount($keyword);
				//$total_pages = ceil($total_products / $display_per_page);

				$products = ProductMaster::model()->search($keyword, $order);

				if($products != NULL) {
					foreach ($products as $product) {
						$data[] = array(
							'product_master_id' => $product->product_master_id,
							'name' 	=> $product->name,
							'price' => (int)$product->price,
						);
					}
				}


				$result = array(
					'status' 	=> 200,
					'product'	=> $data,
				);

				$this->renderJSON($result);
			} else {
				$this->renderErrorMessage(405, 'MethodNotAllowed');
			}
		}


		public function submitorder() {
			// [{"product_id":1,"qty":2,"price":21500},{"product_id":35,"qty":2,"price":32000},{"product_id":78,"qty":1,"price":42000}]
			
			if($this->valid_user_token) {
				if($this->request_type == 'POST') {
					$total = isset($this->params['total']) ? $this->params['total'] : 0;
					$data = isset($this->params['data']) ? $this->params['data'] : NULL;
					
					if($data != NULL) {
						if($this->isJSON($data)) {
							$data = json_decode($data);
							$product_count = count($data);
							$product_submitted = 0;

							$invoice = new Invoice();
							$invoice->invoice_date = date('Y-m-d H:i:s');
							$invoice->total = $total;
							$invoice->created_by = $this->user_id;
							$invoice->created_on = date('Y-m-d H:i:s');
							$invoice->updated_by = $this->user_id;
							$invoice->updated_on = date('Y-m-d H:i:s');

							if($invoice->save()) {
								foreach ($data as $key => $product) {
									//$profit = $value->subtotal - ($value->original_price * $value->qty);
									//$total_profit += $profit;

									$detail = new InvoiceDetail();
									$detail->invoice_id 		= $invoice->invoice_id;
									$detail->product_master_id 	= $product->product_id;
									$detail->price 	= $product->price;
									$detail->qty 	= $product->qty;
									
									if($detail->save()) {
										ProductMaster::model()->updateByAttribute(array(
											'update' 	=> 'rating = rating + 1',
											'condition' => 'product_master_id = :id',
											'params'	=> [':id' => $product->product_id]
										));
										$product_submitted++;
									}
								}

								//$invoice->profit = $total_profit;
								//$invoice->save();

								if($product_count == $product_submitted) {
									$result = array(
										'status' => 200
									);

									$this->renderJSON($result);
								}

							} else {
								$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage($invoice->errors)));
							}
						} else {
							$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('product' => 'Product must be JSON type'))));
						}
					} else {
						$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('product' => 'No product selected'))));
					}
					
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}


			} else {
				$this->renderInvalidUserToken();
			}
		}
		
	}