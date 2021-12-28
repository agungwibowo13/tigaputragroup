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
                        <?= Snl::chtml()->activeTextbox($model, 'outlet_id', array('class' => 'hidden')) ?>
                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('name', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'name') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('address', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextarea($model, 'address', array('rows' => 3)) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('phone', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'phone') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $outlet_machine->getLabel('machine_ids', TRUE); ?></label>
                            <div class="col-md-12">
                                <select class="select2 select2-multiple" multiple="multiple" placeholder="Pilih Mesin" name="OutletMachine[machine_ids][]">
                                <?php
                                    if(count($related_machine) > 0) {
                                        foreach ($related_machine as $id => $machine_no) {
                                            echo "<option value='".$id."' selected>".$machine_no."</option>";
                                        }
                                    }

                                    foreach(Machine::model()->getAvailableMachine() as $id => $machine_no) {
                                        echo "<option value='".$id."'>".$machine_no."</option>";
                                    }  
                                ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('username', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activeTextbox($model, 'username') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('password', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activePassword($model, 'password') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?= $model->getLabel('password_repeat', TRUE); ?></label>
                            <div class="col-md-12">
                                <?= Snl::chtml()->activePassword($model, 'password_repeat') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="submitform('app_form', 'Outlet')"><?= LabelHelper::getLabel('submit') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>