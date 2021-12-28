<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <div id="customer_order_grid"></div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'outlet/search?ajax=1';
        $("#customer_order_grid").jsGrid({
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
                title: "Mesin",
                name: "machine_no",
                type: "text",
            }, {
                title: "Start Mesin",
                name: "start_machine_counter",
                type: "text",
            }, {
                title: "Nama Customer",
                name: "name",
                type: "text",
            }, {
                title: "No. Telp",
                name: "phone",
                type: "text",
            }, {
                title: "Mobil",
                name: "car_info",
                type: "text",
            }, {
                title: "Tanggal Order",
                name: "order_date",
                type: "text",
            }, {
                title: "Status Order",
                name: "order_status",
                type: "text",
            }, {
                title: "Keterangan",
                name: "remarks",
                type: "text",
            }]
        });

    });
</script>