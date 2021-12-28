<?php
	class UserController extends BackendController {
		public function __construct() {
			$this->views = 'modules/user/backend/views/user/';
		}

		public function login() {
			if(Snl::app()->isAdmin()) {
				$this->redirect('admin/productmaster/index');
			}

			$model = new User;

			if(isset($_POST['User'])) {
				$model->username = $_POST['User']['username'];
				$model->password = $_POST['User']['password'];
				if($model->validateLogin()) {
					$this->redirect('default/cashier');
				} else {
					Snl::app()->setFlashMessage('Username atau password salah.', 'danger');
				}
			}

			return $this->render('login', array(
				'model' => $model
			));
		}

		public function logout() {
			Snl::session()->unsetSession(SecurityHelper::encrypt('backendlogin'));
			$this->redirect('admin/user/login');
		}

		public function index() {
			$this->page_title = LabelHelper::getLabel('manage_user');
			$this->crumbs = array('User', 'Index');
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/user/create" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-plus"></i></a>';

			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function create() {
			$model = new User;
			if(isset($_POST['User'])) {
				$model->setAttributes($_POST['User']);
				if($model->save()) {
					Snl::app()->setFlashMessage('User baru berhasil ditambahkan.', 'success');
					$this->redirect('admin/user/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = LabelHelper::getLabel('create_user');
			$this->crumbs = array('User', 'Create');
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/user/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model
			));
		}

		public function update() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = User::model()->findByPk($id);
			$model->password_repeat = $model->password;

			if(isset($_POST['User'])) {
				$model->setAttributes($_POST['User']);
				if($model->save()) {
					Snl::app()->setFlashMessage('User '.$model->username.' berhasil diubah.', 'success');
					$this->redirect('admin/user/index');
				} else {
					Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
				}
			}

			$this->page_title = LabelHelper::getLabel('edit_user');
			$this->crumbs = array('User', 'Edit');
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/user/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model
			));
		}

		public function delete() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = User::model()->findByPk($id);
			if($model !== NULL) {
				if($model->delete()) {
					Snl::app()->setFlashMessage('User '.$model->username.' berhasil dihapus.', 'success');
				} else {
					Snl::app()->setFlashMessage('Internal server error.', 'danger');
				}
			}

			$this->redirect('admin/user/index');
		}

		// All ajax function
		public function validate() {
			$post = $_POST['post'];
			$data = array();
			$result = array();
			$model = new User;

			if(count($post) > 0) {
				foreach ($post as $key => $value) {
					$name = str_replace(']', '', str_replace('[', '', str_replace($model->classname, '', $value['name'])));
					$data[$name] = $value['value'];
				}
			}

			$id = isset($data['user_id']) ? $data['user_id'] : 0;
			if($id > 0) {
				$model = User::model()->findByPk($id);
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
			$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'username';
			$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'asc';
			$offset = ($pageIndex - 1) * $pageSize;
			$search_query = $this->parseSearchQuery(new User, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = count(User::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query)));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$users = User::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query));

			if($users !== NULL) {
				foreach ($users as $user) {
					$data[] = $user->getData();
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}
	}