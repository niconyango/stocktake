<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock Take History
            <small>Report</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>stocks"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Report</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-xs-6">
                            <!--Start Date text input -->
                            <div class="box-body">
                                <div class="col-md-12">
                                    <form role="form" action="<?php echo base_url() ?>historysearch" method="post">
                                        <div class="row">
                                            <!-- Department  -->
                                            <div class="form-group col-md-4">
                                                <select name="userid" id="user" class="form-control">
                                                    <option value="userid">Select User</option>
                                                    <?php
                                                    if (!empty($users)) {
                                                        foreach ($users as $row) {
                                                    ?>
                                                            <option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>

                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!--Category -->
                                            <div class="form-group col-md-3">
                                                <input type="text" required="" name="sDate" id="sDate" class="form-control" placeholder="Start Date" autocomplete="off" />
                                            </div>
                                            <!--Sub Category-->
                                            <div class="form-group col-md-4">
                                                <input type="text" required="" name="eDate" id="eDate" class="form-control" placeholder="End Date" autocomplete="off" />
                                            </div>
                                            <div class="form-group col-md-1">
                                                <button type="submit" class="btn btn-success btn-flat btn-search" style="border-radius: 5px;"><i class="fas fa-search"></i> &nbsp; Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <h3 class="box-title"></h3>
                        </div>
                        <div class="col-xs-6">
                            <a class="btn btn-success pull-right btn-xs btn-flat" style="border-radius: 5px;" href="<?php echo base_url(); ?>historyexcel"><i class="fad fa-file-excel"></i>&nbsp; Excel</a>
                            <a class="btn btn-default pull-right btn-xs btn-flat" style="margin-right: 5px;border-radius:5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fal fa-file-pdf"></i>&nbsp; Pdf</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="stock" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Branch</th>
                                    <th>Start Date</th>
                                    <th>Initiated By </th>
                                    <th>Committed By </th>
                                    <th>Closed Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($history)) {
                                    $i = 1;
                                    foreach ($history as $row) {
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><a href="<?php echo base_url(); ?>stocktakedetails/<?php echo $row->ID; ?>"><?php echo $row->Description; ?></a></td>
                                            <td><?php echo $row->CountingDate; ?></a></td>
                                            <td><?php echo $row->InitiatedByName; ?></td>
                                            <td><?php echo $row->CommittedName; ?></td>
                                            <td><?php echo $row->DateCommitted; ?></td>
                                            <td><?php
                                                if ($row->Status == 0) {
                                                ?>
                                                    <a class="btn btn-flat btn-xs btn-danger" href="#"><i class="fa fa-ban"></i>&nbsp;&nbsp;Pending
                                                    </a>
                                                <?php } else { ?>
                                                    <a class="btn btn-flat btn-xs btn-success" href="#"><i class="fa fa-check-circle">
                                                        </i> Posted</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Branch</th>
                                    <th>Start Date</th>
                                    <th>Initiated By </th>
                                    <th>Committed By </th>
                                    <th>Closed Date</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#stock").DataTable({})
        $('#sDate').datepicker({
            autoclose: true
        })
        $('#eDate').datepicker({
            autoclose: true
        })
    })
    $('#department').change(function() {

        $("#category").html("<option value=''>Select Category</option>")

        var department = $(this).val()
        $.ajax({
            type: 'post',
            url: 'http://192.168.110.4/stocktake/Welcome/get_categories_department',
            data: 'departmentid=' + department,
            success: function(data) {
                var json = $.parseJSON(data)
                $.each(json.Category, function(index, value) {
                    var nicholas = '<option value="' + value.ID + '">' + value.Name + '</option>';

                    $("#category").append(nicholas)
                })
            }
        })
    })
    $('#category').change(function() {

        $("#subcategory").html("<option value=''>Select SubCategory</option>")

        var category = $(this).val()
        $.ajax({
            type: 'post',
            url: 'http://192.168.110.4/stocktake/Welcome/get_subcategories_department',
            data: 'categoryid=' + category,
            success: function(data) {
                var json = $.parseJSON(data)
                $.each(json.SubCategory, function(index, value) {
                    var nicholas = '<option value="' + value.ID + '">' + value.Name + '</option>';

                    $("#subcategory").append(nicholas)
                })
            }
        })
    })
</script>