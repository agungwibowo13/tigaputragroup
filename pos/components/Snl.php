<?php
	class Snl {
		public $errorElement = '<div class="help-block with-errors"></div>';

		public static function app() {
			$snl = new Snl();
			return $snl;
		}

		public static function session() {
			$snl = new Snl();
			return $snl;
		}

		public static function chtml() {
			$snl = new Snl();
			return $snl;
		}

		public static function cart() {
			return new ShoppingCart;
		}

		public static function getInventoryStatus() {
			return array(
				'In Stock' => 'In Stock',
				'Out of Stock' => 'Out of Stock',
			);
		}

		public static function getYesNoOptions() {
			return array(
				'1' => 'Ya',
				'0' => 'Tidak',
			);
		}

		public static function getDiscountType() {
			return array(
				'Amount' => 'Amount',
				'Percent' => 'Percent',
			);
		}

		public static function getStatus() {
			return array(
				'1' => 'Active',
				'0' => 'Inactive',
			);
		}

		public static function getVendorStatus() {
			return array(
				'Enable' => 'Enable',
				'Disable' => 'Disable',
			);
		}

		public static function getGender() {
			return array(
				'1' => 'Laki-Laki',
				'0' => 'Perempuan',
			);
		}

		public static function getDeviceType() {
			return array(
				'Desktop' => 'Desktop',
				'Mobile' => 'Mobile',
			);
		}

		public static function getPageType() {
			return array(
				'Page' => 'Page',
				'News' => 'News',
			);
		}

		public static function getUom() {
			return array(
				'ML' => 'ML',
				'L' => 'L',
			);
		}

		public static function getPackaging() {
			return array(
				'Botol' => 'Botol',
				'Jerigen' => 'Jerigen',
			);
		}

		public static function getOrderStatus() {
			return array(
				'1' => 'Sukses',
				'0' => 'Gagal',
			);
		}

		public static function get_client_ip() {
		    $ipaddress = '';
		    if (getenv('HTTP_CLIENT_IP'))
		        $ipaddress = getenv('HTTP_CLIENT_IP');
		    else if(getenv('HTTP_X_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		    else if(getenv('HTTP_X_FORWARDED'))
		        $ipaddress = getenv('HTTP_X_FORWARDED');
		    else if(getenv('HTTP_FORWARDED_FOR'))
		        $ipaddress = getenv('HTTP_FORWARDED_FOR');
		    else if(getenv('HTTP_FORWARDED'))
		       $ipaddress = getenv('HTTP_FORWARDED');
		    else if(getenv('REMOTE_ADDR'))
		        $ipaddress = getenv('REMOTE_ADDR');
		    else
		        $ipaddress = 'UNKNOWN';
		    return $ipaddress;
		}

		public static function generateRandomString($length = 10) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}

		public static function excerpt($str, $startPos = 0, $maxLength = 100) {
			if(strlen($str) > $maxLength) {
				$excerpt   = substr($str, $startPos, $maxLength-3);
				$lastSpace = strrpos($excerpt, ' ');
				$excerpt   = substr($excerpt, 0, $lastSpace);
				$excerpt  .= '...';
			} else {
				$excerpt = $str;
			}
			
			return $excerpt;
		}

		// BASIC
		public function config($is_frontend = FALSE) {
			return Config::baseConfig($is_frontend);
		}

		public function rootDirectory() {
			// return $_SERVER['DOCUMENT_ROOT'] . '/snl/e-commerce/';
			// return $_SERVER['DOCUMENT_ROOT'] . '/';
			return Config::getRootDirectory();
		}

		public function baseUrl() {
			return Config::getBaseUrl();
		}

		public static function base64_to_jpeg($base64_string, $output_file) {
    		// open the output file for writing
		    $ifp = fopen( $output_file, 'wb' ); 

		    // split the string on commas
		    // $data[ 0 ] == "data:image/png;base64"
		    // $data[ 1 ] == <actual base64 string>
		    $data = explode( ',', $base64_string );

		    // we could add validation here with ensuring count( $data ) > 1
		    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

		    // clean up the file resource
		    fclose( $ifp ); 

		    return true; 
		}

		public function isAdmin() {
			$login_data = $this->getSession(SecurityHelper::encrypt('backendlogin'));
			if($login_data === FALSE) {
				return FALSE;
			}

			$login_data = json_decode($login_data);
			if(isset($login_data->username)) {
				return TRUE;
			}

			return FALSE;
		}

		public function isVendor() {
			$login_data = $this->getSession(SecurityHelper::encrypt('frontendlogin'));
			if($login_data === FALSE) {
				return FALSE;
			}

			$login_data = json_decode($login_data);
			if(isset($login_data->vendor_id)) {
				return TRUE;
			}

			return FALSE;
		}

		public function user() {
			$login_data = $this->getSession(SecurityHelper::encrypt('backendlogin'));
			if($login_data === FALSE) {
				return '';
			}

			$login_data = json_decode($login_data);
			return $login_data;
		}

		public function vendor() {
			$login_data = $this->getSession(SecurityHelper::encrypt('frontendlogin'));
			if($login_data === FALSE) {
				return '';
			}

			$login_data = json_decode($login_data);
			return $login_data;
		}
		// END OF BASIC

		// SESSION
		public function isSessionExist($name = NULL) {
			if(!empty($name)) {
				if(isset($_SESSION[$name])) {
					return TRUE;
				}
			}

			return FALSE;
		}

		public function unsetSession($name = NULL) {
			if(!empty($name)) {
				if(isset($_SESSION[$name])) {
					unset($_SESSION[$name]);
				}
			}
		}

		public function createSession($name = NULL, $value = NULL) {
			if(!empty($name) && !empty($value)) {
				$_SESSION[$name] = $value;
			}
		}

		public function getSession($name = NULL) {
			if(isset($_SESSION[$name])) {
				return $_SESSION[$name];
			}

			return FALSE;
		}
		// END OF SESSION
		
		// FLASH MESSSAGE
		public function setFlashMessage($msg, $type = '') {
			$msg_element = '<div class="row"><div class="col-sm-12"><div class="alert alert-dismissable alert-'.$type.'"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$msg.' </div></div></div>';
			$this->createSession('flashmessage', $msg_element);
		}

		public function getFlashMessage() {
			$msg = $this->getSession('flashmessage');
			$this->unsetSession('flashmessage');
			return $msg;
		}
		// END OF FLASH MESSAGE

		// NUMBER FORMATTING
		public function formatPrice($price, $currency = 'Rp') {
			return $currency.' '.number_format($price);
		}

		public function generateInvoiceNo($number = 1, $len = 4) {
			$result = '';
			for($i = 0; $i < ($len - strlen($number)); $i++) {
				$result .= '0';
			}

			$result = 'INVTTJ'.$result.$number;
			return $result;
		}

		// END OF NUMBER FORMATTING

		// DATE FORMATTING
		public function dateNow() {
			date_default_timezone_set('Asia/Jakarta');
 			$date = date('Y-m-d H:i:s') ;

 			return $date;
		}

		public function dateTimeFormat($date = NULL) {
			if(!is_null($date)) {
				$result = date('d M Y H:i', strtotime($date));

				return $result;
			}

			return '';
		}

		public function dateFormat($date = NULL) {
			if(!is_null($date)) {
				$result = date('d M Y', strtotime($date));

				return $result;
			}

			return '';
		}

		public function mysqlDateFormat($date = NULL) {
			$result = date('Y-m-d');
			if(!is_null($date)) {
				$result = date('Y-m-d', strtotime($date));
			}

			return $result;
		}

		public function mysqlDateTimeFormat($date = NULL) {
			$result = date('Y-m-d H:i:s');
			if(!is_null($date)) {
				$result = date('Y-m-d H:i:s', strtotime($date));
			}

			return $result;
		}
		// END OF DATE FORMATTING

		// SLUG GENERATOR
		public static function generateSlug($str, $options = array())
		{
			// Make sure string is in UTF-8 and strip invalid UTF-8 characters
			$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
			
			$defaults = array(
				'delimiter'     => '-',
				'limit'         => NULL,
				'lowercase'     => TRUE,
				'replacements'  => array(),
				'transliterate' => FALSE,
			);
			
			// Merge options
			$options = array_merge($defaults, $options);
			
			$char_map = array(
				// Latin
				'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
				'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
				'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
				'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
				'ß' => 'ss',
				'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
				'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
				'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
				'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
				'ÿ' => 'y',
				// Latin symbols
				'©' => '(c)',
				// Greek
				'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
				'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
				'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
				'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
				'Ϋ' => 'Y',
				'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
				'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
				'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
				'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
				'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
				// Turkish
				'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
				'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
				// Russian
				'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
				'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
				'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
				'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
				'Я' => 'Ya',
				'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
				'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
				'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
				'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
				'я' => 'ya',
				// Ukrainian
				'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
				'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
				// Czech
				'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
				'Ž' => 'Z',
				'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
				'ž' => 'z',
				// Polish
				'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
				'Ż' => 'Z',
				'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
				'ż' => 'z',
				// Latvian
				'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
				'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
				'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
				'š' => 's', 'ū' => 'u', 'ž' => 'z',
			);
			
			// Make custom replacements
			$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
			
			// Transliterate characters to ASCII
			if ($options['transliterate']) {
				$str = str_replace(array_keys($char_map), $char_map, $str);
			}
			
			// Replace non-alphanumeric characters with our delimiter
			$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
			
			// Remove duplicate delimiters
			$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
			
			// Truncate slug to max. characters
			$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
			
			// Remove delimiter from ends
			$str = trim($str, $options['delimiter']);
			
			return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
		}
		// END OF SLUG GENERATOR

		// HTML ELEMENT
		public function extractAttributes($name, $options) {
			$name = !empty($name) ? " name={$name}" : "";
			$attributes = '';

			if(count($options) > 0) {
				foreach ($options as $key => $option) {
					$attributes .= "{$key}='{$option}' ";
				}
			}

			if(!empty($attributes)) {
				if(!isset($options['class'])) {
					$attributes = ' class="form-control" '.$attributes;
				} else {
					$attributes = ' '.$attributes;
				}
			}

			$attributes = $name.$attributes;
			return $attributes;
		}

		public function textbox($name = '', $options = array()) {
			$attributes = $this->extractAttributes($name, $options);
			return '<input type="text"'.$attributes.'/>';
		}

		public function activeTextbox($model, $name = '', $options = array()) {
			$options['id'] = $model->classname.'_'.$name;
			$options['value'] = $model->$name;
			if(!isset($options['placeholder'])) {
				$options['placeholder'] = $model->getLabel($name);
			}

			$name = "$model->classname[$name]";
			$attributes = $this->extractAttributes($name, $options);

			return '<input type="text"'.$attributes.'/>'.$this->errorElement;
		}

		public function activeEmail($model, $name = '', $options = array()) {
			$options['id'] = $model->classname.'_'.$name;
			$options['value'] = $model->$name;
			if(!isset($options['placeholder'])) {
				$options['placeholder'] = $model->getLabel($name);
			}

			$name = "$model->classname[$name]";
			$attributes = $this->extractAttributes($name, $options);

			return '<input type="email"'.$attributes.'/>'.$this->errorElement;
		}

		public function activePassword($model, $name = '', $options = array()) {
			$options['id'] = $model->classname.'_'.$name;
			$options['value'] = $model->$name;
			if(!isset($options['placeholder'])) {
				$options['placeholder'] = $model->getLabel($name);
			}

			$name = "$model->classname[$name]";
			$attributes = $this->extractAttributes($name, $options);

			return '<input type="password"'.$attributes.'/>'.$this->errorElement;
		}

		public function activeDropdown($model, $name = '', $data = array(), $options = array()) {
			$options['id'] = $model->classname.'_'.$name;
			$attributes = $this->extractAttributes($model->classname.'['.$name.']', $options);
			$options_element = '';
			if(count($data) > 0) {
				foreach ($data as $value => $text) {
					$selected = strtolower($value) == strtolower($model->$name) ? ' selected' : '';
					$options_element .= "<option value='{$value}'{$selected}>{$text}</option>";
				}
			}
			
			return '<select'.$attributes.'>'.$options_element.'</select>'.$this->errorElement;
		}

		public function activeTextarea($model, $name = '', $options = array()) {
			$options['id'] = $model->classname.'_'.$name;
			$options['value'] = $model->$name;
			if(!isset($options['placeholder'])) {
				$options['placeholder'] = $model->getLabel($name);
			}

			$name = "$model->classname[$name]";
			$attributes = $this->extractAttributes($name, $options);

			return '<textarea'.$attributes.'>'.$options['value'].'</textarea>'.$this->errorElement;
		}

		// END OF HTML ELEMENT
		
		// EMAIL TEMPLATE
		public function getForgotPasswordEmailTemplate($password) {
			return <<<HTML
<table style="background:#f9f9f9; color:#373737; font-size:17px; line-height:24px; margin:0; padding:0; width:100%" border="0">
    <tbody>
        <tr>
            <td>
                <table style="background:white; margin-bottom:1rem" align="center" border="0" cellpadding="32">
                    <tbody>
                    	<tr style="background: #009041;">
                    		<td style="padding-top: 10px; padding-bottom: 10px; color: #FFF;">Angkajaya Agro</td>
                    	</tr>
                        <tr>
                            <td style="padding-top: 15px;">
                                <div style="max-width:600px;margin:0 auto">
                                    <div style="background:white;border-radius:0.5rem;margin-bottom:1rem">
                                        <p style="font-size:15px;line-height:24px;margin:0 0 20px;">
                                            Kami telah menerima permintaan reset password untuk akun anda. Berikut ini adalah password sementara akun anda. Silahkan mengganti password tersebut setelah login.
                                        </p>
                                        <p style="font-size:40px;line-height:24px;margin:0 0 16px; text-align: center;">
                                            {$password}
                                        </p>
                                        
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding-top: 0;">
                                <p style="font-weight: bold;">Hormat kami,</p>
                                <p>PasarBumClass</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
HTML;
		}

		public function getVerificationEmailTemplate($verification_link) {
			return <<<HTML
<table style="background:#f9f9f9; color:#373737; font-size:17px; line-height:24px; margin:0; padding:0; width:100%" border="0">
    <tbody>
        <tr>
            <td>
                <table style="background:white; margin-bottom:1rem" align="center" border="0" cellpadding="32">
                    <tbody>
                    	<tr style="background: #009041;">
                    		<td style="padding-top: 10px; padding-bottom: 10px; color: #FFF;">Angkajaya Agro</td>
                    	</tr>
                        <tr>
                            <td style="padding-top: 15px;">
                                <div style="max-width:600px;margin:0 auto">
                                    <div style="background:white;border-radius:0.5rem;margin-bottom:1rem">
                                        <p style="font-size:15px;line-height:24px;margin:0 0 20px;">
                                            Hi, <br />
                                            Terima kasih telah mendaftar di AngkajayaAgro! Mohon untuk melakukan verifikasi alamat email dengan cara membuka tautan di bawah ini.
                                        </p>
                                        <p style="line-height:24px;margin:0 0 16px; text-align: center;">
                                            <a style="text-decoration: none; border: 1px solid #D82922; background: #D82922; color: #FFF; padding: 6px 18px;" href="{$verification_link}">
                                            	Verifikasi
                                            </a>
                                        </p>
                                        
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding-top: 0;">
                                <p style="font-weight: bold;">Hormat kami,</p>
                                <p>Angkajaya Agro</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
HTML;
		}

		public function getInvoiceEmailTemplate($order_id, $order_date, $payment_status, $payment_method, $remarks, $recipient_name, $mobile_phone, $shipping_address, $items, $subtotal, $delivery_fee, $grand_total) {
			return <<<HTML
<table style="background:#f9f9f9; color:#373737; font-size:17px; line-height:24px; margin:0; padding:0; width:100%" border="0">
    <tbody>
        <tr>
            <td>
                <table style="background:white; margin-bottom:1rem" align="center" border="0" cellpadding="32">
                    <tbody>
                        <tr style="background: #009041;">
                            <td style="padding-top: 10px; padding-bottom: 10px; color: #FFF;">Angkajaya Agro</td>
                        </tr>

                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Terima kasih telah berbelanja di toko kami.</td>
                        </tr>
                        <tr>
                            <td style="padding: 0;">
                                <table style="background:white; margin-bottom:1rem" align="center" border="0" cellpadding="32">
                                    <tbody>
                                        <tr>
                                            <td style="padding-top: 5px;padding-bottom: 5px;padding-right: 0;">
                                                <p style="margin: 0;padding: 5px 10px;background-color: #FFF;color: #333;border: 1px solid #ccc;">Pesanan</p>
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;padding-left: 0;">
                                                <p style="margin: 0;padding: 5px 10px;background-color: #FFF;color: #333;border: 1px solid #ccc;">Alamat Pengiriman</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="padding-top: 0;padding-bottom: 5px; vertical-align: top;">
                                                <p style="margin: 0;padding: 5px 10px;font-size: 14px;font-size: 14px;line-height: 1.48em;">
                                                    No. Order: {$order_id}<br>
                                                    Tanggal Order: {$order_date}<br>
                                                    Status Pembayaran: {$payment_status} ({$payment_method})<br>
                                                    Catatan: {$remarks}
                                                </p>
                                            </td>

                                            <td style="padding-top: 0;padding-bottom: 5px;padding-left: 0; vertical-align: top;">
                                                <p style="margin: 0;padding: 5px 10px;font-size: 14px;line-height: 1.48em;">
                                                    Nama: {$recipient_name}<br>
                                                    No. HP: {$mobile_phone}<br>
                                                    Alamat: {$shipping_address}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 0 32px;">
                                <table cellspacing="0" cellpadding="32" border="0" align="center" style="background:white;margin-bottom:1rem;width: 100%;">
                                    <tbody>
                                        <tr style="font-size: 14px;background: #b8d8c7;color: #333;">
                                            <td style="padding: 6px 8px; border: 1px solid #ccc;border-right-width: 0;border-bottom-width: 0;">
                                                No.
                                            </td>
                                            <td style="padding: 6px 8px; border: 1px solid #ccc;border-right-width: 0;border-bottom-width: 0;">
                                                Barang
                                            </td>
                                            <td style="padding: 6px 8px; border: 1px solid #ccc;border-right-width: 0;border-bottom-width: 0;">
                                                Harga
                                            </td>
                                            <td style="padding: 6px 8px; border: 1px solid #ccc;border-right-width: 0;border-bottom-width: 0;">
                                                Jumlah
                                            </td>
                                            <td style="padding: 6px 8px; border: 1px solid #ccc;/* border-right-width: 0; */border-bottom-width: 0;">
                                                Subtotal
                                            </td>
                                        </tr>

                                        {$items}

                                        <tr style="font-size: 14px;">
                                            <td colspan="4" style="padding: 6px 8px; border: 1px solid #ccc;border-right-width: 0;border-bottom-width: 0; text-align: right;">
                                                Subtotal
                                            </td>
                                            <td style="padding: 6px 8px; border: 1px solid #ccc; border-bottom-width: 0; text-align: right;">
                                                {$subtotal}
                                            </td>
                                        </tr>

                                         <tr style="font-size: 14px;">
                                            <td colspan="4" style="padding: 6px 8px; border: 1px solid #ccc;border-right-width: 0;border-bottom-width: 0; text-align: right;">
                                                Ongkos Kirim
                                            </td>
                                            <td style="padding: 6px 8px; border: 1px solid #ccc; border-bottom-width: 0; text-align: right;">
                                                {$delivery_fee}
                                            </td>
                                        </tr>

                                        <tr style="font-size: 14px;">
                                            <td colspan="4" style="padding: 6px 8px; border: 1px solid #ccc;border-right-width: 0;border-bottom-width: 0; text-align: right;">
                                               Total
                                            </td>
                                            <td style="padding: 6px 8px; border: 1px solid #ccc; border-bottom-width: 0; text-align: right;">
                                                {$grand_total}
                                            </td>
                                        </tr>

                                        <tr style="font-size: 14px;">
                                            <td colspan="5" style="padding-top: 5px;padding-bottom: 5px;border-top: 1px solid #ccc;">
                                               &nbsp;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            </tr><tr>
                                <td style="padding-top: 0;">
                                    <p style="font-weight: bold;">Hormat kami,</p>
                                    <p>Angkajaya Agro</p>
                                </td>
                            </tr>
                        
                    </tbody>
                </table>
            </td>
        </tr>           
    </tbody>
</table>
HTML;
		}
		// END OF EMAIL TEMPLATE
	}
