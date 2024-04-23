<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Bin/(Shelf)
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
                            <h3 class="box-title"></h3>
                        </div>
                        <div class="col-xs-6">
                            <a class="btn btn-success pull-right btn-xs" href="<?php echo base_url() ?>binexcel"><i class="fad fa-file-excel"></i>&nbsp; Excel</a>
                            <a class="btn btn-default pull-right btn-xs" style="margin-right: 5px;" target="_blank" href="<?php echo base_url() ?>pdf"><i class="fal fa-file-pdf"></i>&nbsp; Pdf</a>
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
                                    <th class="text-right">Cost</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Counted </th>
                                    <th class="text-right">Total Cost</th>
                                    <th class="text-right">Total Price</th>
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
                                            <td class="text-right"><?php echo number_format($row->Cost, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Price, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Quantity, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Cost * $row->Quantity, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Price * $row->Quantity, 2); ?></td>
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
                                    <th class="text-right"><?php echo number_format($totalcounts, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totalcost, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totalprice, 2); ?></th>
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
    $(document).ready(function() {
        $("#sheets").DataTable({})
    });
</script>