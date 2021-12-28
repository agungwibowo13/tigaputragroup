<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <div id="category_grid"></div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'admin/category/search?ajax=1';
        $("#category_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: pageSize,
            pageLoading: true,
            pageButtonCount: 5,
            deleteConfirm: "Do you really want to delete the category?",
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        dataType: "json",
                        type: "GET",
                        url: ajaxUrl,
                        data: filter
                    });
                },
            },
            fields: [{
                title: "Nama",
                name: "name",
                type: "text",
            }, {
                title: "Gambar",
                name: "image_url",
                itemTemplate: function(value, item) { 
                    return '<img src="'+value+'" style="height: auto; width: auto; max-width: 100px; max-height: 100px;" />';
                },
            }, {
                type: "control",
                itemTemplate: function(value, item) {
                    var $editElm = "<a href='"+baseUrl+"admin/category/update?id="+item.category_id+"' title='Edit'><i class='glyphicon glyphicon-pencil'></i></a>&nbsp;";

                    var $deleteElm = '<a href="'+baseUrl+'admin/category/delete?id='+item.category_id+'" title="Delete" onclick="return confirm(\'Anda yakin untuk menghapus kategori ini?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                    return $editElm + $deleteElm;
                }
            }]
        });

    });
</script>