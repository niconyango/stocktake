<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            StockTake
            <small>Posting</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks'); ?>"><i class="fal fa-home-lg"></i>&nbsp; Home</a></li>
            <li class="active">Stock Take</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pending Bins</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="pendindsheets" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>StockTake #</th>
                                <th>Bin</th>
                                <th class="text-end">Cost Value</th>
                                <th class="text-end">Price Value</th>
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
                                        <td><a class="text-decoration-none" href="#" class="btn-skus" data-bs-target="#pending_sheets"
                                               data-bs-toggle="modal"
                                               user="<?php echo $row->UserID; ?>"><?php echo $row->StocktakeID; ?></a></td>
                                        <td><a class="text-decoration-none" href="#" class="btn-skus" data-bs-target="#pending_sheets"
                                               data-bs-toggle="modal"
                                               user="<?php echo $row->UserID; ?>"><?php echo $row->bin; ?></a></td>
                                        <td class="text-end"><?php echo number_format($row->costvalue, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->pricevalue, 2); ?></a></td>
                                        <td><?php echo $row->user; ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <?php
                        if ($tempsheets_status == 0) { ?>
                            <?php if ($stocktakestatus == 1 && $pendingsyncrecords == 0) { ?>
                                <!-- <button onclick="location.href='<?php echo base_url(); ?>post_stocks'" type="button" class="btn btn-danger btn-flat"><i class="far fa-hdd"></i>&nbsp;Post Stock Take</button> -->
                                <button class="btn btn-flat btn-danger" style="border-radius: 5px;" data-bs-target="#stockpost-modal"
                                        data-bs-toggle="modal">
                                    <i class="far fa-hdd"></i>&nbsp;&nbsp;&nbsp;Post Stock Take.
                                </button>
                            <?php } elseif ($stocktakestatus == 0 && $pendingsyncrecords == 0) { ?>
                                <button type="button" class="btn btn-info btn-flat" style="border-radius: 5px;" disabled><i
                                            class="far fa-times-circle"></i>&nbsp;No active stake.
                                </button>
                            <?php } elseif ($stocktakestatus == 1 && $pendingsyncrecords != 0) { ?>
                                <button type="button" class="btn btn-info btn-flat" style="border-radius: 5px;" disabled><i
                                            class="far fa-times-circle"></i>&nbsp;Synch pending sheets to continue.
                                </button>
                            <?php } ?>
                        <?php } else { ?>
                            <button type="button" class="btn btn-info btn-flat" style="border-radius: 5px;" disabled><i
                                        class="far fa-times-circle"></i>&nbsp;Save Pending sheets to continue.
                            </button>
                        <?php } ?>
                    </div><!-- /.box -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </section><!-- /.content -->
</div><!-- /.row -->
<div class="modal modal-default" id="stockpost-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header">
                <h4 class="modal-title">Posting Options</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="form-group col-md-12">
                <h4 style="text-align:center">
                    Do you want to set items not counted to zero?
                </h4>
            </div>
            <div class="modal-footer">
                <button onclick="location.href='<?php echo base_url("post_counted"); ?>'" type="button" class="btn pull-left"><i
                            class="far fa-exclamation-circle"></i>&nbsp;&nbsp; NO
                </button>
                <button onclick="location.href='<?php echo base_url("post_stocks"); ?>'" type="button" class="btn"><i
                            class="fal fa-check-circle"></i>&nbsp; YES
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="pending_sheets" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Items</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
                        <!-- <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Shelf</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Quantity</th>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.btn-skus').click(function () {
            /** alert('Test '+ $(this).attr('entry')) */
            var id = $(this).attr('user');
            var url = "<?php echo base_url('products'); ?>";

            $.ajax({
                data: "UserID=" + id,
                type: "post",
                url: url,
                success: function (data) {
                    var result = $.parseJSON(data);


                    var table = "";
                    var i = 1;

                    if (result.entries != null) {
                        $.each(result.entries, function (key, value) {

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
        })

        $("#pendindsheets").DataTable({})
        $("#pending_details").DataTable({})
    });
</script>