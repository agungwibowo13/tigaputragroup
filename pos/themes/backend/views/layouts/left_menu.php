<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                <div><img src="<?= Snl::app()->config()->avatar_url ?>" alt="user-img" class="img-circle"></div>
                <a href="javascript:void(0)" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= Snl::app()->user()->completeName ?></a>
            </div>
        </div>
        <ul class="nav" id="side-menu">
            <li><a href="<?= Snl::app()->baseUrl() ?>admin/productmaster/index"><span class="hide-menu">Produk</span></a></li>

            <li><a href="<?= Snl::app()->baseUrl() ?>admin/invoice/index"><span class="hide-menu">Invoice</span></a></li>

            <li><a href="<?= Snl::app()->baseUrl() ?>admin/report/index"><span class="hide-menu">Report</span></a></li>

            <li><a href="<?= Snl::app()->baseUrl() ?>admin/user/index"><span class="hide-menu">User</span></a></li>

            <li> <a href="javascript:void(0)" class="waves-effect"><span class="hide-menu">Pengaturan <span class="fa arrow"></span> </span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?= Snl::app()->baseUrl() ?>admin/setting/index"><span class="hide-menu">Pengaturan Umum</span></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Left Sidebar -->
<!-- ============================================================== -->