<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Bin/(Shelf)
            <small>Report</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Report</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-8">
                            </div>
                            <div class="col-4">
                                <a class="btn btn-success float-end" href="<?php echo base_url('binexcel'); ?>"><i class="fad
                            fa-file-excel"></i>&nbsp; Export Excel</a>
                                <a class="btn btn-danger float-end" style="margin-right: 5px;" target="_blank"
                                   href="<?php echo base_url('pdf');
                                   ?>"><i class="fal fa-file-pdf"></i>&nbsp; Export PDF</a>
                            </div>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="sheets" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Stock Date</th>
                                <th>Bin/(Shelf):</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Counted</th>
                                <th class="text-end">Total Cost</th>
                                <th class="text-end">Total Price</th>
                                <th>User</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $totalcounts = 0;
                            $totalcost = 0;
                            $totalprice = 0;
                            if (!empty($sheets)) {
                                //$i = 1;
                                foreach ($sheets as $row) {
                                    $totalcounts = $totalcounts + ($row->Quantity);
                                    $totalcost = $totalcost + ($row->Cost * $row->Quantity);
                                    $totalprice = $totalprice + ($row->Price * $row->Quantity);
                                    ?>
                                    <tr>
                                        <td><?php echo $row->fedtime; ?></td>
                                        <td><?php echo $row->bin; ?></td>
                                        <td><?php echo $row->itemcode; ?></td>
                                        <td><?php echo $row->Description; ?></a></td>
                                        <td class="text-end"><?php echo number_format($row->Cost, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->Price, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->Quantity, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->Cost * $row->Quantity, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->Price * $row->Quantity, 2); ?></td>
                                        <td><?php echo $row->username; ?></a></td>
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
                                <th class="text-end"></th>
                                <th class="text-end"></th>
                                <th class="text-end"></th>
                                <th>User</th>
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
    $(document).ready(function () {
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
                url: "<?php echo base_url('fetch_sheets');?>",
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
                    {"data": 9, orderable: true, "searchable": true}
                ],
            "order":
                [{
                    "column": 0, "dir": "asc"
                }], footerCallback: function (row, data, start, end, display) {
                // Calculate total cost
                var api = this.api();
                // currency formating.
                var numberFormatter = new Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                // // Total over all pages
                var availableTotal = api
                    .rows({search: 'applied'}) // Only rows matching the filter
                    .data()
                    .pluck(7)
                    .reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);

                var countedTotal = api
                    .rows({search: 'applied'}) // Only rows matching the filter
                    .data()
                    .pluck(8)
                    .reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);

                // Update footer
                $(api.column(7).footer()).html(
                    numberFormatter.format(availableTotal)
                );
                $(api.column(8).footer()).html(
                    numberFormatter.format(countedTotal)
                );
            }
        })
    });
</script>