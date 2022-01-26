<?php
	ob_start();
	session_start();
	date_default_timezone_set("Asia/Jakarta");
	
	$module = isset($_GET['module']) ? strtolower($_GET['module']) : 'customer';
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

	foreach (glob("modules/{$GLOBALS['module']}/api/controllers/*.php") as $filename) {
	    include $filename;
	}	
	
	$classname = ucwords($module).'Controller';
	if (class_exists($classname)) {
		$controller = new $classname;
		$controller->action = $action;
		$controller->init();
		$controller->$action();
	}