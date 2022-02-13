<?php
	ob_start();
	session_start();
	date_default_timezone_set("Asia/Jakarta");
	
	$module = isset($_GET['module']) ? strtolower($_GET['module']) : 'user';
	$action = isset($_GET['action']) ? strtolower($_GET['action']) : 'login';
	$ajax	= isset($_GET['ajax']) ? $_GET['ajax'] : 0;
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

	foreach (glob("modules/{$GLOBALS['module']}/backend/controllers/*.php") as $filename) {
	    include $filename;
	}	

	// function __autoload($classname) {
	//     $filename = "modules/{$GLOBALS['module']}/backend/controllers/". $classname .".php";
	//     include_once($filename);
	// }
	
	$classname = ucwords($module).'Controller';
	if (class_exists($classname)) {
		$controller = new $classname;
		
		if($ajax) {
			$controller->$action();
		} else {
			$isLoginPage = FALSE;
			if($module == 'user' && $action == 'login') {
			    $isLoginPage = TRUE;
			}

			include 'themes/backend/views/layouts/head.php';
			if(!$isLoginPage) {
				$controller->init();
				include 'themes/backend/views/layouts/top_menu.php';
				include 'themes/backend/views/layouts/left_menu.php';
				include 'themes/backend/views/layouts/main.php';
			}
			
			echo $controller->$action();
			include 'themes/backend/views/layouts/footer.php';
		}
	}

	