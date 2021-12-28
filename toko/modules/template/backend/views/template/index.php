<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <div id="outlet_grid"></div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'admin/outlet/search?ajax=1';
        $("#outlet_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: pageSize,
            pageLoading: true,
            pageButtonCount: 5,
            deleteConfirm: "Do you really want to delete the outlet?",
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
                title: "Alamat",
                name: "address",
                type: "text",
            }, {
                title: "No. Telp",
                name: "phone",
                type: "text",
            }, {
                title: "Username",
                name: "username",
                type: "text",
            }, {
                type: "control",
                itemTemplate: function(value, item) {
                    var $machineElm = "<a href='"+baseUrl+"admin/outlet/machine?id="+item.outlet_id+"' title='Machine Assignment'><i class='glyphicon glyphicon-cog'></i></a>&nbsp;";

                    var $editElm = "<a href='"+baseUrl+"admin/outlet/update?id="+item.outlet_id+"' title='Edit'><i class='glyphicon glyphicon-pencil'></i></a>&nbsp;";

                    var $deleteElm = '<a href="'+baseUrl+'admin/outlet/delete?id='+item.outlet_id+'" title="Delete" onclick="return confirm(\'Anda yakin untuk menghapus outlet ini?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                    return $machineElm + $editElm + $deleteElm;
                }
            }]
        });

    });
</script>