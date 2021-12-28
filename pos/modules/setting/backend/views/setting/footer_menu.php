<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row" id="footer_menu">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <label class="col-md-12">Menu Column</label>
                <div class="col-md-6">
                    <select class="form-control" id="menu_column">
                        <option<?= $menu_column == 'col-one' ? ' selected' : '' ?> value="col-one">Column 1</option>
                        <option<?= $menu_column == 'col-two' ? ' selected' : '' ?> value="col-two">Column 2</option>
                    </select>
                </div>
            </div>

            <hr />

            <div class="row">
                <div class="col-md-12" id="sortable_items">
                    <div class="help-block">Centang menu yang ingin ditampilkan. Drag & Drop untuk mengubah urutan. Lalu tekan tombol simpan di pojok kanan atas.</div>
                    <div class="row">
                        <div class="col-xs-3">
                            <h3>Page</h3>
                        </div>
                        <div class="col-xs-5 c-center">
                            <h3>Label Menu</h3>
                        </div>
                    </div>

                    <hr style="margin-top: 0; margin-bottom: 5px;" />
                    <?php if(count($active_menu) > 0) : foreach($active_menu as $obj) : ?>
                        <?php
                            $page = Page::model()->findByPk($obj->page_id);
                        ?>
                        <div class="row sortable-item">
                            <div class="col-xs-4">
                                <div class="checkbox">
                                    <input id="<?= $obj->page_id ?>" checked type="checkbox" class="footer-menu-cb" value="<?= $obj->page_id ?>">
                                    <label for="<?= $obj->page_id ?>"><?= $page->title ?></label>
                                </div>
                            </div>

                            <div class="col-xs-5">
                                <input type="text" class="form-control" value="<?= $obj->label ?>" id="label-<?= $obj->page_id ?>" />
                            </div>
                        </div>
                    <?php endforeach; endif; ?>

                    <?php foreach($model as $obj) : ?>
                        <div class="row sortable-item">
                            <div class="col-xs-4">
                                <div class="checkbox">
                                    <input id="<?= $obj->page_id ?>" type="checkbox" class="footer-menu-cb" value="<?= $obj->page_id ?>">
                                    <label for="<?= $obj->page_id ?>"><?= $obj->title ?></label>
                                </div>
                            </div>

                            <div class="col-xs-5">
                                <input type="text" class="form-control" value="<?= $obj->title ?>" id="label-<?= $obj->page_id ?>" />
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#sortable_items").sortable({
                start: function (event, ui) {
                        ui.item.toggleClass("highlight");
                },
                stop: function (event, ui) {
                        ui.item.toggleClass("highlight");
                }
        });
        $("#sortable_items").disableSelection();

        $('#menu_column').on('change', function() {
            initLoading();
            var menu_column = $(this).val();
            window.location = baseUrl + 'admin/setting/footermenu?menu_column='+menu_column;
        });

        $('#btn_save').on('click', function() {
            initLoading();

            var ajaxUrl = baseUrl + 'admin/setting/savefootermenu?ajax=1';
            var data = [];
            var menu_column = $('#menu_column').val();

            $('.footer-menu-cb').each(function(index) {
                if($(this).is(':checked')) {
                    var page_id = $(this).val();
                    var label = $('#label-'+page_id).val();
                    var tmp = {};
                    tmp['page_id'] = page_id;
                    tmp['label'] = label;
                    data.push(tmp);
                }
            });

            console.log(data);

            $.post(ajaxUrl, {menu_column:menu_column, data:JSON.stringify(data)}, function(result) {
                destroyLoading();
                if(result) {
                    swal("Proses penyimpanan telah berhasil.");
                } else {
                    swal("WARNING!", "Internal server error.");
                }
            });

        });
    });
</script>