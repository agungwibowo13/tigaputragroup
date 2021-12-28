<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();

?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="app_form" action="#" method="POST">
                        <table class="table table-hover manage-u-table">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Item</th>
                                    <th class="text-center">Pesan</th>
                                    <th width="300">Jumlah Pesanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index=0; foreach ($packages as $key => $package) : $index++; ?>
                                    <tr>
                                        <td class="text-center"><?= $index ?></td>
                                        <td><?= $package->name ?></td>
                                        <td><?= Snl::app()->formatPrice($package->price) ?></td>
                                        <td><?= $package->getPackageItems() ?></td>
                                        <td class="text-center">
                                            <div class="checkbox checkbox-success">
                                                <input id="checkbox<?=$index?>" type="checkbox" value="<?= $package->package_id ?>" class="order-this" name="OutletOrder[package][]">
                                                <label for="checkbox<?=$index?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="OutletOrder[qty][<?= $package->package_id ?>]" min="1" max="100">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#submit-outlet-order').on('click', function() {
            initLoading();
            $('#app_form').submit();

            // var order_summary = '';
            // $('.order-this').each(function() {
            //     if($(this).is(':checked')) {
            //         var qty = $(this).parent().parent().next().children().val();
            //         var product = $(this).data('product-name');
            //         var product_package = $(this).data('package');

            //         if(qty != '' && parseInt(qty) > 0) {
            //             order_summary += "<p style='margin-top: 3px;'>" + qty + " " + product_package + " " + product + "</p>";
            //         }
            //     }
            // });

            // swal({
            //     title: 'Daftar Pesanan Anda',
            //     text: order_summary,
            //     html: true,
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#d33',
            //     confirmButtonText: 'Ya, benar!'
            // }, function(result) {
            //     if(result) {
            //         initLoading();
            //         $('#app_form').submit();
            //     }
            // });
            
        });
    });
</script>