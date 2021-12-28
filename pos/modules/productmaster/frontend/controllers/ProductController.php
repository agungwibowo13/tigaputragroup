<?php
	class ProductController extends FrontendController {
		public function __construct() {
			$this->views = 'modules/product/frontend/views/product/';
		}

		public function delete() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Product::model()->findByPk($id);
			if($model !== NULL) {
				if($model->delete()) {
					Snl::app()->setFlashMessage('Produk '.$model->name.' berhasil dihapus.', 'success');
				} else {
					Snl::app()->setFlashMessage('Internal server error.', 'danger');
				}
			}

			$this->redirect('vendor/product');
		}

		public function category() {
			if(!Snl::app()->isVendor()) {
				$this->redirect('vendor/login');
			}

			$categories = Category::model()->findAll(array(
				'condition' => 'is_deleted = 0',
			));


			// $products = Product::model()->findAll(array(
			// 	'condition' => 'category_id = :category_id AND is_deleted = 0',
			// 	'params'	=> [':category_id' => $category->categ]
			// ));

			$this->page_title 	 = 'Kategori Produk';
			$this->crumbs[] = array(
				'url' 	=> $this->createUrl(''),
				'name' 	=> '<i class="fa fa-home"></i>'
			);

			$this->crumbs[] = array(
				'url' 	=> $this->createUrl('product/category'),
				'name' 	=> 'Kategori Produk'
			);

			return $this->render('category_list', array(
				'categories' => $categories,
			));
		}

		public function viewcategory() {
			if(!Snl::app()->isVendor()) {
				$this->redirect('vendor/login');
			}
			
			$name = isset($_GET['name']) ? $_GET['name'] : '';

			$category = Category::model()->findByAttribute(array(
				'condition' => 'is_deleted = 0 AND url_key = :url_key',
				'params'	=>[':url_key' => $name]
			));

			if($category == null) {
				$this->redirect('default/error');
			}

			$products = Product::model()->findAll(array(
				'condition' => 'category_id = :category_id AND is_deleted = 0',
				'params'	=> [':category_id' => $category->category_id]
			));

			$this->page_title = $category->name;
			$this->page_subtitle = 'Kategori Produk';
			$this->crumbs[] = array(
				'url' 	=> $this->createUrl(''),
				'name' 	=> '<i class="fa fa-home"></i>'
			);

			$this->crumbs[] = array(
				'url' 	=> $this->createUrl('product/category'),
				'name' 	=> 'Kategori Produk'
			);

			$this->crumbs[] = array(
				'url' 	=> '#',
				'name' 	=> $category->name
			);

			return $this->render('category_view', array(
				'category' => $category,
				'products' => $products,
			));
		}

		public function view() {
			$product_id = $_GET['id'];
			$product = Product::model()->findByPk($product_id);

			echo $this->render('partial/_view_product', array(
				'product' => $product,
				'images'  => $product->getAllImages(),
			));	
		}


		// All ajax function
		public function search() {
			$gets = isset($_GET) ? $_GET : array();
			
			$data = array();
			$pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 1;
			$pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;
			$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'created_on';
			$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'desc';
			$offset = ($pageIndex - 1) * $pageSize;
			$search_query = $this->parseSearchQuery(new Product, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = count(Product::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query)));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$products = Product::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query));

			if($products !== NULL) {
				foreach ($products as $product) {
					$category = Category::model()->findByPk($product->category_id);
					$data[] = array(
						'product_id' 	=> $product->product_id,
						'name'	  		=> $product->name,
						'category'		=> $category->name,
						'description'	=> $product->description,
						'image_url' 	=> $product->getImage(),
					);
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}

		public function deleteimage() {
			$product_image_id = $_POST['product_image_id'];
			$image = ProductImage::model()->findByPk($product_image_id);
			if($image->delete()) {
				$result = ['success' => true];
			} else {
				$result = ['success' => false];
			}

			echo json_encode($result);
		}
	}