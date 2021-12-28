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
                        <?= Snl::chtml()->activeTextbox($model, 'product_id', array('class' => 'hidden')) ?>
                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('category', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'category') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('name', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'name') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('uom', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'uom') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('hpp', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'hpp') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('price', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextarea($model, 'price', array('class' => 'textarea_editor form-control', 'rows' => 6, 'style' => 'background-image: none; border: 1px solid #eee; padding: 7px 12px;')) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="submitform('app_form', 'Product')"><?= LabelHelper::getLabel('submit') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>