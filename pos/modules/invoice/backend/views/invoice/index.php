<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <div id="invoice_grid"></div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'admin/invoice/search?ajax=1';
        $("#invoice_grid").jsGrid({
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
                title: "No. Invoice",
                name: "invoice_number",
                type: "text",                
            }, {
                title: "Tanggal",
                name: "invoice_date",
                type: "text",
                align: "right",
            }, {
                title: "Total",
                name: "total",
                type: "text",
                align: "right",
            }, {
                title: "Profit",
                name: "profit",
                type: "text",
                align: "right",
            }, {
                type: "control",
                itemTemplate: function(value, item) {
                    var $view = "<a href='"+baseUrl+"admin/invoice/view?id="+item.invoice_id+"' title='View'><i class='glyphicon glyphicon-eye-open'></i></a>&nbsp;";

                    var $deleteElm = '<a href="'+baseUrl+'admin/invoice/delete?id='+item.invoice_id+'" title="Delete" onclick="return confirm(\'Apakah anda yakin untuk menghapus invoice ini?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                    return $view + $deleteElm;
                }
            }]
        });

    });
</script>