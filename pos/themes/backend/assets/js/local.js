var initLoading = function() {
	$('.preloader').removeClass('hidden');
}

var destroyLoading = function() {
	$('.preloader').addClass('hidden');
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
		$parentObj = $('#'+form).find('#'+obj).parent().closest('.form-group');

		if(!$parentObj.hasClass('has-error')) {
			$parentObj.addClass('has-error');
		}

		$parentObj.find('.help-block.with-errors').html('<ul class="list-unstyled"><li>'+msg+'</li></ul>');
	}
}

function submitform(form, model) {
	initLoading();
	clearErrorMsg(form);

	var ajaxUrl = baseUrl + 'admin/'+model.toLowerCase()+'/validate?ajax=1';
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

function updateOrderStatus(order_id, status) {
    initLoading();
    
    var ajaxUrl = baseUrl + 'admin/order/updateorderstatus?ajax=1';
    $.post(ajaxUrl, {order_id:order_id, status:status}, function(result) {
        location.reload();
    });
}

$(document).ready(function() {
	$('body').on('click', '.order-on-delivery', function() {
        if(!$(this).hasClass('disabled')) {
            var order_id = $(this).data('order-id');

            swal({   
                title: "Apakah anda yakin untuk mengubah status order ini menjadi \"Delivery (Dalam Pengiriman)\"?",   
                text: "Aksi ini tidak dapat diulang / anda tidak dapat mengubahnya kembali!",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ya, saya yakin!", 
                cancelButtonText: "Batal",
                closeOnConfirm: false 
            }, function(isConfirm){   
                if(isConfirm) {
                    swal({title:'', timer:1});
                    updateOrderStatus(order_id, 'Delivery');
                }
            });
        }
    });

    $('body').on('click', '.order-complete', function() {
        if(!$(this).hasClass('disabled')) {
            var order_id = $(this).data('order-id');

            swal({   
                title: "Apakah anda yakin order ini telah selesai / complete?",   
                text: "Aksi ini tidak dapat diulang / anda tidak dapat mengubahnya kembali!",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ya, saya yakin!", 
                cancelButtonText: "Batal",
                closeOnConfirm: false 
            }, function(isConfirm){   
                if(isConfirm) {
                    swal({title:'', timer:1});
                    updateOrderStatus(order_id, 'Complete');
                }
            });
        }
    });
});