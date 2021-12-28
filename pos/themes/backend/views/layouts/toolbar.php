<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"><?= $page_title ?></h4>
    </div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <?= $toolbarElement ?>
        <ol class="breadcrumb hidden">
        	<?php
        		// foreach ($crumbs as $key => $value) {
        		// 	$isActive = $key == count($crumbs) - 1 ? TRUE : FALSE;
          //           if($isActive) {
          //               echo '<li class="active">'.$value.'</li>';   
          //           } else {
          //               echo '<li><a href="#">'.$value.'</a></li>';       
          //           }
        			
        		// }
        	?>
        </ol>
    </div>
</div>