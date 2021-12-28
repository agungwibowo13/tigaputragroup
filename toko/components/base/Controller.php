<?php
	class Controller {
		public $views;
		public $page_title;
		public $page_subtitle;
		public $crumbs = array();

		public function render($file, $variables = array(), $fullpath = FALSE) {
	        extract($variables);
	        if(!$fullpath) {
	        	$file = $this->views.$file.'.php';
	        }
	        
	        include $file;
	        $renderedView = ob_get_clean();

	        return $renderedView;
	    }

	    public function redirect($url, $fullurl = FALSE) {
	    	if(!$fullurl) {
	    		$url = Snl::app()->baseUrl().$url;
	    	}

			header('Location: '.$url);
			exit();
		}

		public function createUrl($pageUrl = '') {
			return Snl::app()->baseUrl().$pageUrl;
		}

		public static function app() {
			return new Controller;
		}
	}