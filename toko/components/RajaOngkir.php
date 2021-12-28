<?php
	use \Curl\Curl;

	class RajaOngkir {
		private $api_url = '';
		private $api_key = '';

		public $courier = 'jne';
		public $errors  = array();
		public $response;

		public function __construct() {
			$this->api_url 	= Config::rajaongkir()->api_url;
			$this->api_key	= Config::rajaongkir()->api_key;
		}

		public static function shipping() {
			return new RajaOngkir;
		}

		public function calculateCost($origin_id, $destination_id, $weight = 1000) {
			$params = array(
				'origin' 	  => $origin_id,
				'destination' => $destination_id,
				'weight' 	  => $weight,
				'courier'     => $this->courier
			);

			$curl = new Curl();
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_ENCODING, '');
			$curl->setOpt(CURLOPT_MAXREDIRS, 10);
			$curl->setOpt(CURLOPT_TIMEOUT, 30);
			$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$curl->setHeader('content-type', 'application/x-www-form-urlencoded');
			$curl->setHeader('key', $this->api_key);
			$curl->post($this->api_url.'cost', $params);
			
			if ($curl->error) {
				$this->errors[] = array(
					'code' 		=> $curl->errorCode,
					'message'	=> $curl->errorMessage
				);

				return FALSE;
			} else {
				$this->response = $curl->response;
				if($this->successResponse()) {
					$results = isset($this->response->rajaongkir->results) ? $this->response->rajaongkir->results : NULL;
					$data 	 = array();

					if(is_array($results) && count($results) > 0) {
						foreach ($results as $result) {
							foreach ($result->costs as $c) {
								foreach ($c->cost as $p) {
									$data[] = array(
										'service'     => $c->service,
										'description' => $c->description,
										'cost'		  => $p->value,
										'etd'		  => $p->etd	
									);
								}
							}
						
						}
					}

					return $data;
				} else {
					return FALSE;
				}
			}
		}

		public function getProvinces($id = 0) {
			$params = array();
			if($id > 0) {
				$params['id'] = $id;
			}

			$curl = new Curl();
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_ENCODING, '');
			$curl->setOpt(CURLOPT_MAXREDIRS, 10);
			$curl->setOpt(CURLOPT_TIMEOUT, 30);
			$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$curl->setHeader('key', $this->api_key);
			$curl->get($this->api_url.'province', $params);
			
			if ($curl->error) {
				$this->errors[] = array(
					'code' 		=> $curl->errorCode,
					'message'	=> $curl->errorMessage
				);

				return array();
			} else {
				$this->response = $curl->response;
				if($this->successResponse()) {
					$results   = $this->response->rajaongkir->results;
					$provinces = array();
					if(count($results) > 1) {
						foreach ($results as $result) {
							$provinces[] = array(
								'province_id' => $result->province_id,
								'province'	  => $result->province
							);
						}
					} else {
						$provinces = array(
							'province_id' => $results->province_id,
							'province'	  => $results->province
						);
					}

					return $provinces;
				} else {
					return FALSE;
				}
			}
		}

		public function getCities($province_id = 0, $id = 0) {
			$params = array();
			if($province_id > 0) {
				$params['province'] = $province_id;
			}

			if($id > 0) {
				$params['id'] = $id;
			}

			$curl = new Curl();
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_ENCODING, '');
			$curl->setOpt(CURLOPT_MAXREDIRS, 10);
			$curl->setOpt(CURLOPT_TIMEOUT, 30);
			$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$curl->setHeader('key', $this->api_key);
			$curl->get($this->api_url.'city', $params);
			
			if ($curl->error) {
				$this->errors[] = array(
					'code' 		=> $curl->errorCode,
					'message'	=> $curl->errorMessage
				);

				return array();
			} else {
				$this->response = $curl->response;
				if($this->successResponse()) {
					$results   = $this->response->rajaongkir->results;
					$cities = array();

					if(count($results) > 1) {
						foreach ($results as $result) {
							$cities[] = array(
								'province_id' 	=> $result->province_id,
								'province'	  	=> $result->province,
								'city_id' 		=> $result->city_id,
								'city'	  		=> $result->city_name,
								'type' 			=> $result->type,
								'postal_code'	=> $result->postal_code
							);
						}
					} else {
						$cities = array(
							'province_id' 	=> $results->province_id,
							'province'	  	=> $results->province,
							'city_id' 		=> $results->city_id,
							'city'	  		=> $results->city_name,
							'type' 			=> $results->type,
							'postal_code'	=> $results->postal_code
						);
					}

					return $cities;
				} else {
					return FALSE;
				}
			}
		}

		protected function successResponse() {
			if($this->response->rajaongkir->status->code == 200) {
				return TRUE;
			} else {
				$this->errors[] = array(
					'code' 		=> $this->response->rajaongkir->status->code,
					'message'	=> $this->response->rajaongkir->status->description
				);

				return FALSE;
			}
		}
	}