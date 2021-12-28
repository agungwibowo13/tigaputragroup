<?php
	class ProductController extends BackendController {
		public function __construct() {
			$this->views = 'modules/product/backend/views/product/';
		}

		public function index() {
			$this->page_title = 'Daftar Produk';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/product/create" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-plus"></i></a>';
			
			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function create() {
			$model = new Product;
			
			if(isset($_POST['Product'])) {
				$model->setAttributes($_POST['Product']);

				if($model->save()) {
					Snl::app()->setFlashMessage('Produk baru berhasil ditambahkan.', 'success');
					$this->redirect('admin/product/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Tambah Produk';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/product/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';

			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
			));
		}

		public function update() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Product::model()->findByPk($id);
			
			if(isset($_POST['Product'])) {
				$model->setAttributes($_POST['Product']);

				if($model->save()) {
					Snl::app()->setFlashMessage($model->name.' berhasil diubah.', 'success');
					$this->redirect('admin/product/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Edit Produk';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/product/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
			));
		}

		public function delete() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Product::model()->findByPk($id);
			if($model !== NULL) {
				if($model->delete()) {
					Snl::app()->setFlashMessage($model->name.' berhasil dihapus.', 'success');
				} else {
					Snl::app()->setFlashMessage('Internal server error.', 'danger');
				}
			}

			$this->redirect('admin/product/index');
		}

		// All ajax function
		public function validate() {
			$post = $_POST['post'];
			$data = array();
			$result = array();
			$model = new Product;
			
			if(count($post) > 0) {
				foreach ($post as $key => $value) {
					$name = str_replace(']', '', str_replace('[', '', str_replace($model->classname, '', $value['name'])));
					$data[$name] = $value['value'];
				}
			}

			$id = isset($data['product_id']) ? $data['product_id'] : 0;
			if($id > 0) {
				$model = Product::model()->findByPk($id);
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
			$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'name';
			$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'asc';
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
					$data[] = $product->getData();
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}
	}
