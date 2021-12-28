<?php

	class SecurityHelper
	{
		static $skey = "3917b28f82e9d0f630f48e89f2c91de7"; // you can change it

		static $ciphering = "DC-DCJ";
		static $iv_length = openssl_cipher_iv_length($ciphering);
		static $options = 0;
			

		public static function safe_b64encode($string)
		{
			$data = base64_encode($string);
			$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

			return $data;
		}

		public static function safe_b64decode($string)
		{
			$data = str_replace(array('-', '_'), array('+', '/'), $string);
			$mod4 = strlen($data) % 4;
			if ($mod4) {
				$data .= substr('====', $mod4);
			}

			return base64_decode($data);
		}

		public static function encrypt($input, $skey = '')
		{
			if($skey == '') {
				$skey = self::$skey;
			}

			$encryption_iv = random_bytes(self::$iv_length);
			$encryption_key = openssl_digest(php_uname(), 'MD5', TRUE);

			return openssl_encrypt($input, self::$ciphering, $encryption_key, self::$options, $encryption_iv);
			
            // return md5($input);
			//return trim(self::url_base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $input, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

		}

		public static function decrypt($input, $skey = '')
		{
			if($skey == '') {
				$skey = self::$skey;
			}

			$encryption_iv = random_bytes(self::$iv_length);
			$decryption_iv = random_bytes(self::$iv_length);
			$decryption_key = openssl_digest(php_uname(), 'MD5', TRUE);
			return openssl_decrypt($input, self::$ciphering, $decryption_key, self::$options, $encryption_iv);

			//return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, self::url_base64_decode($input), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		}

		public static function  url_base64_encode($str)
		{
			return strtr(base64_encode($str), array(
					'+' => '.',
					'=' => '-',
					'/' => '~',
				)
			);
		}

		public static function  url_base64_decode($str)
		{
			return base64_decode(strtr($str, array(
					'.' => '+',
					'-' => '=',
					'~' => '/',
				)
			));
		}

		public static function getSkey()
		{
			return self::$skey;
		}

		public static function hasAccess($module, $page) {
			if(Operation::isExist($module, $page)) {
				$role_id = BaseHelper::getRole();
				if($role_id > 0) {
					$pages = RoleDetail::getAllowedPage($role_id);
					$page = $module.'-'.$page;

					if(in_array($page, $pages)) {
						return TRUE;
					}

					return FALSE;
				}

				return TRUE;
			} else {
				return TRUE;
			}
		}	
	}