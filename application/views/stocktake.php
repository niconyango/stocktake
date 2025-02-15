<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock Take
            <small>Process</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Stocks</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Product Details</h3>
                    </div>
                    <div class="box-body">
                        <!-- Bin/(shelf) sheet -->
                        <div class="alert" role="alert" id="error" style="display:none"></div>

                        <?php if ($this->session->flashdata('res') != '') { ?>
                            <!-- <div class="alert alert-success"> <?php echo $this->session->flashdata('res'); ?> </div> -->
                            <div class="alert alert-success">
                                <a href="#" class="close" data-bs-dismiss="alert">&times;</a>
                                <strong>Success!</strong> <?php echo $this->session->flashdata('res');
                                unset($_SESSION['res']); ?>
                            </div>
                        <?php } else if ($this->session->flashdata('updating')) { ?>
                            <div class="alert alert-warning">
                                <a href="#" class="close" data-bs-dismiss="alert">&times;</a>
                                <strong>Warning!</strong> <?php echo $this->session->flashdata('updating');
                                unset($_SESSION['updating']); ?>
                            </div>
                        <?php } else if ($this->session->flashdata('missing') != NULL) { ?>
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-bs-dismiss="alert">&times;</a>
                                <strong>Error!</strong> <?php echo $this->session->flashdata('missing');
                                unset($_SESSION['missing']); ?>
                            </div>
                        <?php } else if ($this->session->flashdata('sheetchange')) { ?>
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-bs-dismiss="alert">&times;</a>
                                <strong>Danger!</strong> <?php echo $this->session->flashdata('sheetchange');
                                unset($_SESSION['sheetchange']); ?>
                            </div>
                        <?php } else if ($this->session->flashdata('adding')) { ?>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#codeupdate-modal').modal('show');
                                });
                            </script>
                        <?php unset($_SESSION['adding']); ?>
                        <?php } elseif ($this->session->flashdata('added')) { ?>
                            <div class="alert alert-success">
                                <a href="#" class="close" data-bs-dismiss="alert">&times;</a>
                                <strong>Success!</strong> <?php echo $this->session->flashdata('added');
                                unset($_SESSION['added']); ?>
                            </div>
                        <?php } else if ($this->session->flashdata('bin')) { ?>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#bin-modal').modal('show');
                                });
                            </script>
                        <?php unset($_SESSION['bin']); ?>
                        <?php } else if ($this->session->flashdata('entries')) { ?>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#entries-modal').modal('show');
                                });
                            </script>
                            <?php unset($_SESSION['entries']); ?>
                        <?php } ?>
                        <form role="form" id="stock-form">
                            <input type="hidden" name="action" id="action" value="0"/>
                            <div class="mb-3">
                                <label>Reason:</label>
                                <div class="input-group my-colorpicker2">
                                    <input type="text" name="reasoncode" id="reasoncode" value="<?php echo $reason; ?>" autocomplete="off"
                                           class="form-control" readonly required/>
                                    <div class="input-group-addon">
                                        <i></i>
                                    </div>
                                </div>
                            </div><!-- /.form group -->
                            <div class="mb-3">
                                <label>Shelf/Bin Number:</label>
                                <div class="input-group my-colorpicker2">
                                    <input type="text" name="bin" id="bin" value="<?php echo $shelf; ?>" autocomplete="off"
                                           class="form-control" required/>
                                    <div class="input-group-addon">
                                        <i></i>
                                    </div>
                                </div>
                            </div><!-- /.form group -->
                            <!-- Item Code details -->
                            <div class="mb-3">
                                <label>Item LookupCode:</label>
                                <div class="input-group my-colorpicker2">
                                    <input type="text" name="item_code" id="item_code" autocomplete="off" class="form-control"
                                           placeholder="Code" required/>
                                    <div class="input-group-addon">
                                        <i></i>
                                    </div>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                            <!-- shelf/(bin) count -->
                            <div class="bootstrap-timepicker">
                                <div class="mb-3">
                                    <label>Counted Stock:</label>
                                    <div class="input-group">
                                        <input type="text" name="quantity" id="quantity" onkeypress="return isNumberKey(event)"
                                               autocomplete="off" class="form-control" placeholder="0.00" required/>
                                        <div class="input-group-addon">
                                            <i></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                            <!--Item description -->
                            <div class="bootstrap-timepicker">
                                <div class="mb-3">
                                    <label>Description:</label>
                                    <div class="input-group">
                                        <input type="text" name="item_details" id="item_details" autocomplete="off" class="form-control"
                                               required readonly/>
                                        <div class="input-group-addon">
                                            <i></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                    <!-- <div class="modal-footer justify-content-between">
                        <button onclick="location.href='<?php echo base_url(); ?>sync_stocks'" type="button" class="btn btn-success btn-flat" data-bs-dismiss="modal" style="float:left"><i class="fas fa-sync-alt"></i>&nbsp;Sync Sheets</button>
                    </div> -->
                </div><!-- /.box -->
            </div><!-- /.col (left) -->
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Sheet Details</h3>
                        <button onclick="location.href='<?php echo base_url('post_sheets'); ?>'" type="button" data-bs-backdrop="static"
                                class="btn btn-primary btn-flat float-end" style="border-radius: 5px;"><i class="fad
                                fa-file-upload"></i>&nbsp;
                            Save Sheet
                        </button>
                    </div>
                    <div class="box-body">
                        <table id="sheets" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Bin/(Shelf)</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th class="text-right">Couted</th>
                                <th class="text-right">Stock Date</th>
                                <th class="text-right">User</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($tsheets)) {
                                //$i = 1;
                                foreach ($tsheets as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row->Shelf; ?></td>
                                        <td><?php echo $row->ItemLookupCode; ?></td>
                                        <td><?php echo $row->Itemdescription; ?></a></td>
                                        <td class="text-right"><?php echo number_format($row->Quantity, 2); ?></td>
                                        <td class="text-right"><?php echo $row->tTime; ?></td>
                                        <td><?php echo $row->CashierName; ?></a></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning btn-edit" id="<?php echo $row->ID; ?>"
                                                    itemid="<?php echo $row->ItemID; ?>" itemcode="<?php echo $row->ItemLookupCode; ?>"
                                                    description="<?php echo $row->Itemdescription; ?>" shelf="<?php echo $row->Shelf; ?>"
                                                    quantity="<?php echo $row->Quantity; ?>" cashier="<?php echo $row->CashierName; ?>"><i
                                                        class="fal fa-edit"></i>&nbsp;Edit
                                            </button>
                                            <a class="btn btn-sm btn-danger"
                                               href="<?php echo base_url() ?>remove/<?php echo $row->ID; ?>"><i class="far fa-trash"></i>&nbsp;Remove</a>
                                        </td>
                                    </tr>
                                    <?php
                                    // $i++;
                                }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Bin/(Shelf)</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th class="text-right">Couted</th>
                                <th class="text-right">Stock Date</th>
                                <th class="text-right">User</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </tfoot>
                        </table>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- /.col (right) -->
        </div><!-- /.row -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div class="modal modal-default" id="codeupdate-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title">Warning</h4>
            </div>
            <div class="mb-3 col-md-12">
                <h4 style="text-align:center">The item has already been entered in the current sheet.</h4><br/>
            </div>
            <div class="modal-footer">
                <button onclick="location.href='<?php echo base_url(); ?>cancelcode'" class="btn btn-flat btn btn-outline pull-left"><i
                            class="far fa-times-circle"></i>&nbsp;&nbsp; Cancel
                </button>

                <button class="btn btn-primary" onclick="location.href='<?php echo base_url(); ?>updatecode'" type="button"><i
                            class="far fa-check-circle"></i>&nbsp; Add
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal modal-default" id="bin-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title">Warning</h4>
            </div>
            <div class="mb-3 col-md-12">
                <h4 style="text-align:center">Please first set the Bin before you continue.</h4><br/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="far fa-times-circle"></i>&nbsp;
                    Cancel
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Warning on entries more than 10-->
<div class="modal modal-default" id="entries-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title">Warning</h4>
            </div>
            <div class="mb-3 col-md-12">
                <h4 style="text-align:center">Maximum sheet entries reached, please save the sheet to continue.</h4><br/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="far fa-times-circle"></i>&nbsp;
                    Cancel
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Javascript -->
<script type="text/javascript">
    /** Enter key moving cursor to the next text field  */
    $("#item_code").focus()
    $("#bin").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#item_code").focus()
        }
    });
    $("#item_code").keyup(function (event) {
        // if (event.keyCode == 13) {
        //     $("#quantity").focus()
        // }
        if (event.keyCode == 13) {

            var url = "<?php echo base_url('code_desc'); ?>";
            var code = $("#item_code").val();
            var data = {
                code: code
            }
            //alert(data);
            $.ajax({
                type: "GET",
                data: data,
                url: url,
                success: function (data) {
                    $("#res").html(code).show().addClass("alert-success");
                    var json = $.parseJSON(data)
                    $("#item_details").val(json.Description)
                }

            })
            $("#quantity").focus()
        }
    });
    var currentBoxNumber = 0;
    $("#quantity").keyup(function (event) {
        if (event.keyCode == 13) {

            var url = "<?php echo base_url('stock_take'); ?>";
            var data = $("#stock-form").serialize();
            $.ajax({
                type: "POST",
                async: true,
                data: data,
                url: url,
                success: function (data) {
                    $("#res").html(data).show().addClass("alert-success");

                    location.reload();

                }

            })
        }
    });

    /** Forcing numbers only on quantity */
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    /** Posting stock take item */
    $(document).ready(function () {

        $("body").on('click', '.btn-edit', function (e) {
            //var bin = $(this).attr('bin')
            $("#action").val($(this).attr('id'));
            $("#bin").val($(this).attr('shelf'));
            $("#item_code").val($(this).attr('itemcode'));
            $("#item_details").val($(this).attr('description'));
            $("#quantity").val($(this).attr('quantity'));
        })
        $("#sheets").DataTable({})

    });
</script>