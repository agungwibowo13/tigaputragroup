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
                        <?= Snl::chtml()->activeTextbox($model, 'setting_id', array('class' => 'hidden')) ?>
                        
                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('name', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'name') ?>
                            </div>
                        </div>

                        <?php if($model->input_type == 'file') : ?>
                            <div class="form-group">
                                <label class="col-md-12"><?= $model->getLabel('value', TRUE); ?></label>
                                <div class="col-md-12">
                                    <input type="file" name="Setting[value]" id="Setting_value" class="form-control" />
                                    <span class="help-block"><small><?= $model->remarks ?></small></span>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('value', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'value') ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary"><?= LabelHelper::getLabel('submit') ?></button>
                            </div>
                        </div>

                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>