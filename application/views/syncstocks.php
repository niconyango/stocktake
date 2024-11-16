<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock Take Progress
            <small>Status</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>stocks"><i class="fal fa-home-lg"></i>&nbsp; Home</a></li>
            <li class="active">Report</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-xs-2">
                            <h3 class="box-title"></h3>

                            <?php
                            if ($stocktakestatus != 0) { ?>
                                <?php if ($tempsheets_status == 0 && $pendingsyncrecords == 0) { ?>
                                    <button type="button" class="btn btn-warning" disabled><i class="fal fa-ban"></i>&nbsp;No Sheets to synch. </button>
                                <?php } elseif ($tempsheets_status != 0 && ($pendingsyncrecords == 0 || $pendingsyncrecords != 0)) { ?>
                                    <button type="button" class="btn btn-warning" disabled><i class="fal fa-exclamation-circle"></i>&nbsp;Save the sheets to synch </button>
                                <?php } elseif ($pendingsyncrecords != 0 && $tempsheets_status == 0) { ?>
                                    <button onclick="location.href='<?php echo base_url(); ?>sync_stocks'" type="button" data-backdrop="static" class="btn btn-success pull-left"><i class="fal fa-sync"></i>&nbsp;Sync Sheets</button>
                                <?php } ?>
                            <?php } else { ?>
                                <button type="button" class="btn btn-warning" disabled><i class="fal fa-exclamation-circle"></i>&nbsp;No stock Take in Progress. </button>
                            <?php } ?>


                        </div>
                        <?php
                        if ($tempsheets_status != 0 && ($pendingsyncrecords == 0 || $pendingsyncrecords != 0)) { ?>
                            <div class="col-xs-6">
                                <h3 class="box-title" style="font-weight: bolder;font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">Pending Bins</h3>
                            </div>
                        <?php } else { ?>
                            <div class="col-xs-6">
                                <div class="col-xs-4">
                                    <form role="form" action="<?php echo base_url() ?>specific_feed" method="post">
                                        <div class="row">
                                            <!-- Department  -->
                                            <div class="input-group">
                                                <input type="text" name="LookupCode" id="lookupcode" autocomplete="off" required="" class="form-control" placeholder="ItemLookupCode" />
                                            </div><!-- /.input group -->
                                        </div>
                                </div>
                                </form>
                                <div class="col-xs-2">
                                    <h4 class="text-black"><strong>VALUE:&nbsp;<?php echo number_format($total, 2); ?></strong></h4>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-xs-4">
                            <a class="btn btn-success pull-right" href="<?php echo base_url() ?>synchronize"><i class="fal fa-file-excel"></i>&nbsp; Export Excel</a>
                            <a class="btn btn-default pull-right" style="margin-right: 5px;" target="_blank" href="<?php echo base_url() ?>pdf"><i class="fas fa-file-pdf"></i>&nbsp; Export PDF</a>
                        </div>
                    </div><!-- /.box-header -->

                    <?php
                    if ($tempsheets_status != 0 && ($pendingsyncrecords == 0 || $pendingsyncrecords != 0)) { ?>
                        <div class="box-body">
                            <table id="pendindsheets" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>StockTake #</th>
                                        <th>Bin</th>
                                        <th class="text-right">Cost Value</th>
                                        <th class="text-right">Price Value</th>
                                        <th>Data Clerk</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if (!empty($pendings)) {
                                        $i = 1;
                                        foreach ($pendings as $row) {

                                    ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><a href="#" class="btn-skus" data-target="#pending_sheets" data-toggle="modal" user="<?php echo $row->UserID; ?>"><?php echo $row->StocktakeID; ?></a></td>
                                                <td><a href="#" class="btn-skus" data-target="#pending_sheets" data-toggle="modal" user="<?php echo $row->UserID; ?>"><?php echo $row->bin; ?></a></td>
                                                <td class="text-right"><?php echo number_format($row->costvalue, 2); ?></td>
                                                <td class="text-right"><?php echo number_format($row->pricevalue, 2); ?></a></td>
                                                <td><?php echo $row->user; ?></td>


                                            </tr>
                                    <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="box-body">
                            <table id="sheets" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Bin/(Shelf):</th>
                                        <th>Lookup Code</th>
                                        <th>Alias</th>
                                        <th>Description</th>
                                        <th class="text-right">Cost</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Counted Qty</th>
                                        <th class="text-right">Total Cost</th>
                                        <th class="text-right">Total Price</th>
                                        <!-- <th>User</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalcounts = 0;
                                    $totalcost = 0;
                                    $totalprice = 0;
                                    if (!empty($psynchs)) {
                                        /** $i = 1;*/
                                        foreach ($psynchs as $row) {
                                            $totalcounts = $totalcounts + ($row->Quantity);
                                            $totalcost = $totalcost + ($row->Cost * $row->Quantity);
                                            $totalprice = $totalprice + ($row->Price * $row->Quantity);
                                    ?>
                                            <tr>
                                                <td><?php echo $row->bin; ?></td>
                                                <td><?php echo $row->ItemCode; ?></td>
                                                <td><?php echo $row->Alias; ?></td>
                                                <td><?php echo $row->Description; ?></a></td>
                                                <td class="text-right"><?php echo number_format($row->Cost, 2); ?></td>
                                                <td class="text-right"><?php echo number_format($row->Price, 2); ?></td>
                                                <td class="text-right"><?php echo number_format($row->Quantity, 2); ?></td>
                                                <td class="text-right"><?php echo number_format($row->Cost * $row->Quantity, 2); ?></td>
                                                <td class="text-right"><?php echo number_format($row->Price * $row->Quantity, 2); ?></td>
                                                <!-- <td><?php echo $row->Username; ?></a></td> -->
                                                <td>
                                                    <button class="btn btn-xs btn-warning btn-edit" id="<?php echo $row->ID; ?>" itemid="<?php echo $row->ItemID; ?>" itemcode="<?php echo $row->ItemLookupCode; ?>" description="<?php echo $row->Description; ?>" shelf="<?php echo $row->bin; ?>" quantity="<?php echo $row->Quantity; ?>" userid="<?php echo $row->UserID; ?>" cashier="<?php echo $row->Username; ?>"><i class="fal fa-edit"></i>&nbsp;&nbsp; Edit</button>&nbsp;


                                                    <a class="btn btn-xs btn-danger" href="<?php echo base_url() ?>del_sheet_entry/<?php echo $row->ID; ?>"><i class="far fa-trash"></i>&nbsp;&nbsp; Delete</a>
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
                                        <th colspan="6">Totals</th>
                                        <th class="text-right"><?php echo number_format($totalcounts, 2); ?></th>
                                        <th class="text-right"><?php echo number_format($totalcost, 2); ?></th>
                                        <th class="text-right"><?php echo number_format($totalprice, 2); ?></th>
                                        <!-- <th>User</th> -->
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.box-body -->
                    <?php } ?>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!--Item detail modal-->
<div class="modal modal-default" id="details-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-default">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Entry Details</h4>
            </div>
            <div class="form-group col-md-12">
                <form role="form" id="details-form">
                    <input type="hidden" name="action" id="action" value="0" />
                    <div class="form-group">
                        <label>Shelf/Bin Number:</label>
                        <div class="input-group my-colorpicker2">
                            <input type="text" name="bin" id="bin" value="<?php echo $shelf; ?>" autocomplete="off" class="form-control" required readonly />
                            <div class="input-group-addon">
                                <i></i>
                            </div>
                        </div>
                    </div><!-- /.form group -->
                    <div class="row">
                        <!-- Item Code details -->
                        <div class="form-group col-md-6">
                            <label>Item LookupCode:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="item_code" id="item_code" autocomplete="off" class="form-control" placeholder="Code" required readonly />
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <!--Item description -->
                        <div class="bootstrap-timepicker">
                            <div class="form-group col-md-6">
                                <label>Description:</label>
                                <div class="input-group">
                                    <input type="text" name="item_details" id="item_details" autocomplete="off" class="form-control" required readonly />
                                    <div class="input-group-addon">
                                        <i></i>
                                    </div>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                    </div>
                    <!-- shelf/(bin) count -->
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <label>Counted Stock:</label>
                            <div class="input-group">
                                <input type="text" name="quantity" id="quantity" onkeypress="return isNumberKey(event)" autocomplete="off" class="form-control" placeholder="0.00" required />
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div><!-- /.input group -->
                        </div><!-- /.form group -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn btn-outline" data-dismiss="modal"><i class="far fa-times-circle"></i>&nbsp; Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="pending_sheets" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header">
                <h4 class="modal-title">Items</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <table id="pending_details" class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Shelf</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($entries)) {
                                $i = 1;
                                foreach ($entries as $row) {
                                    # code..
                            ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row->tDate; ?></td>
                                        <td><?php echo $row->bin; ?></td>
                                        <td><?php echo $row->ItemLookupCode; ?></td>
                                        <td><?php echo $row->Description; ?></td>
                                        <td><?php echo number_format($row->Quantity, 2); ?></td>
                                    </tr>

                            <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal-content -->
<script type="text/javascript">
    /** Forcing numbers only on quantity */
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    var currentBoxNumber = 0;
    /** Posting updated entry details */
    $("#quantity").keyup(function(event) {
        if (event.keyCode == 13) {
            var url = "<?php echo base_url() ?>updatedetail";
            var data = $("#details-form").serialize();
            $.ajax({
                type: "POST",
                data: data,
                url: url,
                success: function(data) {
                    $("#res").html(data).show().addClass("alert-success");
                    location.reload();
                }
            })
        }
    });

    $(document).ready(function() {
        $("body").on('click', '.btn-edit', function(e) {
            var bin = $(this).attr('bin')
            $("#action").val($(this).attr('ID'));
            $("#bin").val($(this).attr('shelf'));
            $("#item_code").val($(this).attr('itemcode'));
            $("#item_details").val($(this).attr('description'));
            $("#quantity").val($(this).attr('quantity'));

            $("#details-modal").modal("show")
        })

        $("#sheets").DataTable({});
        $("#pendindsheets").DataTable({});
        //$("#pending_details").DataTable({})
    });
    $('.btn-skus').click(function() {
        // alert('Test '+ $(this).attr('entry')).
        var id = $(this).attr('user');
        var url = "<?php echo base_url() ?>products";

        $.ajax({
            data: "UserID=" + id,
            type: "post",
            url: url,
            success: function(data) {
                var result = $.parseJSON(data);
                var table = "";
                var i = 1;

                if (result.entries != null) {
                    $.each(result.entries, function(key, value) {
                        table += "<tr>";
                        table += "<td>" + i + "</td>";
                        table += "<td>" + value.tDate + "</td>";
                        table += "<td>" + value.bin + "</td>";
                        table += "<td>" + value.ItemLookupCode + "</td>";
                        table += "<td>" + value.Description + "</td>";
                        table += "<td>" + value.Quantity + "</td>";
                        table += "</tr>";
                        i++;
                    })
                    console.log(table)
                    $("#pending_details tbody").html(table);
                } else {
                    $("#pending_details tbody").html("");
                    //console.log("No records found");
                }
            }
        })
    });
</script>