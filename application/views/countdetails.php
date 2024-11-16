<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock Take
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
                        <div class="col-xs-12">
                            <?php
                            if (!empty($info)) {
                                foreach ($info as $row) {
                                    $id = $row->ID;
                            ?>
                                    <a class="btn btn-success pull-right" href="<?php echo base_url(); ?>detailsexcel/<?php echo $id; ?>"><i class="fad fa-file-excel"></i> &nbsp; Export Excel</a>
                                    <a class="btn btn-default pull-right" style="margin-right: 5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fas fa-file-pdf"></i>&nbsp; Print</a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="details" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Code</th>
                                    <th>Alias</th>
                                    <th>Description </th>
                                    <th class="text-right">Cost</th>
                                    <th class="text-right">Available Qty</th>
                                    <th class="text-right">Available Value</th>
                                    <th class="text-right">Counted Qty</th>
                                    <th class="text-right">Counted Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalcounts = 0;
                                $existing = 0;
                                $totale = 0;
                                $totalc = 0;
                                if (!empty($details)) {
                                    //$i = 1;
                                    foreach ($details as $row) {
                                        $totalcounts = $totalcounts + ($row->CountedQty);
                                        $existing = $existing + ($row->OriginalQty);
                                        $totalc = $totalc + ($row->Cost * $row->CountedQty);
                                        $totale = $totale + ($row->Cost * $row->OriginalQty);
                                ?>
                                        <tr>
                                            <td><?php echo $row->department; ?></td>
                                            <td><a href="#" class="btn-items" data-target="#sheets" data-toggle="modal" entry="<?php echo $row->lookup ?>"><?php echo $row->lookup; ?></a></td>
                                            <td><?php echo $row->Alias; ?></a></td>
                                            <td><?php echo $row->Description; ?></a></td>
                                            <td class="text-right"><?php echo number_format($row->Cost, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->OriginalQty, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Cost * $row->OriginalQty, 2) ?></td>
                                            <td class="text-right"><?php echo number_format($row->CountedQty, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Cost * $row->CountedQty, 2) ?></td>
                                        </tr>
                                <?php
                                        // $i++;
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Totals</th>
                                    <th class="text-right"><?php echo number_format($existing, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totale, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totalcounts, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totalc, 2); ?></th>
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
        $("#details").DataTable({})
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