<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            System Users
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">List</li>
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
                                </div>
                            </div>
                            <h3 class="box-title"></h3>
                        </div>
                        <div class="col-xs-6">
                            <a class="btn btn-success pull-right btn-xs btn-flat" href="<?php echo base_url(); ?>historyexcel"><i class="fa fa-file-excel-o"></i>Excel</a>
                            <a class="btn btn-default pull-right btn-xs btn-flat" style="margin-right: 5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="users" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>UserID</th>
                                    <th>Name</th>
                                    <th>Email Address </th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>User Level</th>
                                    <th>Branch</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($users)) {
                                    $i = 1;
                                    foreach ($users as $row) {
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row->Number; ?></td>
                                            <td><?php echo $row->Name; ?></a></td>
                                            <td><?php echo $row->EmailAddress; ?></td>
                                            <td><?php echo $row->Telephone; ?></td>
                                            <td><?php
                                                if ($row->Enabled == 0) {
                                                ?>
                                                    <a class="btn btn-flat btn-xs btn-danger" href="#"><i class="fa fa-ban"></i>&nbsp;&nbsp;Inactive
                                                    </a>
                                                <?php } else { ?>
                                                    <a class="btn btn-flat btn-xs btn-success" href="#"><i class="fa fa-check-circle">
                                                        </i> Active</a>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row->SecurityLevel; ?></td>
                                            <td><?php echo $row->Description; ?></td>
                                            <td>
                                                <!-- <a class="btn btn-flat btn-xs btn-warning" href="#"><i class="fas fa-user-edit"></i>&nbsp;&nbsp;Edit
                                                </a> -->
                                                <button class="btn btn-flat btn-xs btn-warning btn-edit" id="<?php echo $row->ID; ?>" userid="<?php echo $row->Number; ?>" name="<?php echo $row->Name; ?>" tel="<?php echo $row->Telephone; ?>" email="<?php echo $row->EmailAddress; ?>" branch="<?php echo $row->StoreID; ?>" password="<?php echo $row->Pass; ?>" level="<?php echo $row->Security; ?>">
                                                    <i class="fas fa-user-edit"></i>&nbsp;Edit</button>
                                                <?php if ($row->Enabled == 1) {
                                                ?>
                                                    <a class="btn btn-flat btn-xs btn-danger" href="<?php echo base_url() ?>disable/<?php echo $row->ID; ?>"><i class="fas fa-ban"></i> Disable</a>
                                                <?php } else { ?>
                                                    <a class="btn btn-flat btn-xs btn-primary" href="<?php echo base_url() ?>activate/<?php echo $row->ID; ?>"><i class="fas fa-edit"></i> Activate</a>
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
                                    <th>UserID</th>
                                    <th>Name</th>
                                    <th>Email Address </th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>User Level</th>
                                    <th>Branch</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- User modal -->
<div class="modal modal-default" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">User Details</h4>
            </div>
            <div class="modal-body">
                <div class="alert" role="alert" id="error" style="display:none"></div>
                <form role="form" id="user-form">
                    <input type="hidden" name="action" id="action" value="0" />
                    <!-- text input -->
                    <div class="form-group">
                        <!-- <label>Name</label>
                        <input type="text" name="Name" id="Name" required class="form-control" autocomplete="off" placeholder="Fullname" /> -->
                        <div class="row">
                            <!-- text input -->
                            <div class="form-group col-md-6">
                                <label>UserID</label>
                                <input type="text" name="Number" id="Number" class="form-control" autocomplete="off" placeholder="UserID" />
                            </div>
                            <!-- text input-->
                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <input type="text" name="Name" id="Name" autocomplete="off" required="" class="form-control" placeholder="Fullname" />
                            </div>
                        </div>
                    </div>
                    <!--text-->
                    <div class="form-group">
                        <div class="row">
                            <!-- text input -->
                            <div class="form-group col-md-6">
                                <label>Phone Number</label>
                                <input type="text" name="Telephone" id="Telephone" class="form-control" autocomplete="off" placeholder="+254 000 000 000" />
                            </div>
                            <!-- text input-->
                            <div class="form-group col-md-6">
                                <label>Email Address</label>
                                <input type="email" name="EmailAddress" id="EmailAddress" autocomplete="off" required="" class="form-control" placeholder="Email Address" />
                            </div>
                        </div>
                    </div>
                    <!-- text input -->
                    <div class="form-group">
                        <label>User Group</label>
                        <select name="SecurityLevel" id="SecurityLevel" class="form-control">
                            <option value="">Select User Group</option>
                            <?php
                            if (!empty($userlevels)) {
                                foreach ($userlevels as $row) {
                            ?>
                                    <option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>

                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!--text input-->
                    <div class="form-group">
                        <label>User Branch</label>
                        <select name="StoreID" id="StoreID" class="form-control">
                            <option value="">Select Branch</option>
                            <?php
                            if (!empty($branches)) {
                                foreach ($branches as $row) {
                            ?>
                                    <option value="<?php echo $row->ID; ?>"><?php echo $row->Description; ?></option>

                            <?php
                                }
                            }
                            ?>
                        </select>


                    </div>
                    <div class="form-group">
                        <label>
                            Enabled?&nbsp;
                            <input type="radio" id="enabled" name="r3" Value="1" class="flat-red">
                        </label>
                        &nbsp;
                        <label>
                            <input type="radio" id="disabled" name="r3" value="0" class="flat-red">&nbsp;
                            Disabled
                        </label>
                    </div>
                    <!-- text input -->
                    <div class="form-group">
                        <div class="row">
                            <!-- text input -->
                            <div class="form-group col-md-6">
                                <label>Password</label>
                                <input type="password" name="Security" id="Security" autocomplete="off" required pattern=".{4,}" minlength="6" class="form-control" placeholder="Password" />
                                <span toggle="#Password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <!-- text input -->
                            <div class="form-group col-md-6">
                                <label>Confirm Password</label>
                                <input type="password" name="SecurityConfirm" id="SecurityConfirm" required pattern=".{4,}" minlength="6" class="form-control" placeholder="Confirm Password" />
                                <span toggle="#Password-confirm" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            Password Expires?&nbsp;
                            NO
                            <input type="radio" id="passno" name="r1" Value="0" class="flat-red">
                        </label>
                        <label>
                            <input type="radio" id="passyes" name="r1" value="1" class="flat-red">
                            YES
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="far fa-times-circle"></i>&nbsp;Close</button>
                <button type="button" class="btn btn-primary" id="btn-user"><i class="fas fa-user-edit"></i>&nbsp;Update</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#users").DataTable({})
        $('#sDate').datepicker({
            autoclose: true,
            todayHighlight: true
        })
        $('#eDate').datepicker({
            autoclose: true
        })
        $("body").on('click', '.btn-edit', function(e) {
            //$(".btn-edit").click(function(e) {
            var Fullname = $(this).attr('Fullname')
            $("#action").val($(this).attr('ID'));
            $("#Number").val($(this).attr('userid'));
            $("#Name").val($(this).attr('name'));
            $("#Telephone").val($(this).attr('tel'));
            $("#EmailAddress").val($(this).attr('email'));
            $("#SecurityLevel").val($(this).attr('level'));
            $("#StoreID").val($(this).attr('branch'));

            $("#user-modal").modal("show")
        })

        $("#btn-user").click(function(e) {

            var url = "<?php echo base_url('user'); ?>";
            var data = $("#user-form").serialize();
            //alert(data)
            $.ajax({
                type: "POST",
                data: data,
                url: url,
                success: function(data) {
                    $("#error").html(data).show().addClass("alert-success");

                    location.reload();
                }
            });

        })
    })
    $('#department').change(function() {

        $("#category").html("<option value=''>Select Category</option>")

        var department = $(this).val()
        $.ajax({
            type: 'post',
            url: '<?php echo base_url("get_categories_department");?>',
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
            url: '<?php echo base_url("get_subcategories_department");?>',
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