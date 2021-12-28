<?php
	class SettingController extends BackendController {
		public function __construct() {
			$this->views = 'modules/setting/backend/views/setting/';
		}

		public function index() {
			$this->page_title = LabelHelper::getLabel('setting');
			
			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function update() {
			$id 	= isset($_GET['id']) ? $_GET['id'] : 0;
			$model 	= Setting::model()->findByPk($id);

			if(isset($_POST['Setting'])) {
				if($model->input_type == 'file') {
					$existing_file	= Snl::app()->rootDirectory().'uploads/setting/images/'.$model->value;
					$filename 		= $model->value;
					$image_uploaded = FALSE;
					$image_error 	= '';

					if($_FILES['Setting']['name']['value'] == '') {
						$image_uploaded = TRUE;
					} else {
						$target_dir  = Snl::app()->rootDirectory().'uploads/setting/images/';
						$imageFileType = pathinfo(basename($_FILES['Setting']['name']['value']),PATHINFO_EXTENSION);
						$filename 	 = time().'.'.$imageFileType;
						$target_file = $target_dir . $filename;
						$image_valid = TRUE;

						if ($_FILES['Setting']['size']['value'] > 1000000) {
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
							if (move_uploaded_file($_FILES['Setting']['tmp_name']['value'], $target_file)) {
						        $image_uploaded = TRUE;

						        if($model->value != '') {
							        if(file_exists($existing_file)) {
							        	unlink($existing_file);
									}
						        }
						    } else {
						    	$image_error = 'Internal server error.';
						    }
						}
					}

					if($image_uploaded) {
						$model->value = $filename;
						if($model->save()) {
							Snl::app()->setFlashMessage('Setting berhasil disimpan.', 'success');
							$this->redirect('admin/setting/index');
						} else {
							Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
						}
					}
						
				} else {
					$model->setAttributes($_POST['Setting']);
					if($model->save()) {
						Snl::app()->setFlashMessage('Setting berhasil disimpan.', 'success');
						$this->redirect('admin/setting/index');
					} else {
						Snl::app()->setFlashMessage('Kesalahan input.', 'danger');
					}
				}
			}
			

			$this->page_title = LabelHelper::getLabel('edit_setting');
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/setting/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('form', array(
				'toolbar' => $this->toolbar(),
				'model'   => $model
			));
		}

		public function footermenu() {
			$menu_column = isset($_GET['menu_column']) ? $_GET['menu_column'] : 'col-one';
			$active_menu = Menu::model()->findAll(array(
				'condition' => 'menu_column=:menu_column ORDER BY display_order',
				'params'	=> [':menu_column' => $menu_column]
			));

			$active_page_ids = [];
			if($active_menu != NULL) {
				foreach ($active_menu as $obj) {
					$active_page_ids[] = $obj->page_id;
				}
			}

			$notInCondition = '';
			if(count($active_page_ids) > 0) {
				$notInCondition = ' AND page_id NOT IN ('.implode(',',$active_page_ids).')';
			}
			
			$model = Page::model()->findAll(array(
				'condition' => 'is_deleted=:is_deleted AND status=:status'.$notInCondition,
				'params'	=> [':is_deleted' => 0, ':status' => 1]
			));


			$this->page_title = 'Pengaturan Menu Footer';
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/category/index" class="btn btn-default btn-sm pull-right m-l-5"><i class="glyphicon glyphicon-remove"></i></a>';

			$this->toolbarElement .= '<a href="javascript:;" id="btn_save" class="btn btn-primary btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-floppy-disk"></i></a>';

			return $this->render('footer_menu', array(
				'toolbar' => $this->toolbar(),
				'model'	  => $model,
				'active_menu' 	=> $active_menu,
				'menu_column'	=> $menu_column		
			));
		}

		// All ajax function
		public function validate() {
			$post = $_POST['post'];
			$data = array();
			$result = array();
			$model = new Setting;
			
			if(count($post) > 0) {
				foreach ($post as $key => $value) {
					$name = str_replace(']', '', str_replace('[', '', str_replace($model->classname, '', $value['name'])));
					$data[$name] = $value['value'];
				}
			}

			$id = isset($data['setting_id']) ? $data['setting_id'] : 0;
			if($id > 0) {
				$model = Setting::model()->findByPk($id);
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
			$search_query = $this->parseSearchQuery(new Setting, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = count(Setting::model()->findAll(array('condition' => '1 = 1'.$search_query)));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$settings = Setting::model()->findAll(array('condition' => '1 = 1'.$search_query));

			if($settings !== NULL) {
				foreach ($settings as $setting) {
					if($setting->input_type == 'file') {
						$value = "<img src='{$setting->getImage()}' style='height: auto; width: 200px; max-width: 100px;' />";
					} else {
						$value = $setting->value;
					}

					$data[] = array(
						'setting_id' => $setting->setting_id,
						'name'	  => $setting->name,
						'value'	  => $value
					);
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}

		public function savefootermenu() {
			$menu_column = $_POST['menu_column'];
			$data 	= json_decode($_POST['data'], true);
			$index 	= 1;

			$model = Menu::model()->findAll(array(
				'condition' => 'menu_column=:menu_column',
				'params' => [':menu_column' => $menu_column]
			));

			if($model != NULL) {
				foreach ($model as $obj) {
					$obj->delete();
				}
			}

			foreach ($data as $key => $value) {
				$model = new Menu();
				$model->menu_column = $menu_column;
				$model->page_id = $value['page_id'];
				$model->label 	= $value['label'];
				$model->display_order = $index;
				$model->save();
				$index++;
			}

			echo json_encode($data);
		}
	}
