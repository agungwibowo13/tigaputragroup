<style type="text/css">
	.row.fancybox-content {
		min-width: 680px;
		padding-left: 0;
	    padding-top: 0;
	    padding-bottom: 0;
	}

	.row.fancybox-content table {
		margin-bottom: 0;
	}

	.fancybox-content #invoice_title {
		font-size: 48px;
    	text-align: right;
	}

	.fancybox-content #company_name {
		color: #fff;
    	margin-bottom: 0;
	}

	.fancybox-content #company_slogan {
		font-size: 12px;
    	color: #999;
	}

	.left-block {
		background-color: #E9EBE8;
		border-right: 2px solid #373B46;
		padding-left: 40px;
	}

	.company-info {
		background-color: #373B46;
		color: #FFF;
		padding-left: 40px;
	}
</style>

<div class="row">
	<div class="row">
		<div class="col-xs-4 left-block">
			<p>&nbsp;</p>
		</div>
		<div class="col-xs-8">
			<p>&nbsp;</p>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6 company-info">
			<h4 id="company_name"><?= Setting::getSetting('company-name') ?></H4>
			<p id="company_slogan"><?= Setting::getSetting('company-quote') ?></p>
			<p>
				<?= Setting::getSetting('company-address') ?>
			</p>
			<p><?= Setting::getSetting('company-phone') ?></p>
		</div>
		<div class="col-xs-6">
			<h1 id="invoice_title">INVOICE</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-4 left-block p-t-10">
			<h4 class="m-b-0">Bill to:</h4>
			<p class="m-b-0"><b><?= ucwords(strtolower($outlet->name)) ?></b></p>
			<p>
				<?= $outlet->address ?>
				<br />
				<?= $outlet->phone ?>
				<br />
			</p>

			<br />
			<p class="m-b-0"><b>Order#</b>&nbsp;<?= $order->order_printed_id ?></p>
			<p><b>Date</b>&nbsp;<?= Snl::app()->dateFormat($order->order_date) ?></p>

			<br /><br /><br /><br />
		</div>

		<div class="col-xs-8 p-t-10">
			<table class="table table-stripped">
				<thead>
					<tr>
						<th class="text-right">QTY</th>
						<th>Deskripsi Produk</th>
						<th class="text-right">Harga/Paket</th>
						<th class="text-right">Total</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td class="text-right"><?= $order->qty ?></td>
						<td><?= $package->name ?></td>
						<td class="text-right"><?= Snl::app()->formatPrice($order->package_price) ?></td>
						<td class="text-right"><?= Snl::app()->formatPrice($order->package_price * $order->qty) ?></td>
					</tr>
				</tbody>

				<tfoot>
					<tr style="background-color: #F9F9F9;">
						<th colspan="3" class="text-right">TOTAL</th>
						<th class="text-right"><?= Snl::app()->formatPrice($order->package_price * $order->qty) ?></th>
					</tr>
				</tfoot>
			</table>

			<?php if($order->payment_status == 'Paid') : ?>
				<img src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/images/logo-paid.jpg" style="max-width: 200px;" />
			<?php endif; ?>
		</div>
	</div>
</div>