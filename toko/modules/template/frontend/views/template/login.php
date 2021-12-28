<section id="wrapper" class="login-register">
    <div class="login-box frontend-login">
        <div class="row">
            <div class="col-md-6 login-left-block">
                <h3>OUTLET LOGIN</h3>
                <h5>Diesel Utama</h5>
            </div>
            <div class="col-md-6 col-xs-12 login-right-block">
                <form class="form-horizontal" id="loginform" action="#" method="POST">
                    
                    <div class="form-group m-t-40">
                        <div class="col-xs-12">
                            <?= Snl::app()->getFlashMessage() ?>
                            <?= Snl::chtml()->activeTextbox($model, 'username') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <?= Snl::chtml()->activePassword($model, 'password') ?>
                        </div>
                    </div>

                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-green btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">
                                <i class="fa fa-fw fa-unlock"></i> <?= LabelHelper::getLabel('login') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>