<?php
	use \Curl\Curl;

	class Midtrans {
		private $android_api_url 	= '';
		private $server_key			= '';

		public function __construct() {
			$this->android_api_url 	= Config::midtrans()->android_api_url;
			$this->server_key		= Config::midtrans()->server_key;
		}

		public static function app() {
			return new Midtrans;
		}

		public function androidChargeAPI($params = array()) {
			$curl = new Curl();
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_TIMEOUT, 30);
			$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$curl->setHeader('Content-Type', 'application/json');
			$curl->setHeader('Accept', 'application/json');
			$curl->setHeader('Authorization', 'Basic '.base64_encode($this->server_key));
			$curl->post(Config::midtrans()->android_api_url, json_encode($params));
			
			if ($curl->error) {
				return array(
					'code' 		=> $curl->errorCode,
					'message'	=> $curl->errorMessage
				);
			} else {
				return array(
					'code'			=> 200,
					'token' 		=> $curl->response->token,
					'redirect_url'	=> $curl->response->redirect_url,
				);
			}
		}

		public function androidChargeAPI2($params = array()) {
			$curl = new Curl();
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_TIMEOUT, 30);
			$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$curl->setHeader('Content-Type', 'application/json');
			$curl->setHeader('Accept', 'application/json');
			$curl->setHeader('Authorization', 'Basic '.base64_encode($this->server_key));
			$curl->post(Config::midtrans()->android_api_url, $params);
			
			if ($curl->error) {
				return array(
					'code' 		=> $curl->errorCode,
					'message'	=> $curl->errorMessage
				);
			} else {
				return $curl->response;
			}
		}

		public static function validateSignatureKey($order_id, $gross_amount, $status_code, $incoming_signature) {
			$input 		= $order_id.$status_code.$gross_amount.$this->server_key;
			$signature 	= openssl_digest($input, 'sha512');

			if($signature == $incoming_signature) {
				return TRUE;
			}

			return FALSE;
		}
	}