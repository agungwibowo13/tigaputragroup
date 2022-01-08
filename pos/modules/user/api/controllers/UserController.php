<?php
	class UserController extends ApiController {
		public function login() {
			if($this->request_type == 'POST') {
				$model = new User;
				$model->username = isset($this->params['username']) ? $this->params['username'] : '';
				$model->password = isset($this->params['password']) ? $this->params['password'] : '';
				$valid_user = $model->validateApiLogin();

				if($valid_user == 1) {
					$model = User::model()->findByPk($model->user_id);

					$last_login = UserApiLoginHistory::model()->findByAttribute(array(
						'condition' => 'user_id = :user_id AND clock_out IS NULL ORDER BY clock_in DESC',
						'params'	=> array(':user_id' => $model->user_id)
					));

					if($last_login != NULL) {
						$last_login->clock_out =  Snl::app()->dateNow();
						$last_login->save();
					}

					$log = new UserApiLoginHistory;
					$log->user_id 	= $model->user_id;
					$log->clock_in 	= Snl::app()->dateNow();
					$log->save();

					$result = array(
						'status'        => 200,
						'user_token'    => SecurityHelper::encrypt($log->api_login_history_id),
						'data'			=> $model->getApiLoginInformation()
					);

					$this->renderJSON($result);
				} elseif($valid_user == 2) { // blacklisted user
					// $this->renderErrorMessage(403, 'UserBlocked');
					$this->renderErrorMessage(403, 'UserBlocked', array(
							'error' => $this->parseErrorMessage(array('email' => 'Akun anda terblokir.'))
						)
					);
				} elseif($valid_user == 3) { // unverified user
					// $this->renderErrorMessage(403, 'UserUnverified');
					$this->renderErrorMessage(403, 'UserUnverified', array(
							'error' => $this->parseErrorMessage(array('email' => 'Email belum terverifikasi.'))
						)
					);
				} else { // invalid username or password
					$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('password' => 'Email atau Password tidak cocok.'))));
				}
			} else {
				$this->renderErrorMessage(405, 'MethodNotAllowed');
			}
		}

		public function logout() {
			if($this->request_type == 'POST') {
				$user_token = $this->user_token;
				if(empty($user_token) || $user_token == '') {
					$this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('user_token' => 'User Token not found.'))));
				} else {
					$login_id = (int) SecurityHelper::decrypt($user_token);
					$model = UserApiLoginHistory::model()->findByPk($login_id);
					if($model != NULL) {
						$model->clock_out = Snl::app()->dateNow();
						$model->save();

						$result = array(
							'status' => 200,
						);
						$this->renderJSON($result);
					} else {
						// $this->renderErrorMessage(400, 'InvalidResource', array('error' => $this->parseErrorMessage(array('user_token' => 'User Token not found.'))));
						$this->renderInvalidUserToken();
					}
				}
			} else {
				$this->renderErrorMessage(405, 'MethodNotAllowed');
			}
		}
		
		public function getuser() {
			if($this->valid_user_token) {
				if($this->request_type == 'GET') {
					$user = User::model()->findByPk($this->user_id);
					
					$result = array(
						'status' => 200,
						'username'	=> $user->username,
						'email' 	=> $user->email,
						'firstname' => $user->firstname,
					);

					$this->renderJSON($result);
				} else {
					$this->renderErrorMessage(405, 'MethodNotAllowed');
				}
			} else {
				$this->renderInvalidUserToken();
			}
		}
		
	}