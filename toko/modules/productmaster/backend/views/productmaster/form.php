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
                        <?= Snl::chtml()->activeTextbox($model, 'product_master_id', array('class' => 'hidden')) ?>
                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('name', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'name') ?>
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
                                <?= Snl::chtml()->activeTextbox($model, 'price') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('rounded_price', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeDropdown($model, 'rounded_price', ['0'=>'No','1'=>'Yes']) ?>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('remarks', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextarea($model, 'remarks') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="submitform('app_form', 'ProductMaster')"><?= LabelHelper::getLabel('submit') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>