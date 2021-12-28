var addToCart = function() {
	var _renderMessage = function(message, type) {
		swal('Keranjang', message, type);
	}

	var _renderMiniCart = function() {
		var ajaxUrl = baseUrl + 'category/renderminicart?ajax=1';
		$.post(ajaxUrl, {}, function(cart) {
			cart = $.parseJSON(cart);
			$('#mini_cart').find('.cart-total-items').html(cart.total_items + ' produk');
			$('#mini_cart').find('.cart-total-price').html(cart.total_price);
			$('#mini_cart').find('.c-cart-menu-items').html(cart.items);
			$('#cart_total_items').html(cart.total_items);
			$('#cart_total_items_xs').html(cart.total_items);
		});
	}

	var _renderAddToCartQtyMode = function(product_id, qty, container) {
		if(typeof $(container).data('page') !== 'undefined' && $(container).data('page') == 'product_page') {
			$(container).removeClass('c-margin-t-20');
			$(container).removeClass('col-sm-12');
			$(container).addClass('col-sm-4');
			$(container).data('page', '');

			var label = '';
			label += '<div class="col-sm-12 col-xs-12">';
				label += '<div class="c-input-group c-spinner">';
					label += '<p class="c-product-meta-label c-product-margin-2 c-font-uppercase c-font-bold">';
						label += 'Add to Cart';
					label += '</p>';
				label += '</div>';
			label += '</div>';

			$(container).parent().prepend(label);
		}

		var htmlElement = '';
		htmlElement += '<div class="input-group c-square btn-add-to-cart">';
			htmlElement += '<span class="input-group-btn">';
	    		htmlElement += '<button class="btn btn-default add-to-cart-minus adjust-qty" type="button" data-action="minus" data-product-id="'+product_id+'">-</button>';
	      	htmlElement += '</span>';
	      	htmlElement += '<input type="text" class="form-control c-square c-theme cart-quantity" value="'+qty+'">';
	    	htmlElement += '<span class="input-group-btn">';
	    		htmlElement += '<button class="btn btn-default add-to-cart-plus adjust-qty" type="button" data-action="plus" data-product-id="'+product_id+'">+</button>';
	      	htmlElement += '</span>';
	    htmlElement += '</div>';

	    $(container).html(htmlElement);
	}

	var _addItemToCart = function(product_id, container, action) {
		var ajaxUrl = baseUrl + 'category/addtocart?ajax=1';
		var reload_page = $(container).data('reload-page');

		$.ajax({
			url: ajaxUrl,
			data: { product_id: product_id, action: action },
			method: "post",
			dataType: "json",
			beforeSend: function(xhr) {
			    initLoading();
			},
			success: function (data) {
				_renderMiniCart();
				destroyLoading();
				if(data.success) {
					if(typeof reload_page !== "undefined" && reload_page == "1") {
						location.reload();
					} else {
						if(data.qty > 0) {
							if(action == 'plus') {
								_renderMessage(data.message, 'success');
							}
							
							_renderAddToCartQtyMode(product_id, data.qty, container);
						} else {
							location.reload();
						}
					}
				} else {
					_renderMessage(data.message, 'error');
				}
			},
			error: function(data) {
				console.log(data);
			}
		});
	}

	var _removeItemFromCart = function(product_id) {
		var ajaxUrl = baseUrl + 'category/removefromcart?ajax=1';
		$.ajax({
			url: ajaxUrl,
			data: { product_id: product_id },
			method: "post",
			dataType: "json",
			beforeSend: function(xhr) {
			    initLoading();
			},
			success: function (data) {
				_renderMiniCart();
				destroyLoading();
				if(data.success) {
					location.reload();
				} else {
					_renderMessage(data.message, 'error');
				}
			},
		});
	}

	return {
		init: function () {
			$('body').on('click', '.add-to-cart', function (e) {
				var product_id 		= $(this).data('product-id');
				var cartContainer 	= $(this).closest('.add-to-cart-block');
				_addItemToCart(product_id, cartContainer, 'plus');
			});

			$('body').on('click', '.remove-item-from-cart', function (e) {
				var product_id = $(this).data('product-id');
				_removeItemFromCart(product_id);
			});

			$('body').on('click', '.btn-add-to-cart .adjust-qty', function() {
				var action 		= $(this).data('action');
				var product_id 	= $(this).data('product-id');
				_addItemToCart(product_id, $(this).closest('.add-to-cart-block'), action);
			});
		}
	};
}();

$(document).ready(function() {
	addToCart.init();
});