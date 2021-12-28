<div id="product_grid"></div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'default/search?ajax=1';
        $("#product_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageLoading: true,
            pageButtonCount: 5,
            pageSize: 10,
            heading: false,
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
                width: "150px",
            }, {
                title: "Harga Satuan",
                name: "price",
                type: "text",
                align: "right",
                filtering: false,
            }, {
                title: "Keterangan",
                name: "remarks",
                type: "text",
                align: "left",
                filtering: false,
            }]
        });

    });
</script>