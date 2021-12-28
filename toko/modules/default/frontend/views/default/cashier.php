<?php
	echo Snl::app()->getFlashMessage();
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<form class="form-material form-horizontal" id="app_form" action="#" method="POST" enctype="multipart/form-data">
	            <div class="form-group">
	                <label class="col-md-2">Tanggal</label>
	                <div class="col-md-4">
	                    <input type="text" class="form-control" id="invoice_date" value="<?= date('d M Y H:i:s') ?>" disabled="disabled" />
	                </div>
	            </div>
	            <div class="form-group">
	                <div class="col-md-6">
	                    <button type="button" class="btn btn-primary visible-xs" id="btn_search"><i class="fa fa-fw fa-search"></i>Cari</button>
	                </div>
	            </div>
	        </form>
		</div>

		<div class="col-md-6">
			<!-- <button type="button" class="btn btn-danger pull-right" id="btn_save"><i class="fa fa-fw fa-save"></i>Simpan</button> -->
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table table-condensed" id="invoice-table">
			 	<thead>
			 		<tr>
			 			<th>Nama Barang</th>
			 			<th class="text-right">Harga</th>
			 			<th class="text-right">Jumlah</th>
			 			<th class="text-right">Subtotal</th>
			 			<th>&nbsp;</th>
			 		</tr>
			 	</thead>

			 	<tbody></tbody>

			 	<tfoot>
			 		<tr>
			 			<th class="text-right" colspan="3">&nbsp;</th>
			 			<th class="text-right">
			 				<button type="button" class="btn btn-danger pull-right" id="btn_save"><i class="fa fa-fw fa-print"></i>Selesai</button>
			 			</th>
			 		</tr>
			 	</tfoot>
			 	<tfoot>
			 		<tr>
			 			<th class="text-right" colspan="3">Total</th>
			 			<th class="text-right" id="invoice-table-total"></th>
			 		</tr>
			 	</tfoot>

			 	
			</table>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<input type="text" id="search_product" />
      		</div>
    	</div>
  	</div>
</div>

<script>
   	function repoFormatResult(repo) {
   		var markup = '<div class="row">';
   		markup += '<div class="col-xs-8">'+repo.name+'</div>';
   		markup += '<div class="col-xs-4 text-right">'+repo.price+'</div>';
   		markup += '</div>';
    	return markup;
   	}

   	function repoFormatSelection(repo) {
      	return repo.name + " - " + repo.price;
   	}

   	function calculateInvoiceTotal() {
   		var total = 0;
   		$('#invoice-table').find('tbody tr').each(function() {
   			var subtotal = $(this).find('td:eq(3)').data('subtotal');
   			total += parseInt(subtotal);
   		});


   		var ajaxUrl = baseUrl + "default/formatprice?ajax=1";
		$.post(ajaxUrl, {total:total}, function(result) {
			result = $.parseJSON(result);
			$('#invoice-table-total').html(result.price);
		});
   	}

   	function roundedPrice(price) {
   		price = price/1000;
	    var n = Math.floor(price);
	    var m = price - n;

	    if(m > 0) {
		    if(m <= 0.5) {
		        m = 0.5;
		    } else {
		        m = 1;
		    }
	    }

	    price = (n + m) * 1000;
	    return price;
   	}

   	function recalculate() {
   		var total = 0;
   		var ajaxUrl = baseUrl + "default/formatprice?ajax=1";

   		$('#invoice-table').find('tbody tr').each(function() {
   			var obj = $(this);
   			var rounded_price = $(this).find('td:eq(1)').data('rounded-price');
   			var price = $(this).find('td:eq(1)').data('price');
   			var qty = $(this).find('td:eq(2)').find('input').val();
   			var subtotal = parseInt(price) * qty;

   			if(rounded_price == 1) {
   				subtotal = roundedPrice(subtotal);
   			}

   			total += subtotal;

			$.post(ajaxUrl, {total:subtotal}, function(result) {
				result = $.parseJSON(result);
				obj.find('td:eq(3)').html(result.price);
				obj.find('td:eq(3)').data('subtotal', subtotal);
			});
   		});


   		$.post(ajaxUrl, {total:total}, function(result) {
			result = $.parseJSON(result);
			$('#invoice-table-total').html(result.price);
		});
   	}

   	function deleteRow(product_id) {
   		$('#row'+product_id).remove();
   		recalculate();
   	}

   	$('body').on('change', '#invoice-table > tbody > tr > td > .variative-input', function() {
   		console.log('Re-calculate');
   		recalculate();
	});

	
	$('body').on('keypress', '#invoice-table > tbody > tr > td > .variative-input', function(e) {
		var keyCode = e.which;
	    /*
	    8 - (backspace)
	    32 - (space)
	    48-57 - (0-9)Numbers
	    */
	    if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
	    	return false;
	    }
	});

</script>


<script type="text/javascript">
	$('body').on('keyup', function(e) {
		if(e.keyCode == 27) {
			setTimeout(function(){ 
				$('#myModal').modal('toggle');

				setTimeout(function(){ 
					if($('#myModal').hasClass('in')) {
						$("#search_product").select2('open');

						setTimeout(function(){ 
							$("#search_product").select2('focus');
						}, 200);
					}
				}, 300);
			}, 100);
		}
	});

	$('#btn_search').on('click', function() {
		setTimeout(function(){ 
			$('#myModal').modal('toggle');

			setTimeout(function(){ 
				if($('#myModal').hasClass('in')) {
					$("#search_product").select2('open');

					setTimeout(function(){ 
						$("#search_product").select2('focus');
					}, 200);
				}
			}, 300);
		}, 100);
	});

	$(document).ready(function() {
		$("#search_product").select2({
		    placeholder: 'Ketik nama barang',
			minimumInputLength: 2,
			allowClear: true,
			width: "100%",
		    ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
		        url: baseUrl + "default/searchproduct?ajax=1",
		        dataType: 'json',
		    	type: 'GET',
		        quietMillis: 250,
		        data: function (term) {
		            return {
		                q: term, // search term
		            };
		        },
		        results: function (data) { // parse the results into the format expected by Select2.
		            // since we are using custom formatting functions we do not need to alter the remote JSON data
		            return { results: data.items };
		        },
		        // cache: true
		    },
		  	formatResult: repoFormatResult, // omitted for brevity, see the source of this page
		    formatSelection: repoFormatSelection,  // omitted for brevity, see the source of this page
		    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
		    containerCssClass: "search-product-cashier",
		    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
		});

		$("#search_product").on('change', function() {
			if($(this).val() != '') {
				$('#myModal').modal('hide');
				var product_id = $(this).val();
				var qty = prompt("Jumlah Barang", "1");
				
				if(qty != null && qty > 0) {
					var price_correction = prompt("Koreksi Harga", "0");

					var ajaxUrl = baseUrl + "default/getproductname?ajax=1";
					$.post(ajaxUrl, {product_id:product_id, qty:qty, price_correction:price_correction}, function(result) {
						result = $.parseJSON(result);
						var tableRow = '<tr id="row'+result.product_id+'">';
						tableRow += '<td data-product-id="'+result.product_id+'">' + result.name_formatted + '</td>';
						tableRow += '<td class="text-right" data-rounded-price="'+result.rounded_price+'" data-original-price="'+result.original_price+'" data-price="'+result.price+'">' + result.price_formatted + '</td>';
						tableRow += '<td class="text-right"><input type="text" class="variative-input" style="width: 75px;" value="'+qty+'" /></td>';
						tableRow += '<td class="text-right" data-subtotal="'+result.subtotal+'">' + result.subtotal_formatted + '</td>';
						tableRow += '<td style="text-align: center; cursor: pointer; color: red;"><i class="fa fa-trash delete-row" data-product-id="'+result.product_id+'" onclick="deleteRow('+result.product_id+')"></i></td>';
						tableRow += '</tr>';

						$('#invoice-table').find('tbody').append(tableRow);
						$("#search_product").select2('val', '');
						calculateInvoiceTotal();

						setTimeout(function(){ 
							$('#invoice-table').find('tbody tr:last td:eq(2)').find('input').focus();
						}, 100);
					});
				}
			}
		});

		$('#btn_save').bind('click', function() {
			initLoading();
			var data = [];
			var total = 0;
			var ajaxUrl = baseUrl + "default/submitinvoice?ajax=1";

			$('#invoice-table').find('tbody tr').each(function() {
	   			var product_id = $(this).find('td:eq(0)').data('product-id');
	   			var original_price = $(this).find('td:eq(1)').data('original-price');
	   			var price = $(this).find('td:eq(1)').data('price');
	   			var rounded_price = $(this).find('td:eq(1)').data('rounded-price');
	   			var qty = $(this).find('td:eq(2)').find('input').val();
	   			var subtotal = parseInt(price) * qty;
	   			if(rounded_price == 1) {
	   				subtotal = roundedPrice(subtotal);
	   			}

	   			total += subtotal;

	   			var data_detail = {};
	   			data_detail.product_id = product_id;
	   			data_detail.subtotal = subtotal;
	   			data_detail.price = price;
	   			data_detail.original_price = original_price;
	   			data_detail.qty = qty;
				data.push(data_detail);
	   		});

			if(data.length > 0) {
				var post = JSON.stringify(data);
				$.post(ajaxUrl, {post:post, total:total}, function(result) {
					console.log(result);
					if(result) {
						location.reload();
					} else {
						alert('Internal error!');
					}
				});
	   		} else {
	   			destroyLoading();
	   			alert('Nota kosong!');
	   		}
		});
	});
</script>