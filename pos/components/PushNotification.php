<?php
	use \Curl\Curl;

	class PushNotification {
		private $api_url = 'https://fcm.googleapis.com/fcm/send';
		private $api_key = 'AAAAhCUXKLU:APA91bGlXXHlFGJ1kbtDOe1oerpN3it1Fe5CkRW33wv7yrAkBOBkUrU-PBx0zelo1Fgl27ufm5r2hc1FXdJPzn1AgJzctPIQTfgZ6FTndmC7ShB8PfoQGmr4UQasqyCgZsHwBEgsuTwJ';

		public $errors  = array();
		public $response;

		public static function app() {
			return new PushNotification;
		}

		public function orderApproved($outlet_id, $order_id, $order_printed_id, $delivery_date, $fcm_token) {
			$delivery_date = Snl::app()->dateFormat($delivery_date);
			// $to 	= '/topics/user_'.$outlet_id;
			$to 	= $fcm_token;
			$title 	= 'Order #'.$order_printed_id.' telah disetujui.';
			$body 	= 'Order anda dengan no order #'.$order_printed_id.' telah disetujui dan akan dikirim pada tanggal '.$delivery_date;

			$params = array(
				'to'		=> $to,
				'content_available' => true,
				'priority' 	=> 'high',
				'sound' 	=> 'default', 
				'data'		=> array(
					'type'		=> 'order_approved',
					'order_id' 	=> $order_printed_id,
					'title'		=> $title,
					'body' 		=> $body,
				)
			);
			

			$curl = new Curl();
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_ENCODING, '');
			$curl->setOpt(CURLOPT_MAXREDIRS, 10);
			$curl->setOpt(CURLOPT_TIMEOUT, 30);
			$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$curl->setHeader('Content-Type', 'application/json');
			$curl->setHeader('Authorization', 'key='.$this->api_key);
			$curl->post($this->api_url, json_encode($params));

			if ($curl->error) {
				$this->errors[] = array(
					'code' 		=> $curl->errorCode,
					'message'	=> $curl->errorMessage
				);

				return FALSE;
			} else {
				$this->response = $curl->response;

				return TRUE;
			}
		}

		public function orderInvoiced($outlet_id, $order_id, $order_printed_id, $total_order, $fcm_token) {
			// $to 	= '/topics/user_'.$outlet_id;
			$to 	= $fcm_token;
			$title 	= 'Invoice Order #'.$order_printed_id;
			$body 	= 'Invoice untuk order #'.$order_printed_id.'. Total '.Snl::app()->formatPrice($total_order);

			$params = array(
				'to'		=> $to,
				'content_available' => true,
				'priority' 	=> 'high',
				'sound' 	=> 'default', 
				'data'		=> array(
					'type'		=> 'order_invoiced',
					'order_id' 	=> $order_printed_id,
					'title'		=> $title,
					'body' 		=> $body,
				)
			);
			

			$curl = new Curl();
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_ENCODING, '');
			$curl->setOpt(CURLOPT_MAXREDIRS, 10);
			$curl->setOpt(CURLOPT_TIMEOUT, 30);
			$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$curl->setHeader('Content-Type', 'application/json');
			$curl->setHeader('Authorization', 'key='.$this->api_key);
			$curl->post($this->api_url, json_encode($params));

			if ($curl->error) {
				$this->errors[] = array(
					'code' 		=> $curl->errorCode,
					'message'	=> $curl->errorMessage
				);

				return FALSE;
			} else {
				$this->response = $curl->response;

				return TRUE;
			}
		}
	}