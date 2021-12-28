<?php
	class DashboardController extends BackendController {
		public function __construct() {
			$this->views = 'modules/dashboard/backend/views/dashboard/';
		}

		public function index() {
			$this->page_title = 'Dashboard';
			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		// All ajax function
		
	}