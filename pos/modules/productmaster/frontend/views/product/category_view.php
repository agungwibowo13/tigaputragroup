<?php
    echo $this->render(Snl::app()->rootDirectory().'themes/frontend/views/general/breadcrumbs-thin.php', array(), TRUE);
?>

<div class="container">
    <?php if(count($products) > 0) : ?>
        <div class="c-content-box c-size-md row">
            <!-- <div id="grid-container" class="cbp cbp-l-grid-agency"> -->
               <?php
					if($products != NULL) {
						foreach ($products as $product) {
							$merchant = Merchant::model()->findByPk($product->merchant_id);
							echo $this->render('partial/_product_grid', array(
								'data' => $product,
								'merchant' => $merchant
							));
						}
					}
				?>
            <!-- </div> -->
        </div>
    <?php else : ?>
        <p><i>Tidak ada produk.</i></p>
    <?php endif; ?>
</div>