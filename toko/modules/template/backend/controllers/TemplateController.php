<?php
	class TemplateController extends BackendController {
		public function __construct() {
			$this->views = 'modules/outlet/backend/views/outlet/';
		}

		public function index() {
			$this->page_title = 'Outlet';
			$this->crumbs = array('Outlet', 'Index');
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/outlet/create" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-plus"></i></a>';

			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function create() {
			$model = new Outlet;
			
			if(isset($_POST['Outlet'])) {
				$model->setAttributes($_POST['Outlet']);
				if($model->save()) {

					if(isset($_POST['OutletMachine']['machine_ids'])) {
						$machine_ids = $_POST['OutletMachine']['machine_ids'];
						if(count($machine_ids) > 0) {
							foreach($machine_ids as $machine_id) {
								if(OutletMachine::assignment($model->outlet_id, $machine_id)) {
									Machine::isUsed($machine_id, 1);
								}
							}
						}
					}

					Snl::app()->setFlashMessage('Outlet baru berhasil ditambahkan.', 'success');
					$this->redirect('admin/outlet/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Tambah Outlet';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/outlet/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';

			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
				'outlet_machine'   => new OutletMachine,
				'related_machine'  => [],
			));
		}

		public function update() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Outlet::model()->findByPk($id);
			$model->password_repeat = $model->password;
			
			if(isset($_POST['Outlet'])) {
				$model->setAttributes($_POST['Outlet']);
				if($model->save()) {

					if(isset($_POST['OutletMachine']['machine_ids'])) {
						$current_machine = $model->getRelatedMachine('id_only');
						$machine_ids = $_POST['OutletMachine']['machine_ids'];
						if(count($machine_ids) > 0) {
							foreach ($current_machine as $current_machine_id) {
								if(!in_array($current_machine_id, $machine_ids)) {
									OutletMachine::removeAssignment($model->outlet_id, $current_machine_id);
									Machine::isUsed($current_machine_id, 0);
								}	
							}

							foreach($machine_ids as $machine_id) {
								if(OutletMachine::assignment($model->outlet_id, $machine_id)) {
									Machine::isUsed($machine_id, 1);
								}
							}
						}
					}

					Snl::app()->setFlashMessage('Outlet '.$model->name.' berhasil diubah.', 'success');
					$this->redirect('admin/outlet/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = 'Edit Outlet';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/outlet/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
				'outlet_machine'   => new OutletMachine,
				'related_machine'  => $model->getRelatedMachine(),
			));
		}

		public function delete() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Outlet::model()->findByPk($id);
			if($model !== NULL) {
				if($model->delete()) {
					Snl::app()->setFlashMessage('Outlet '.$model->name.' berhasil dihapus.', 'success');
				} else {
					Snl::app()->setFlashMessage('Internal server error.', 'danger');
				}
			}

			$this->redirect('admin/outlet/index');
		}

		// All ajax function
		public function validate() {
			$post = $_POST['post'];
			$data = array();
			$result = array();
			$model = new Outlet;
			
			if(count($post) > 0) {
				foreach ($post as $key => $value) {
					$name = str_replace(']', '', str_replace('[', '', str_replace($model->classname, '', $value['name'])));
					$data[$name] = $value['value'];
				}
			}

			$id = isset($data['outlet_id']) ? $data['outlet_id'] : 0;
			if($id > 0) {
				$model = Outlet::model()->findByPk($id);
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
			$search_query = $this->parseSearchQuery(new Outlet, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = count(Outlet::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query)));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$outlets = Outlet::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query));

			if($outlets !== NULL) {
				foreach ($outlets as $outlet) {
					$data[] = $outlet->getData();
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}
	}
