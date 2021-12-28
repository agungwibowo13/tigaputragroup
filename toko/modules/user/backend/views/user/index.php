<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <div class="row">
                <div class="col-md-12">
                    <div id="user_grid"></div>
                </div>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var ajaxUrl = baseUrl + 'admin/user/search?ajax=1';
        $("#user_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: pageSize,
            pageLoading: true,
            pageButtonCount: 5,
            deleteConfirm: "Do you really want to delete the user?",
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
                name: "username",
                type: "text",
            }, {
                name: "email",
                type: "text",
            }, {
                title: "Nama Depan",
                name: "firstname",
                type: "text",                
            }, {
                title: "Nama Belakang",
                name: "lastname",
                type: "text",
            }, {
                name: "status",
                type: "select",
                items: [
                    { text: "", value: "" },
                    { text: "Active", value: "1" },
                    { text: "Inactive", value: "0" },
                ],
                valueField: "value",
                textField: "text"
            }, {
                type: "control",
                itemTemplate: function(value, item) {
                    var $editElm = "<a href='"+baseUrl+"admin/user/update?id="+item.user_id+"' title='Edit'><i class='glyphicon glyphicon-pencil'></i></a>&nbsp;";

                    var $deleteElm = '<a href="'+baseUrl+'admin/user/delete?id='+item.user_id+'" title="Delete" onclick="return confirm(\'Are you sure to delete this item?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                    return $editElm + $deleteElm;
                }
            }]
        });

    });
</script>