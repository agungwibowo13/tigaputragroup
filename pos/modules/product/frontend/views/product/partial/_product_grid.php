<div class="col-md-3 col-sm-6 c-margin-b-20">
	<div class="c-content-product-2 c-bg-white c-border">
		<div class="c-content-overlay">
			<div class="c-bg-img-center c-overlay-object c-product-image" data-height="height" style="height: 230px;">
				<a href="<?= Snl::app()->baseUrl() ?>merchant/view?name=<?= $data->getMerchantUrl() ?>">
					<img src="<?= $data->getImage() ?>" class="product-image" />
				</a>
			</div>
		</div>
		<div class="c-info">
			<p class="c-title c-font-20 m-b-0">
				<?= ucwords(strtolower($data->name)) ?>
			</p>

			<p class="c-price c-font-14 c-font-slim">
				<?= $data->description ?>
			</p>

			<!-- <p class="c-price c-font-12 c-font-slim">
				<span style="color: red;">Informasi Penjual:</span> <?= $merchant->name ?> (<?= $merchant->address ?> | <?= $merchant->phone ?> | <?= $merchant->email ?>)
			</p> -->
		</div>
		
	</div>
</div>