<?php
	class ApiController {
		public $request_type = 'GET';
		public $header;
		public $params = array();
		public $require_valid_token = TRUE;
		public $valid_user_token = TRUE;
		public $user_token = '';
		public $user_id = 0;
		public $action;

		public function init() {
			$this->request_type = $_SERVER['REQUEST_METHOD'];
			$this->header = apache_request_headers();

			if(isset($_GET)) {
				$this->header = $_GET;
			}

			if(isset($this->header['user_token'])) {
				$this->user_token = $this->header['user_token'];
			}

			if ($this->request_type == 'GET') {
				$this->params = array_merge($this->params, (((isset($_GET)) && (count($_GET) >= 1)) ? $_GET : array()));
			} elseif ($this->request_type == 'POST') {
				$this->params = array_merge($this->params, (isset($_POST) ? $_POST : array()));
			} elseif ($this->request_type == 'PUT' || $this->request_type == 'DELETE') {
				$this->params = array_merge($this->params, (((isset($_GET)) && (count($_GET) >= 1)) ? $_GET : array()));
			}

			if(in_array($this->action, array('login', 'logout', 'register', 'forgotpassword', 'getcategory'))) {
				$this->require_valid_token = FALSE;
			}

			if($this->require_valid_token) {
				$this->validateUserToken();
			}
		}

		protected function validateUserToken() {
			if($this->user_token == '' || empty($this->user_token)) {
				$this->valid_user_token = FALSE;
			} else {
				$log_id = (int) SecurityHelper::decrypt($this->user_token);
				$log = UserApiLoginHistory::model()->findByPk($log_id);
				if($log == NULL) {
					$this->valid_user_token = FALSE;
				} else {
					if($log->clock_out == '0000-00-00 00:00:00' || strtotime($log->clock_out) == '') {
						$this->user_id = $log->user_id;
						$this->valid_user_token = TRUE;
					} else {
						$this->valid_user_token = FALSE;
					}
				}
			}
		}

		protected function renderInvalidUserToken() {
			$this->renderErrorMessage(401, 'InvalidUserToken', array('error' => $this->parseErrorMessage(array('user_token' => 'Invalid User Token.'))));
		}

		protected function parseErrorMessage($errors = array()) {
			$msg = array();
			if(count($errors) > 0) {
				foreach ($errors as $key => $error) {
					$msg[] = array(
						'field' 	=> $key,
						'message'	=> $error
					);
				}
			}

			return $msg;
		}

		protected function renderErrorMessage($code = '400', $title = 'BadRequest', $params = array())
		{
			$error_messages = array(
				400 => array(
					'BadRequest'      => 'Your request could not be processed.',
					'InvalidAction'   => 'The action requested was not valid for this resource',
					'InvalidResource' => 'The resource submitted could not be validated.',
					'JSONParseError'  => 'We encountered an unspecified JSON parsing error.',
				),
				401 => array(
					'APIKeyMissing' 	=> 'Your request did not include an API key.',
					'APIKeyInvalid' 	=> 'Your API key may be invalid, or you\'ve attempted to access the wrong data center.',
					'InvalidUserToken'	=> 'Invalid User Token'
				),
				403 => array(
					'Forbidden'       => 'You are not permitted to access this resource.',
					'UserDisabled'    => 'This account has been disabled.',
					'UserBlocked'     => 'This account is on blacklist.',
					'UserUnverified'  => 'Please verify your account.',
					'WrongDataCenter' => 'The API key provided is linked to a different data center.',
				),
				404 => array(
					'ResourceNotFound' => 'The requested resource could not be found.',
				),
				405 => array(
					'MethodNotAllowed' => 'The requested method and resource are not compatible.',
				),
				414 => array(
					'ResourceNestingTooDeep' => 'The sub-resource requested is nested too deeply.',
				),
				429 => array(
					'TooManyRequests' => 'You have exceeded the limit of 10 simultaneous connections.',
				),
				500 => array(
					'InternalServerError' => 'An unexpected internal error has occurred. Please contact Support for more information.',
				),
				503 => array(
					'ComplianceRelated' => 'This method has been disabled.',
				),
			);

			$detail = '';
			if ((isset($error_messages[$code])) && (isset($error_messages[$code][$title]))) {
				$detail = $error_messages[$code][$title];
			}

			// Set the status
			$statusHeader = 'HTTP/1.1 ' . $code . ' ' . $title;
			header($statusHeader);

			$error = array(
				'status'        => $code,
				'method'        => $this->request_type,
				'title'         => $title,
				'detail'        => $detail,
				// 'field_warning' => isset($params['warning']) ? $params['warning'] : array(),
				'field_error'   => isset($params['error']) ? $params['error'] : array(),
			);

			$this->renderJSON($error);
		}

		protected function renderJSON($data)
		{
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($data);
		}

		protected function isJSON($string) {
			json_decode($string);

		    switch (json_last_error()) {
		        case JSON_ERROR_NONE:
		            return TRUE;
		        break;
		        case JSON_ERROR_DEPTH:
		            return FALSE;
		        break;
		        case JSON_ERROR_STATE_MISMATCH:
		            return FALSE;
		        break;
		        case JSON_ERROR_CTRL_CHAR:
		            return FALSE;
		        break;
		        case JSON_ERROR_SYNTAX:
		            return FALSE;
		        break;
		        case JSON_ERROR_UTF8:
		            return FALSE;
		        break;
		        default:
		            return FALSE;
		        break;
		    }
		}
	}