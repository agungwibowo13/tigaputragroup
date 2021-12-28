<?php
	class ReportController extends BackendController {
		public function __construct() {
			$this->views = 'modules/report/backend/views/report/';
		}

		public function index() {
			$this->page_title = 'Laporan Penjualan';
			$total = 0;
			$profit = 0;
			$persentase = 0;
			$selected_date = date('d M Y');
			$selected_mode = null;

			if(isset($_GET['Report'])) {
				$date = date('Y-m-d', strtotime($_GET['Report']['date']));
				$mode = $_GET['Report']['mode'];
				$selected_date = date('d M Y', strtotime($date));
				$selected_mode = $mode;

				$model = new SnlActiveRecord();
				$model->openConnection();
				if($mode == 'y') {
					$date = date('Y', strtotime($date));
					$query = $model->conn->prepare("SELECT SUM(total) as total, SUM(profit) as profit FROM tbl_invoice WHERE YEAR(invoice_date) = :d");
				} else if($mode == 'm') {
					$date = date('m', strtotime($date));
					$query = $model->conn->prepare("SELECT SUM(total) as total, SUM(profit) as profit FROM tbl_invoice WHERE MONTH(invoice_date) = :d");
				} else {
					$query = $model->conn->prepare("SELECT SUM(total) as total, SUM(profit) as profit FROM tbl_invoice WHERE DATE(invoice_date) = :d");
				}
				
				$query->bindParam(':d', $date);
				$query->execute();
				$model->closeConnection();

				if($query->rowCount() > 0) {
					$result = $query->fetchAll();
					$total = $result[0]['total'];
					$profit = $result[0]['profit'];
					$persentase = round($profit / $total * 100, 2);
				}
			}

			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
				'total'   => Snl::app()->formatPrice($total),
				'profit'  => Snl::app()->formatPrice($profit),
				'persentase' => $persentase.'%',
				'selected_date' => $selected_date,
				'selected_mode' => $selected_mode,
			));
		}
	}
