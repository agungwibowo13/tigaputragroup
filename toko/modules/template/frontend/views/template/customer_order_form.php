<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-material form-horizontal" id="app_form" action="#" method="POST">
                        <?= Snl::chtml()->activeTextbox($model, 'customer_order_id', array('class' => 'hidden')) ?>
                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('outlet_machine_id', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeDropdown($model, 'outlet_machine_id', Outlet::getOutletAvailableMachine(Snl::app()->outlet()->outlet_id)) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('name', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'name') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('phone', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'phone') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('address', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextarea($model, 'address', array('rows' => 3)) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('car_brand', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'car_brand') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('car_type', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'car_type') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('car_number', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'car_number') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('start_machine_counter', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'start_machine_counter') ?>
                            </div>
                        </div>   

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('order_status', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeDropdown($model, 'order_status', Snl::getOrderStatus()) ?>
                            </div>
                        </div>  

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('remarks', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextarea($model, 'remarks', array('rows' => 3)) ?>
                            </div>
                        </div>                   

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="submitform('app_form', 'CustomerOrder')"><?= LabelHelper::getLabel('submit') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>