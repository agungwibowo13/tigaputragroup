<?php
	class CategoryController extends BackendController {
		public function __construct() {
			$this->views = 'modules/category/backend/views/category/';
		}

		public function index() {
			$this->page_title = 'Kategori';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/category/create" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-plus"></i></a>';

			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function create() {
			$model = new Category;
			
			if(isset($_POST['Category'])) {
				$model->setAttributes($_POST['Category']);

				$target_dir  = Snl::app()->rootDirectory().'uploads/category/images/';
				$imageFileType = pathinfo(basename($_FILES['Category']['name']['image_url']),PATHINFO_EXTENSION);
				$filename 	 = time().'.'.$imageFileType;
				$target_file = $target_dir . $filename;
				$image_error = '';
				$image_valid = TRUE;
				$image_uploaded = FALSE;

				if($_FILES['Category']['name']['image_url'] == '') {
					$image_uploaded = TRUE;
				} else {
					if ($_FILES['Category']['size']['image_url'] > 2000000) {
					    $image_error = 'Ukuran gambar terlalu besar.';
					    $image_valid = FALSE;
					}
					
					if($image_valid) {
						if(!in_array($imageFileType, $model->allowedFileType)) {
							$image_error = 'Format gambar tidak didukung. Silahkan menggunakan format berikut ini: JPG, JPEG & PNG.';
					    	$image_valid = FALSE;
						}
					}

					if($image_valid) {
						if (move_uploaded_file($_FILES['Category']['tmp_name']['image_url'], $target_file)) {
					        $image_uploaded = TRUE;
					    } else {
					    	$image_error = 'Internal server error.';
					    }
					}
				}


				if($image_uploaded) {
					$model->image_url = $filename;
					
					if($model->save()) {
						Snl::app()->setFlashMessage('Kategori baru berhasil ditambahkan.', 'success');
						$this->redirect('admin/category/index');
					} else {
						Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
					}
				} else {
					Snl::app()->setFlashMessage($image_error, 'danger');
				}
			}

			$this->page_title = 'Tambah Kategori';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/category/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';

			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
			));
		}

		public function update() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Category::model()->findByPk($id);
			$existing_file	= Snl::app()->rootDirectory().'uploads/category/images/'.$model->image_url;
			$filename 		= $model->image_url;
			$image_uploaded = FALSE;
			$image_error 	= '';

			if(isset($_POST['Category'])) {
				$model->setAttributes($_POST['Category']);

				if($_FILES['Category']['name']['image_url'] == '') {
					$image_uploaded = TRUE;
				} else {
					$target_dir  = Snl::app()->rootDirectory().'uploads/category/images/';
					$imageFileType = pathinfo(basename($_FILES['Category']['name']['image_url']),PATHINFO_EXTENSION);
					$filename 	 = time().'.'.$imageFileType;
					$target_file = $target_dir . $filename;
					$image_valid = TRUE;

					if ($_FILES['Category']['size']['image_url'] > 2000000) {
					    $image_error = 'Ukuran gambar terlalu besar.';
					    $image_valid = FALSE;
					}
					
					if($image_valid) {
						if(!in_array($imageFileType, $model->allowedFileType)) {
							$image_error = 'Format gambar tidak didukung. Silahkan menggunakan format berikut ini: JPG, JPEG & PNG.';
					    	$image_valid = FALSE;
						}
					}

					if($image_valid) {
						if (move_uploaded_file($_FILES['Category']['tmp_name']['image_url'], $target_file)) {
					        $image_uploaded = TRUE;

					        if(file_exists($existing_file)) {
					        	unlink($existing_file);
							}
					    } else {
					    	$image_error = 'Internal server error.';
					    }
					}
				}

				if($image_uploaded) {
					$model->image_url = $filename;
					
					if($model->save()) {
						Snl::app()->setFlashMessage('Kategori '.$model->name.' berhasil diubah.', 'success');
						$this->redirect('admin/category/index');
					} else {
						Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
					}
				} else {
					Snl::app()->setFlashMessage($image_error, 'danger');
				}

			}

			$this->page_title = 'Edit Kategori';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/category/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model,
			));
		}

		public function delete() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Category::model()->findByPk($id);
			if($model !== NULL) {
				if($model->delete()) {
					Snl::app()->setFlashMessage('Kategori '.$model->name.' berhasil dihapus.', 'success');
				} else {
					Snl::app()->setFlashMessage('Internal server error.', 'danger');
				}
			}

			$this->redirect('admin/category/index');
		}

		// All ajax function
		public function validate() {
			$post = $_POST['post'];
			$data = array();
			$result = array();
			$model = new Category;
			
			if(count($post) > 0) {
				foreach ($post as $key => $value) {
					$name = str_replace(']', '', str_replace('[', '', str_replace($model->classname, '', $value['name'])));
					$data[$name] = $value['value'];
				}
			}

			$id = isset($data['category_id']) ? $data['category_id'] : 0;
			if($id > 0) {
				$model = Category::model()->findByPk($id);
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
			$search_query = $this->parseSearchQuery(new Category, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = count(Category::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query)));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$categories = Category::model()->findAll(array('condition' => 'is_deleted = 0'.$search_query));

			if($categories !== NULL) {
				foreach ($categories as $category) {
					$data[] = array(
						'category_id' => $category->category_id,
						'name'	  	=> $category->name,
						'status'	=> $category->status,
						'image_url' => $category->getImage(),
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
			$category_id = $_POST['category_id'];
			$model = Category::model()->findByPk($category_id);
			if($model) {
				$model->image_url = '';
				if($model->save()) {
					echo true;
				} else {
					echo false;
				}
			} else {
				echo false;
			}
		}
	}
