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
							'name' 	=> ucwords($product->name),
							'price' => (int)$product->price,
							'is_rounded_price' => $product->rounded_price,
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
					$payment = isset($this->params['payment']) ? $this->params['payment'] : 0;
					$data = isset($this->params['data']) ? $this->params['data'] : NULL;
					$total_profit = 0;
					
					if($data != NULL) {
						if($this->isJSON($data)) {
							$data = json_decode($data);
							$product_count = count($data);
							$product_submitted = 0;

							$invoice = new Invoice();
							$invoice->invoice_date = date('Y-m-d H:i:s');
							$invoice->total = $total;
							$invoice->payment = $payment;
							$invoice->created_by = $this->user_id;
							$invoice->created_on = date('Y-m-d H:i:s');
							$invoice->updated_by = $this->user_id;
							$invoice->updated_on = date('Y-m-d H:i:s');

							if($invoice->save()) {
								foreach ($data as $key => $product) {
									$product_id = $product->product_id;

									if($product_id == 0) {
										$model = ProductMaster::model()->findByAttribute(array(
											'condition' => 'is_manual_product = :is_manual_product AND is_deleted = 0',
											'params'	=> array(':is_manual_product' => 1)
										));

										if($model == NULL) {
											$model = new ProductMaster();
											$model->name = 'Manual Product';
											$model->hpp = $product->price;
											$model->price = $product->price;
											$model->is_manual_product = 1;
											$model->created_by = $this->user_id;
											$model->created_on = date('Y-m-d H:i:s');
											$model->updated_by = $this->user_id;
											$model->updated_on = date('Y-m-d H:i:s');
											if($model->save()) {
												$product_id = $model->product_master_id;	
											}
										} else {
											$product_id = $model->product_master_id;
										}

									} else {
										$model = ProductMaster::model()->findByPk($product_id);
										$profit = ($product->price * $product->qty) - ($model->hpp * $product->qty);
										$total_profit += $profit;
									}

									$detail = new InvoiceDetail();
									$detail->invoice_id 		= $invoice->invoice_id;
									$detail->product_master_id 	= $product_id;
									$detail->product_name 		= $product->name;
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

								$invoice->profit = $total_profit;
								$invoice->save();

								if($product_count == $product_submitted) {
									$result = array(
										'status' => 200,
										'invoice_id' => $invoice->invoice_id,
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
		
		public function getinvoice() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					$date = isset($this->params['date']) ? $this->params['date'] : NULL;
					$result = array(
						'status' 	=> 200,
						'data'		=> NULL,
					);

					if($date != NULL) {
						$invoices = Invoice::model()->findAll(array(
							'condition' => 'date(created_on) = :date AND is_deleted = 0 ORDER BY invoice_id DESC',
							'params'	=> array(':date' => date('Y-m-d', strtotime($date)))
						));

						if($invoices != NULL) {
							foreach ($invoices as $invoice) {
								$user = User::model()->findByPk($invoice->created_by);
								$data[] = array(
									'invoice_id' => $invoice->invoice_id,
									'invoice_date' 	=> date('d M Y H:i:s', strtotime($invoice->created_on)),
									'total' => (int)$invoice->total,
									'payment' => (int)$invoice->payment,
									'created_by' => $user->firstname,
								);
							}

							$result = array(
								'status' 	=> 200,
								'data'		=> $data,
							);
						}
					}
					
					$this->renderJSON($result);
					
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}

		public function getinvoicedetail() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					$invoice_id = isset($this->params['invoice_id']) ? $this->params['invoice_id'] : NULL;
					$result = array(
						'status' 	=> 200,
						'data'		=> NULL,
					);

					if($invoice_id != NULL) {
						$model = InvoiceDetail::model()->findAll(array(
							'condition' => 'invoice_id = :invoice_id',
							'params'	=> array(':invoice_id' => $invoice_id)
						));

						if($model != NULL) {
							foreach ($model as $product) {
								$data[] = array(
									'product_id'	=> $product->product_master_id,
									'name' 			=> ucwords($product->product_name),
									'price' 		=> (int)$product->price,
									'qty'	 		=> (int)$product->qty,
								);
							}

							$result = array(
								'status' 	=> 200,
								'data'		=> $data,
							);
						}
					}
					
					$this->renderJSON($result);
					
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}
	}