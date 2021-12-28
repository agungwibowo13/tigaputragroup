<style type="text/css">
	@media (min-width: 768px) {
		#view_product_block {
		    width: 480px;
		}
	}

</style>
<div class="row" id="view_product_block">
	<div class="col-xs-12">
		<h3 class="m-b-0"><?= $product->name ?></h3>
		<p class="c-font-14"><?= $product->description ?></p>
	</div>

	<div class="col-xs-12">
		<?php if($images != null) : ?>
			<div class="bxslider">
				<?php foreach ($images as $image) : ?>
					<div><img src="<?= Snl::app()->baseUrl().'uploads/product/images/'.$image->image_url; ?>" style="max-height: 390px; max-width: 100%; width: auto;"></div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(function(){
	  $('.bxslider').bxSlider({
	    mode: 'fade',
	    slideWidth: 600
	  });
	});
</script>