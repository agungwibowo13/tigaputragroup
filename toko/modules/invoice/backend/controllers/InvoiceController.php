<?php
	class InvoiceController extends BackendController {
		public function __construct() {
			$this->views = 'modules/invoice/backend/views/invoice/';
		}

		public function index() {
			$this->page_title = 'Daftar Penjualan';
			
			return $this->render('index', array(
				'toolbar' => $this->toolbar(),
			));
		}

		public function view() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Invoice::model()->findByPk($id);

			$details = InvoiceDetail::model()->findAll(array(
				'condition' => 'invoice_id = :invoice_id',
				'params' => [':invoice_id' => $model->invoice_id]
			));
			
			$this->page_title = Snl::app()->generateInvoiceNo($model->invoice_id).' - '.Snl::app()->formatPrice($model->total);
			$this->toolbarElement = '<a href="'.Snl::app()->baseUrl().'admin/invoice/index" class="btn btn-default btn-sm pull-right m-l-20"><i class="glyphicon glyphicon-remove"></i></a>';
			return $this->render('view', array(
				'toolbar' 	=> $this->toolbar(),
				'model'	  	=> $model,
				'details'	=> $details,
			));
		}

		public function delete() {
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			$model = Invoice::model()->findByPk($id);


			if($model !== NULL) {
				$delete_detail = InvoiceDetail::model()->deleteByAttribute(array(
					'condition' => 'invoice_id = :invoice_id',
					'params'	=> [':invoice_id' => $model->invoice_id]
				));

				if($model->delete()) {
					Snl::app()->setFlashMessage($model->name.' berhasil dihapus.', 'success');
				} else {
					Snl::app()->setFlashMessage('Internal server error.', 'danger');
				}
			}

			$this->redirect('admin/invoice/index');
		}

		// All ajax function
		public function search() {
			$gets = isset($_GET) ? $_GET : array();
			
			$data = array();
			$pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 1;
			$pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;
			$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'invoice_id';
			$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'desc';
			$offset = ($pageIndex - 1) * $pageSize;
			$search_query = $this->parseSearchQuery(new Invoice, $gets);
			if(!empty($search_query)) {
				$search_query = ' AND '.$search_query;
			}

			$total_search_query = $search_query." ORDER BY ".$sortField." ".$sortOrder;
			$itemsCount = count(Invoice::model()->findAll(array('condition' => '1 = 1'.$search_query)));

			$search_query .= " ORDER BY ".$sortField." ".$sortOrder." LIMIT ".$pageSize." OFFSET ".$offset;
			$invoices = Invoice::model()->findAll(array('condition' => '1 = 1'.$search_query));

			if($invoices !== NULL) {
				foreach ($invoices as $invoice) {
					$data[] = [
						'invoice_id' => $invoice->invoice_id,
						'invoice_number' => Snl::app()->generateInvoiceNo($invoice->invoice_id),
						'total' => Snl::app()->formatPrice($invoice->total),
						'profit' => Snl::app()->formatPrice($invoice->profit),
						'invoice_date' => Snl::app()->dateTimeFormat($invoice->invoice_date)
					];
				}
			}

			$json = array(
				"itemsCount" => $itemsCount,
				"data" => $data,
			);
			echo json_encode($json);
		}
	}
