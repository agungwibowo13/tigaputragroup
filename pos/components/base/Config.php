<?php
	class Config {
		const SERVER = 'localhost';
		const DB_USERNAME = 'u4870469_donni';
		const DB_PASSWORD = 'O[gdu^}C,Cg1';
		const DB_NAME = 'u4870469_toko';

		public static function getBaseUrl() {
			$currentPath = $_SERVER['PHP_SELF']; 
		    $pathInfo = pathinfo($currentPath); 
		    $hostName = $_SERVER['HTTP_HOST']; 
		    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'https://';
		    return $protocol.$hostName.$pathInfo['dirname'];
		}

		public static function baseConfig($is_frontend = FALSE) {
			$config = new StdClass();

			if($is_frontend) {
				$config->theme_url 	= self::getBaseUrl().'themes/frontend/';
				$config->site_title = 'Toko';
				$config->copyright 	= '2017 © Toko';
				$config->no_image	= self::getBaseUrl().'uploads/no_image.png';
				$config->no_banner	= self::getBaseUrl().'uploads/no_banner.png';
				$config->display_per_page = 12;
			} else {
				$config->theme_url 	= self::getBaseUrl().'themes/backend/';
				$config->site_title = 'Toko';
				$config->copyright 	= '2017 © Toko';
				$config->avatar_url = self::getBaseUrl().'themes/backend/plugins/images/users/man.png';
				$config->order_id_prefix = 'SO';
				$config->no_image	= self::getBaseUrl().'uploads/no_image.png';
				$config->no_banner	= self::getBaseUrl().'uploads/no_banner.png';
				$config->use_phpmailer = FALSE;
				$config->origin_id	= 444;
			}

			return $config;
		}

		public static function midtrans($is_production = FALSE) {
			$config = new stdClass();
			$config->is_production = FALSE;
			
			if($is_production) {	
				$config->android_api_url 	= 'https://app.midtrans.com/snap/v1/transactions';
				$config->server_key			= 'Mid-server-2pMsQBU_54PhR_JvI43hmX-A';
			} else {
				$config->android_api_url 	= 'https://app.sandbox.midtrans.com/snap/v1/transactions';
				$config->server_key			= 'SB-Mid-server-XULQNGjljCb4Puqecc1oUDj5';
				$config->client_key			= 'SB-Mid-client-jdqI_ucsaC1t2OVd';
			}

			return $config;
		}

		public static function rajaongkir() {
			$config = new stdClass();
			$config->api_url = 'https://api.rajaongkir.com/starter/';
			$config->api_key = '7644d301508250500811ee77ba5cd250';
			
			return $config;
		}

		public static function getRootDirectory() {
			return $_SERVER['DOCUMENT_ROOT'] . '/';
		}
	}