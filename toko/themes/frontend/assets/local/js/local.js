function initLoading() {
	if($('#loading_screen').length > 0) {
		$('#loading_screen').remove();
	}

	$('body').append("<div id='loading_screen'><img src='../themes/frontend/assets/images/balls.gif' /></div>");
}

function destroyLoading() {
	if($('#loading_screen').length > 0) {
		$('#loading_screen').remove();
	}	
}

var clearErrorMsg = function(form) {
	$('#'+form).find('.has-error').each(function() {
		$(this).removeClass('has-error');
	});

	$('#'+form).find('.help-block.with-errors').each(function() {
		$(this).html('');
	});
}

var setMsg = function(form, obj, msg) {
	if($('#'+form).find('#'+obj).length > 0) {
		// $parentObj = $('#'+form).find('#'+obj).parent().closest('.form-group');
		$parentObj = $('#'+form).find('#'+obj).closest('.form-group');

		if(!$parentObj.hasClass('has-error')) {
			$parentObj.addClass('has-error');
		}
		$('#'+form).find('#'+obj).next('.help-block.with-errors').html('<ul class="list-unstyled"><li>'+msg+'</li></ul>');
		// $parentObj.find('.help-block.with-errors').html('<ul class="list-unstyled"><li>'+msg+'</li></ul>');
	}
}

// function submitform(form, model) {
// 	initLoading();
// 	clearErrorMsg(form);

// 	var ajaxUrl = baseUrl + model.toLowerCase() + '/validate?ajax=1';
// 	var post = $('#'+form).serializeArray();
// 	$.post(ajaxUrl, {post:post}, function(result) {
// 		result = $.parseJSON(result);
// 		console.log(result);
// 		if(result.valid) {
// 			$('#'+form).submit();
// 		} else {
// 			destroyLoading();
// 			$.each(result.msg, function(key, value) {
// 			  	setMsg(form, model+'_'+key, value);	
// 			});

// 			swal('Oops!', 'Mohon periksa ulang data anda!', 'error');
// 		}
// 	});
// }

function submitform(form, model, controller, action) {
    if(typeof controller == 'undefined') {
        controller = model;
    }

    if(typeof action == 'undefined') {
        action = 'validate';
    }

	initLoading();
	clearErrorMsg(form);
    console.log(model);
	var ajaxUrl = baseUrl + controller.toLowerCase()+'/'+action+'?ajax=1';
	var post = $('#'+form).serializeArray();
	$.post(ajaxUrl, {post:post}, function(result) {
		console.log(result);
		result = $.parseJSON(result);
		if(result.valid) {
			$('#'+form).submit();
		} else {
			destroyLoading();
			$.each(result.msg, function(key, value) {
			  	setMsg(form, model+'_'+key, value);	
			});

			swal("WARNING!", "Terjadi kesalahan input. Mohon periksa ulang data yang anda inputkan.");
		}
	});
}

function submitForgotPassword(form) {
	initLoading();
	$('#'+form).submit();
}

function doFilter(pageUrl) {
	var query = '';
	var availability = '';
	$('.product-filter').each(function() {
		var filter_type = $(this).data('filter-type');
		if(filter_type == 'category') {
			query += '&category='+$(this).val();
		} else if(filter_type == 'availability') {
			if($(this).is(':checked')) {
				availability += $(this).val()+'_';
			}
		}
	});

	query += '&availability='+availability;
	var url = pageUrl + query;
	window.location = url;
}

function calculateTotalOrder() {
	var destination_id = $('.calculate-shipping').val();
	var delivery_service = $('input[name=delivery_service]:checked').val();
	var ajaxUrl = baseUrl + 'order/calculatetotalorder?ajax=1';
	$('.partial-loading').removeClass('hidden');

	$.post(ajaxUrl, {destination_id:destination_id, delivery_service:delivery_service}, function(result) {
		result = $.parseJSON(result);
		$('#total_order').html(result);
		$('.partial-loading').addClass('hidden');
	});
}

$(document).ready(function() {
	$('#sort_product').on('change', function() {
		var pageUrl = $(this).data('page-url');
		var requestOrder = $(this).val();
		window.location = pageUrl+'&order='+requestOrder;
	});

	$('.c-toggle-hide2').each(function () {
		var $checkbox = $(this).find('input.c-check'),
			$speed = $(this).data('animation-speed'),
			$object = $('.' + $(this).data('object-selector'));

		if (typeof $speed === 'undefined') {
			$speed = 'slow';
		}

		$($checkbox).on('change', function () {
			if ($($object).is(':hidden')) {
				$($object).show($speed);
			} else {
				$($object).slideUp($speed);
			}
		});
	});

	$('#submit_order').on('click', function() {
		initLoading();
		var shipping 	= $('#app_form_shipping').serializeArray();
		var billing 	= $('#app_form_billing').serializeArray();
		var order 		= $('#app_form_order').serializeArray();
		var delivery 	= $('#app_form_delivery').serializeArray();
		var ajaxUrl 	= baseUrl + 'order/validateorderdata?ajax=1';
		

		clearErrorMsg('app_form_shipping');
		clearErrorMsg('app_form_billing');
		
		$.post(ajaxUrl, {shipping:shipping, billing:billing, order:order, delivery:delivery}, function(result) {
			destroyLoading();
			result = $.parseJSON(result);
			
			if(result.shipping.valid == false) {
				$.each(result.shipping.msg, function(key, value) {
					console.log(key);
				  	setMsg('app_form_shipping', 'OrderAddress_'+key, value);	
				});
			}

			if(result.billing.valid == false) {
				$.each(result.billing.msg, function(key, value) {
				  	setMsg('app_form_billing', 'OrderAddress_'+key, value);	
				});
			}

			if(result.delivery.valid == false) {
				swal('Oops!', 'Mohon maaf, kami tidak dapat menemukan kurir pengiriman untuk alamat yang anda tuju!', 'error');
			}

			if(result.product_qty.valid == false) {
				var tmp_msg = '';
				$.each(result.product_qty.msg, function(key, value) {
				  	tmp_msg += 'Stok tidak cukup untuk product berikut:\n';
				  	tmp_msg += value.name + ' - Stok tersisa: ' + value.qty;
				  	tmp_msg += '\n';
				});

				if(tmp_msg != '') {
					swal('Oops!', tmp_msg, 'error');
				}
			}

			if(result.shipping.valid == true && result.billing.valid == true && result.delivery.valid == true && result.product_qty.valid == true) {
				
				initLoading();
				ajaxUrl = baseUrl + 'order/saveorderdata?ajax=1';
				$.post(ajaxUrl, {shipping:shipping, billing:billing, order:order, delivery:delivery}, function(result) {
					destroyLoading();
					console.log(result);
					result = $.parseJSON(result);
					var snap_token = result.snap_token;
					snap.pay(snap_token);

					// window.location = baseUrl + result.next_url;
				});
			}
			
		});
	});

	$('body').on('change', '.delivery-service', function() {
		calculateTotalOrder();
	});

	$('.calculate-shipping').on('change', function() {
		var destination_id = $(this).val();
		var ajaxUrl = baseUrl + 'order/calculateshipping?ajax=1';
		$('.partial-loading').removeClass('hidden');
		$.post(ajaxUrl, {destination_id:destination_id}, function(result) {
			result = $.parseJSON(result);
			console.log(result);
			if(result == null) {
				$('#shipping_block').find('.available-shipping-service').html('Kota yang anda pilih tidak terdaftar.');
			} else {
				var htmlElement = '';
				$.each(result, function( index, value ) {
					htmlElement += '<div class="c-radio">';
						htmlElement += '<input type="radio" value="'+value.service+'" id="radio'+index+'" class="c-radio delivery-service" name="delivery_service" checked="">';
						htmlElement += '<label for="radio'+index+'">';
							htmlElement += '<span class="inc"></span>';
							htmlElement += '<span class="check"></span>';
							htmlElement += '<span class="box"></span>';
							htmlElement += value.service + ' ('+value.etd+' hari)';
						htmlElement += '</label>';
						htmlElement += '<p class="c-shipping-price c-font-bold c-font-20">'+value.formated_cost+'</p>';
					htmlElement += '</div>';
				});

				$('#shipping_block').find('.available-shipping-service').html(htmlElement);
			}

			$('.partial-loading').addClass('hidden');
			calculateTotalOrder();
		});
	});

	if($('.calculate-shipping').length > 0) {
		$('.calculate-shipping').change();
	}
});