<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <div id="outlet_order_grid"></div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'outlet/searchoutletorder?ajax=1';
        $("#outlet_order_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: pageSize,
            pageLoading: true,
            pageButtonCount: 5,
            deleteConfirm: "Do you really want to delete the order?",
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
                title: "No. Order",
                name: "order_printed_id",
                type: "text",
            }, {
                title: "Tanggal Order",
                name: "order_date",
                type: "text",
            }, {
                title: "QTY",
                name: "qty",
                type: "text",
            }, {
                title: "Harga per Paket",
                name: "package_price",
                type: "text",
            }, {
                title: "Item per Paket",
                name: "items",
                type: "text",
            }, {
                title: "Status",
                name: "status",
                type: "text",
                itemTemplate: function(value, item) {
                    var order_label = ' label-danger';
                    if(item.status == 'Approved') {
                        order_label = ' label-info';
                    } else if(item.status == 'Delivered') {
                        order_label = ' label-success';
                    }

                    return "<span class='label"+order_label+"'>"+item.status+"</span>";
                }
            }, {
                title: "Tanggal Pengiriman",
                name: "delivery_date",
                type: "text",
            }, {
                title: "Status Pembayaran",
                name: "payment_status",
                type: "text",
                itemTemplate: function(value, item) {
                    var order_label = ' label-danger';
                    if(item.payment_status == 'Invoiced') {
                        order_label = ' label-warning';
                    } else if(item.payment_status == 'Paid') {
                        order_label = ' label-success';
                    }

                    return "<span class='label"+order_label+"'>"+item.payment_status+"</span>";
                }
            }, {
                type: "control",
                width: "100px",
                itemTemplate: function(value, item) {
                    $invoice_button = "<a data-fancybox data-type='ajax' class='grid-button' data-src='"+baseUrl+"outlet/blankinvoice?ajax=1' href='javascript:;'>Lihat Invoice</a>";

                    if(item.status == 'Delivered' && (item.payment_status == 'Invoiced' || item.payment_status == 'Paid')) {
                        $invoice_button = "<a data-fancybox data-type='ajax' class='grid-button' data-src='"+baseUrl+"outlet/openinvoice?ajax=1&outlet_order_id="+item.outlet_order_id+"' href='javascript:;'>Lihat Invoice</a>";
                    }


                    return $invoice_button;
                }
            }]
        });

    });
</script>