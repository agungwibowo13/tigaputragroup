<?php
	ob_start();
	session_start();
	date_default_timezone_set("Asia/Jakarta");
	
	$module = isset($_GET['module']) ? strtolower($_GET['module']) : 'default';
	$action = isset($_GET['action']) ? strtolower($_GET['action']) : 'cashier';
	$ajax	= isset($_GET['ajax']) ? $_GET['ajax'] : 0;
	$partial = isset($_GET['partial']) ? $_GET['partial'] : 0;
	$GLOBALS['module'] = $module;

	// import all file on base components
	foreach (glob("components/base/*.php") as $filename) {
	    include $filename;
	}

	// import all file on components
	foreach (glob("components/*.php") as $filename) {
	    include $filename;
	}

	// import Curl Class
	foreach (glob("components/Curl/*.php") as $filename) {
	    include $filename;
	}
	
	// import all file on models
	foreach (glob("models/*.php") as $filename) {
	    include $filename;
	}

	foreach (glob("modules/{$GLOBALS['module']}/frontend/controllers/*.php") as $filename) {
	    include $filename;
	}
	
	$classname = ucwords($module).'Controller';
	if (class_exists($classname)) {
		$controller = new $classname;
		
		if($ajax) {
			$controller->$action();
		} elseif($module == 'configuration') {
			echo $controller->$action();
		} else {
			$isLoginPage = FALSE;
			if($module == 'customer' && $action == 'login') {
				$isLoginPage = TRUE;
			}

			include 'themes/frontend/views/layouts/head.php';
			if(!$isLoginPage) {
				$controller->init();
				// include 'themes/frontend/views/layouts/header.php';
				// include 'themes/frontend/views/layouts/menu.php';
				include 'themes/frontend/views/layouts/content.php';
			}
			
			echo $controller->$action();
			include 'themes/frontend/views/layouts/footer.php';
		}
	}
