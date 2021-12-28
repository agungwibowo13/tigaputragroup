<?php
    echo $toolbar;
    echo Snl::app()->getFlashMessage();    
?>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box p-l-20 p-r-20">
            <form class="form-material form-horizontal" id="app_form" action="#" method="GET" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-md-12">Select Date</label>
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="report-date" name="Report[date]" value="<?= $selected_date ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-12">Mode</label>
                    <div class="col-md-12">
                        <select class="form-control" id="report-mode" name="Report[mode]">
                            <option value="d"<?= $selected_mode == 'd' ? ' selected' : '' ?>>Day</option>
                            <option value="m"<?= $selected_mode == 'm' ? ' selected' : '' ?>>Month</option>
                            <option value="y"<?= $selected_mode == 'y' ? ' selected' : '' ?>>Year</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-condensed" id="invoice-table">
            <tr>
                <td width="100">Total</td>
                <th><?= $total ?></th>
            </tr>

            <tr>
                <td>Profit</td>
                <th><?= $profit ?></th>
            </tr>

            <tr>
                <td>Persentase</td>
                <th><?= $persentase ?></th>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#report-date').datepicker({
            autoclose: true,
            format: 'dd M yyyy',
            todayHighlight: true,
        });
    });
</script>