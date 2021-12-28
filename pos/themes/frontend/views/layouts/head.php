<!DOCTYPE html>
<!--[if IE 9]> 
<html lang="en" class="ie9 no-js">
    <![endif]-->
    <!--[if !IE]><!-->
    <html lang="en">
        <!--<![endif]-->
        <!-- BEGIN HEAD -->
        <head>
            <meta charset="utf-8"/>
            <title><?= Snl::app()->config(TRUE)->site_title ?></title>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8">
            <meta content="" name="description"/>
            <meta content="" name="author"/>

            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/base/css/themes/red1.css" rel="stylesheet" id="style_theme" type="text/css"/>

            <!-- BEGIN GLOBAL MANDATORY STYLES -->
			<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700&subset=latin,cyrillic-ext,greek-ext,cyrillic,latin-ext,vietnamese,greek' rel='stylesheet' type='text/css'>
			<link href='https://fonts.googleapis.com/css?family=Hind:400,500,300,600,700' rel='stylesheet' type='text/css'>
			<link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/socicon/socicon.css" rel="stylesheet" type="text/css"/>
			<link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-social/bootstrap-social.css" rel="stylesheet" type="text/css"/>
			<link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
			<link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
			<link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/animate/animate.min.css" rel="stylesheet" type="text/css"/>
			<link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
			<!-- END GLOBAL MANDATORY STYLES -->

			<!-- BEGIN: BASE PLUGINS -->
			<link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/fancybox-master-3.2.10/dist/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
			<!-- END: BASE PLUGINS -->

            <!-- BEGIN: PAGE STYLES -->
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/sweetalert/sweetalert2.min.css" rel="stylesheet" type="text/css">
            <!-- END: PAGE STYLES -->

            <!-- BEGIN THEME STYLES -->
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/base/css/plugins.css" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/base/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/base/css/themes/default.css" rel="stylesheet" id="style_theme" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/base/css/custom.css" rel="stylesheet" type="text/css"/>
            <!-- END THEME STYLES -->

            <!-- BXSLIDER -->
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bxslider-4-4.2.12/dist/jquery.bxslider.css" rel="stylesheet" type="text/css"/>

            <!-- JSGRID -->
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/jsgrid-1.5.3/dist/jsgrid.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/jsgrid-1.5.3/dist/jsgrid-theme.min.css" rel="stylesheet" type="text/css"/>

            <!-- BEGIN: LOCAL STYLES -->
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/local/css/local.css" rel="stylesheet" type="text/css"/>
            <!-- END: LOCAL STYLES -->

            <!-- BEGIN: JQUERY -->
            <script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/jquery.min.js" type="text/javascript" type="text/javascript" ></script>
            <!-- END: JQUERY -->

            <!-- SELECT2 -->
            <link href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/select2-3.5.3/select2.css" rel="stylesheet" type="text/css"/>
            <script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/select2-3.5.3/select2.js" type="text/javascript"></script>

            <link rel="shortcut icon" href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/images/favicon.ico" type="image/x-icon">
            <link rel="icon" href="<?= Snl::app()->config(TRUE)->theme_url ?>assets/images/favicon.ico" type="image/x-icon">
            <script type="text/javascript">
                var totalPages = 1;
                var visiblePages = 5;
                var baseUrl = "<?= Snl::app()->baseUrl() ?>";
            </script>
        </head>

        <body class="c-layout-header-fixed c-layout-header-mobile-fixed c-shop-demo-1"> 