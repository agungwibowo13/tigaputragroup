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

		
	}