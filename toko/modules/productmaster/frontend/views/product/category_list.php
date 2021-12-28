<?php
    echo $this->render(Snl::app()->rootDirectory().'themes/frontend/views/general/breadcrumbs-thin.php', array(), TRUE);
?>

<div class="container">
    <?php if(count($categories) > 0) : ?>
        <div class="c-content-box c-size-md">
            <div id="grid-container" class="cbp cbp-l-grid-agency">
                <?php foreach($categories as $category) : ?>
                    <div class="cbp-item graphic">
                        <a href="<?= Snl::app()->baseUrl() ?>product/viewcategory?name=<?= $category->url_key ?>" target="">
                            <div class="cbp-caption">
                                <div class="cbp-caption-defaultWrap">
                                    <div style="width: 273px; height: 273px; text-align: center; position: relative;">
                                        <img src="<?= $category->getImage() ?>" alt="" style="max-width: 100%; max-height: 100%; width: auto; height: auto; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                                    </div>
                                </div>
                            </div>
                            <div class="cbp-l-grid-agency-title"><?= $category->name ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else : ?>
        <p><i>Tidak ditemukan data.</i></p>
    <?php endif; ?>
</div>