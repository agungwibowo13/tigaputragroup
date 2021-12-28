<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <div id="product_grid"></div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'admin/productmaster/search?ajax=1';
        $("#product_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: pageSize,
            pageLoading: true,
            pageButtonCount: 5,
            pageSize: 20,
            deleteConfirm: "Apakah anda yakin untuk menghapus barang ini?",
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
                title: "Nama Barang",
                name: "name",
                type: "text",
                width: "250px"
            }, {
                title: "HPP",
                name: "hpp",
                type: "text",
                align: "right",
            }, {
                title: "Harga Satuan",
                name: "price",
                type: "text",
                align: "right",
            }, {
                title: "Harga Grosir",
                name: "grosir",
                type: "text",
                align: "right",
            }, {
                title: "Margin",
                name: "margin_percentage",
                type: "text",
                align: "right",
            }, {
                title: "Keterangan",
                name: "remarks",
                type: "text",
                align: "left",
            }, {
                title: "Last Update",
                name: "updated_on",
                type: "text",
            }, /*{
                title: "Updated By",
                name: "updated_by",
                type: "text",
            }, */{
                type: "control",
                itemTemplate: function(value, item) {
                    var $editElm = "<a href='"+baseUrl+"admin/productmaster/update?id="+item.product_master_id+"' title='Edit'><i class='glyphicon glyphicon-pencil'></i></a>&nbsp;";

                    var $deleteElm = '<a href="'+baseUrl+'admin/productmaster/delete?id='+item.product_master_id+'" title="Delete" onclick="return confirm(\'Apakah anda yakin untuk menghapus barang ini?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                    return $editElm + $deleteElm;
                }
            }]
        });

    });
</script>