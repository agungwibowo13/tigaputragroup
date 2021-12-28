<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-material form-horizontal" id="app_form" action="#" method="POST" enctype="multipart/form-data">
                        <?= Snl::chtml()->activeTextbox($model, 'category_id', array('class' => 'hidden')) ?>
                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('name', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'name') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('status', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeDropdown($model, 'status', Snl::getVendorStatus()) ?>
                            </div>
                        </div>

                        <?php if(!$model->isNewRecord && $model->image_url != '') : ?>
                        <div class="form-group" id="image-container">
                            <label class="col-md-12"><?= $model->getLabel('current_image', TRUE); ?></label>
                            <div class="col-md-12">
                                <div class="image-thumbnail-box">
                                    <i class="fa fa-remove top-right-delete-button" data-category-id="<?= $model->category_id ?>"></i>
                                    <img src="<?= $model->getImage() ?>" class="image-thumbnails" />
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('image_url', TRUE); ?></label>
                            <div class="col-md-12">
                                <input type="file" name="Category[image_url]" id="Category_image_url" class="form-control" />
                                <span class="help-block"><small>Ukuran. 1920x1080<br />Max. 2 MB</small></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="submitform('app_form', 'Category')"><?= LabelHelper::getLabel('submit') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '.top-right-delete-button', function() {
            swal({
                title: "Apakah anda yakin untuk menghapus gambar ini?", 
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.value) {
                    initLoading();
                    var category_id = $(this).data('category-id');
                    var ajaxUrl = baseUrl + 'admin/category/deleteimage?ajax=1';

                    $.post(ajaxUrl, {category_id:category_id}, function(result) {
                        destroyLoading();
                        if(result) {
                            $('#image-container').remove();
                        } else {
                            swal("Error!", "Internal server error.");
                        }
                    });
                }
            });
        });
    });
</script>