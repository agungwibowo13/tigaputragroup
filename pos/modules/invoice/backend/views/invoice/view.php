<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed" id="invoice-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Jumlah</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($details as $detail) : ?>
                                <?php $product = ProductMaster::model()->findByPk($detail->product_master_id); ?>
                                <tr>
                                    <td><?= ucwords($product->name) ?></td>
                                    <td class="text-right"><?= Snl::app()->formatPrice($detail->price) ?></td>
                                    <td class="text-right"><?= $detail->qty ?></td>
                                    <td class="text-right">
                                        <?php
                                            if($product->rounded_price) {
                                                echo Snl::app()->formatPrice($product->calculateRoundedPrice($detail->price * $detail->qty));
                                            } else {
                                                echo Snl::app()->formatPrice($detail->price * $detail->qty);
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>