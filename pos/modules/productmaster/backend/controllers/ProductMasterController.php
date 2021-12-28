<?php
	class ProductMasterController extends BackendController {
		public function __construct() {
			$this->views = 'modules/productmaster/backend/views/productmaster/';
		}

		public function index() {
			$this->page_title = 'Daftar Barang';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/productmaster/create" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-plus"></i></a>';
			
			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function create() {
			$model = new ProductMaster;
			
			if(isset($_POST['ProductMaster'])) {
				$model->setAttributes($_POST['ProductMaster']);

				if($model->save()) {
					Snl::app()->setFlashMessage('Barang baru berhasil ditambahkan.', 'success');
					$this->redirect('admin/productmaster/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Tambah Barang';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/productmaster/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';

			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
			));
		}

		public function update() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = ProductMaster::model()->findByPk($id);
			$model->hpp = (int)$model->hpp;
			$model->price = (int)$model->price;
			$model->grosir = (int)$model->grosir;
			
			if(isset($_POST['ProductMaster'])) {
				$model->setAttributes($_POST['ProductMaster']);

				if($model->save()) {
					Snl::app()->setFlashMessage($model->name.' berhasil diubah.', 'success');
					$this->redirect('admin/productmaster/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Edit Barang';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/productmaster/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
			));
		}

		public function delete() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = ProductMaster::model()->findByPk($id);
			if($model !== NULL) {
				if($model->delete()) {
					Snl::app()->setFlashMessage($model->name.' berhasil dihapus.', 'success');
				} else {
					Snl::app()->setFlashMessage('Internal server error.', 'danger');
				}
			}

			$this->redirect('admin/productmaster/index');
		}

		// All ajax function
		public function validate() {
			$post = $_POST['post'];
			$data = array();
			$result = array();
			$model = new ProductMaster;
			
			if(count($post) > 0) {
				foreach ($post as $key => $value) {
					$name = str_replace(']', '', str_replace('[', '', str_replace($model->classname, '', $value['name'])));
					$data[$name] = $value['value'];
				}
			}

			$id = isset($data['product_master_id']) ? $data['product_master_id'] : 0;
			if($id > 0) {
				$model = ProductMaster::model()->findByPk($id);
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
			$margin_percentage = 0;
			
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
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}
	}
