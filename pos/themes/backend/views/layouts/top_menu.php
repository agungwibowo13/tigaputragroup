<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<?php
    $unverified_vendor = [];
?>
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo hidden" href="javascript:;">
                <!-- Logo icon image, you can use font-icon also -->
                <b>
                    <!--This is dark logo icon-->
                    <img src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/images/logo-icon.png" alt="home" class="dark-logo" />
                    <!--This is light logo icon-->
                    <img src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/images/logo-icon.png" alt="home" class="light-logo" />
                </b>
                    <!-- Logo text image you can use text also -->
                <span class="hidden-xs">
                    <!--This is dark logo text-->
                    <img src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/images/logo-text.png" alt="home" class="dark-logo" />
                    <!--This is light logo text-->
                    <img src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/images/logo-text.png" alt="home" class="light-logo" />
                </span> 
            </a>
        </div>
        <!-- /Logo -->
        <!-- Search input and Toggle icon -->
        <ul class="nav navbar-top-links navbar-left">
            <li><a href="javascript:void(0)" class="open-close waves-effect waves-light"><i class="ti-menu"></i></a></li>

            <?php if(count($unverified_vendor) > 0) : ?>
            <li class="dropdown">
                <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"> <i class="mdi mdi-bell"></i>
                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                </a>
                <ul class="dropdown-menu mailbox animated bounceInDown">
                    <li>
                        <div class="drop-title">Ada <?= count($unverified_vendor) ?> vendor yang belum diverifikasi</div>
                    </li>
                    <li>
                        <div class="message-center">
                            <?php foreach($unverified_vendor as $key => $obj) : ?>
                                <?php if($key < 5) : ?>
                                <a href="<?= Snl::app()->baseUrl() ?>admin/vendor/index">
                                    <div class="mail-contnet">
                                        <h5><?= $obj->printed_id ?> - <?= $obj->name ?></h5>
                                        
                                    </div>
                                </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </li>
                    <li>
                        <a class="text-center" href="<?= Snl::app()->baseUrl() ?>admin/vendor/index"> <strong>Lihat semua vendor</strong> <i class="fa fa-angle-right"></i> </a>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>
            <?php endif; ?>
        </ul>
        <ul class="nav navbar-top-links navbar-right pull-right">
            <li class="dropdown">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="<?= Snl::app()->config()->avatar_url ?>" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?= Snl::app()->user()->firstname ?></b><span class="caret"></span> </a>
                <ul class="dropdown-menu dropdown-user animated flipInY">
                    <li>
                        <div class="dw-user-box">
                            <div class="u-img"><img src="<?= Snl::app()->config()->avatar_url ?>" alt="user" /></div>
                            <div class="u-text">
                                <h4><?= Snl::app()->user()->completeName ?></h4>
                                <p class="text-muted"><?= Snl::app()->user()->email ?></p><a href="#" class="btn btn-rounded btn-danger btn-sm hidden">View Profile</a></div>
                        </div>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?= Snl::app()->baseUrl() ?>admin/user/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
</nav>
<!-- End Top Navigation -->