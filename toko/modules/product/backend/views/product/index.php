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
        var ajaxUrl = baseUrl + 'admin/product/search?ajax=1';
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
            deleteConfirm: "Apakah anda yakin untuk menghapus toko ini?",
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
                title: "Kategori",
                name: "category",
                type: "text",
            }, {
                title: "Nama Produk",
                name: "name",
                type: "text",
            }, {
                title: "UOM/Satuan",
                name: "uom",
                type: "text",
            }, {
                title: "HPP",
                name: "hpp",
                type: "text",
            }, {
                title: "Harga Jual",
                name: "price",
                type: "text",
            }, {
                type: "control",
                itemTemplate: function(value, item) {
                    var $editElm = "<a href='"+baseUrl+"admin/product/update?id="+item.product_id+"' title='Edit'><i class='glyphicon glyphicon-pencil'></i></a>&nbsp;";

                    var $deleteElm = '<a href="'+baseUrl+'admin/product/delete?id='+item.product_id+'" title="Delete" onclick="return confirm(\'Are you sure to delete this product?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                    return $editElm + $deleteElm;
                }
            }]
        });

    });
</script>