<?php
	class ProductMasterController extends ApiController {
		public function getallproducts() {
			$products = new ProductMaster;
			$products = ProductMaster::model()->findByAll()

			if($products !== NULL) {
				foreach ($products as $product) {
					$margin_percentage = round((($product->price - $product->hpp) / $product->hpp) * 100, 2);
					$data[] = [
						'product_master_id' => $product->product_master_id,
						'name' => ucwords($product->name),
						'hpp' => Snl::app()->formatPrice($product->hpp),
						'price' => Snl::app()->formatPrice($product->price),
						'grosir' => Snl::app()->formatPrice($product->grosir),
						'margin_percentage' => $margin_percentage,
						'remarks' => $product->remarks,
						'updated_on' => Snl::app()->dateTimeFormat($product->updated_on),
						'updated_by' => $product->updated_by,
					];
				}

				$this->renderJSON($data);
			}
			
		}

	}
