<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock Take Progress
            <small>Status</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks'); ?>"><i class="fal fa-home-lg"></i>&nbsp; Home</a></li>
            <li class="active">Report</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2">
                                <?php
                                if ($stocktakestatus != 0) { ?>
                                    <?php if ($tempsheets_status == 0 && $pendingsyncrecords == 0) { ?>
                                        <button type="button" class="btn btn-warning" disabled><i class="fal fa-ban"></i>&nbsp;No Sheets to
                                            synch.
                                        </button>
                                    <?php } elseif ($tempsheets_status != 0 && ($pendingsyncrecords == 0 || $pendingsyncrecords != 0)) { ?>
                                        <button type="button" class="btn btn-warning" disabled><i class="fal fa-exclamation-circle"></i>&nbsp;Save
                                            the sheets to synch
                                        </button>
                                    <?php } elseif ($pendingsyncrecords != 0 && $tempsheets_status == 0) { ?>
                                        <button onclick="location.href='<?php echo base_url('sync_stocks'); ?>'" type="button"
                                                data-bs-backdrop="static" class="btn btn-success pull-left"><i class="fal fa-sync"></i>&nbsp;Sync
                                            Sheets
                                        </button>
                                    <?php } ?>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-warning" disabled><i class="fal fa-exclamation-circle"></i>&nbsp;No
                                        stock Take in Progress.
                                    </button>
                                <?php } ?>
                            </div>
                            <?php
                            if ($tempsheets_status != 0 && ($pendingsyncrecords == 0 || $pendingsyncrecords != 0)) { ?>
                                <div class="col-6">
                                    <h3 class="box-title"
                                        style="font-weight: bolder;font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;">
                                        Pending Bins</h3>
                                </div>
                            <?php } else { ?>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="col-md-12">
                                                <form role="form" method="post">
                                                    <div class="row">
                                                        <!-- item look up  -->
                                                        <div class="form-group col-md-6">
                                                            <input type="text" name="LookupCode" id="lookupcode" autocomplete="off"
                                                                   required=""
                                                                   class="form-control" placeholder="ItemLookupCode"/>
                                                        </div><!-- /.input group -->
                                                        <div class="form-group col-md-6">
                                                            <button type="button" class="btn btn-success btn-search" id="btn-search"><i
                                                                        class="fas
                                                    fa-search"></i>&nbsp;Search
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <h4 class="text-black"><strong>VALUE:&nbsp;<?php echo number_format($total, 2); ?></strong></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-4">
                                <a class="btn btn-success float-end" href="<?php echo base_url('synchronize'); ?>"><i
                                            class="fal fa-file-excel"></i>&nbsp; Export Excel</a>
                                <a class="btn btn-danger float-end" style="margin-right: 5px;" target="_blank"
                                   href="<?php echo base_url('pdf'); ?>"><i class="fas fa-file-pdf"></i>&nbsp; Export PDF</a>
                            </div>
                        </div><!-- /.box-header -->
                    </div>
                </div>
                <?php
                if ($tempsheets_status != 0 && ($pendingsyncrecords == 0 || $pendingsyncrecords != 0)) { ?>
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
                                        <td><a href="#" class="text-decoration-none btn-skus" data-bs-target="#pending_sheets"
                                               data-toggle="modal"
                                               user="<?php echo $row->UserID; ?>"><?php echo $row->StocktakeID; ?></a></td>
                                        <td><a href="#" class="text-decoration-none btn-skus" data-bs-target="#pending_sheets"
                                               data-toggle="modal"
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
                    </div>
                <?php } else { ?>
                    <div class="box-body">
                        <table id="sheets" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Bin/(Shelf):</th>
                                <th>Lookup Code</th>
                                <th>Alias</th>
                                <th>Description</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Counted Qty</th>
                                <th class="text-end">Total Cost</th>
                                <th class="text-end">Total Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7">Totals</th>
                                <th class="text-end"><?php echo number_format($totalcounts, 2); ?></th>
                                <th class="text-end"><?php echo number_format($totalcost, 2); ?></th>
                                <th class="text-end"><?php echo number_format($totalprice, 2); ?></th>
                            </tr>
                            </tfoot>
                        </table>

                    </div><!-- /.box-body -->
                <?php } ?>
            </div><!-- /.box -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!--Item detail modal-->
<div class="modal modal-default" id="details-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Entry Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form role="form" id="details-form">
                    <input type="hidden" name="action" id="action" value="0"/>
                    <div class="mb-3">
                        <label>Shelf/Bin Number:</label>
                        <input type="text" name="bin" id="bin" autocomplete="off" class="form-control"
                               required readonly/>
                    </div><!-- /.form group -->
                    <div class="row">
                        <!-- Item Code details -->
                        <div class="mb-3 col-md-6">
                            <label>Item LookupCode:</label>
                            <input type="text" name="item_code" id="item_code" autocomplete="off" class="form-control"
                                   placeholder="Code" required readonly/>
                        </div><!-- /.input group -->
                        <!--Item description -->
                        <div class="mb-3 col-md-6">
                            <label>Description:</label>
                            <input type="text" name="item_details" id="item_details" autocomplete="off" class="form-control"
                                   required readonly/>
                        </div><!-- /.form group -->
                    </div><!-- /.form group -->
                    <!-- shelf/(bin) count -->
                    <div class="mb-3">
                        <label>Counted Stock:</label>
                        <input type="text" name="quantity" id="quantity" onkeypress="return isNumberKey(event)" autocomplete="off"
                               class="form-control" placeholder="0.00" required/>
                    </div><!-- /.input group -->
                </form>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
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
<script type="text/javascript">
    $(document).ready(function () {
        var userlevel = "<?php echo $this->session->userdata('SecurityLevel');?>"
        $("body").on('click', '.btn-edit', function (e) {
            //var bin = $(this).attr('bin')
            $("#action").val($(this).attr('data-id'));
            $("#bin").val($(this).attr('shelf'));
            $("#item_code").val($(this).attr('itemcode'));
            $("#item_details").val($(this).attr('description'));
            $("#quantity").val($(this).attr('quantity'));

            $("#details-modal").modal("show")
        })
        $("#pendindsheets").DataTable({});

        var table = $("#sheets").DataTable({
            "autoWidth": false,
            "serverSide": true,
            "responsive": true,
            "paging": true,
            "pageLength": 10,
            "deferRender": true,
            "processing": true,
            // Get the selected column for filtering
            "ajax": {
                url: "<?php echo base_url('fetch_entries');?>",
                type: "POST",
                data: function (d) {
                    d.LookupCode = $("#lookupcode").val();
                }
            },
            "columns":
                [
                    {"data": 0, orderable: true, "searchable": true},
                    {"data": 1, orderable: true, "searchable": true},
                    {"data": 2, orderable: true, "searchable": true},
                    {"data": 3, orderable: true, "searchable": true},
                    {"data": 4, orderable: true, "searchable": true},
                    {
                        "data": 5, orderable: true, "searchable": true, render: function (data, type, row) {
                            return new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(data);
                        }
                    },
                    {
                        "data": 6, orderable: true, "searchable": true, render: function (data, type, row) {
                            return new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(data);
                        }
                    },
                    {
                        "data": 7, orderable: true, "searchable": true, render: function (data, type, row) {
                            return new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(data);
                        }
                    },
                    {
                        "data": 8, orderable: true, "searchable": true, render: function (data, type, row) {
                            return new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(data);
                        }
                    },
                    {
                        "data": 9, orderable: true, "searchable": true, render: function (data, type, row) {
                            return new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(data);
                        }
                    },
                    {
                        data: null, // No direct data source
                        render: function (data, type, row) {
                            // Add edit button with row ID
                            let editButton = `<button class="btn btn-sm btn-warning btn-edit" data-id="${row[0]}" shelf="${row[1]}"
                            itemcode="${row[2]}" description="${row[4]}" quantity="${row[7]}"><i class="fa-thin fa-pen-to-square"></i> Edit</button>`;
                            let deleteButton = `<a class="btn btn-sm btn-danger" href="<?php echo base_url() ?>del_sheet_entry/${data[0]}"><i class="far fa-trash"></i>&nbsp;&nbsp; Delete</a>`;
                            let blockedButton = `<button class="btn btn-sm btn-danger btn-edit disabled"><i class="fa-thin fa-ban"></i> Restricted</button>`;
                            if (userlevel == 5 || userlevel == 19) {
                                return editButton + ' ' + deleteButton;
                            } else {
                                return blockedButton;
                            }
                        }
                    }
                ],
            "order":
                [{
                    "column": 0, "dir": "asc"
                }]
        })
        // Trigger table reload on form submission
        $('#btn-search').on('click', function () {
            //table.ajax.draw(); // Redraw the table to apply new filters
            table.draw();
        });
    });

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
    $("#quantity").keyup(function (event) {
        if (event.keyCode == 13) {
            var url = "<?php echo base_url('updatedetail'); ?>";
            var data = $("#details-form").serialize();
            $.ajax({
                type: "POST",
                data: data,
                url: url,
                success: function (data) {
                    $("#res").html(data).show().addClass("alert-success");
                    location.reload();
                }
            })
        }
    });
    $('.btn-skus').click(function () {
        // alert('Test '+ $(this).attr('entry')).
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
    });
</script>