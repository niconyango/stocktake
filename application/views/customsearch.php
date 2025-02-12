<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Current Stock
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
                                    <form role="form" action="<?php echo base_url() ?>customsearch" method="post">
                                        <div class="row">
                                            <!-- Department  -->
                                            <div class="form-group col-md-4">
                                                <select name="DepartmentID" id="department" class="form-control" required="">
                                                    <option value="0">Select Department</option>
                                                    <?php
                                                    if (!empty($departments)) {
                                                        foreach ($departments as $nicholas) {
                                                    ?>
                                                            <option value="<?php echo $nicholas->ID; ?>"><?php echo $nicholas->Name; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!--Category -->
                                            <div class="form-group col-md-3">
                                                <select id="category" name="CategoryID" class="form-control">
                                                    <option value="0">Select Category</option>
                                                </select>
                                            </div>
                                            <!--Sub Category-->
                                            <div class="form-group col-md-4">
                                                <select id="subcategory" name="SubCategoryID" class="form-control">
                                                    <option value="0">Select Sub Category</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <button type="submit" class="btn btn-success btn-search"><i class="fas fa-search"></i>&nbsp;Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <h3 class="box-title"></h3>
                        </div>
                        <div class="col-xs-6">
                            <a class="btn btn-success pull-right" href="<?php echo base_url(); ?>customexcel"><i class="fad fa-file-excel"></i>&nbsp;Export Excel</a>
                            <a class="btn btn-default pull-right" style="margin-right: 5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fa fa-print"></i>&nbsp;Print</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="stock" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Stock Date</th>
                                    <th>Code</th>
                                    <th>Alias</th>
                                    <th>Description</th>
                                    <th class="text-right">Cost</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Available Qty</th>
                                    <th class="text-right">Counted Qty</th>
                                    <th class="text-right">Counted Value</th>
                                    <th class="text-right">Cost Value</th>
                                    <th class="text-right">Price Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pvalue = 0;
                                $cvalue = 0;
                                $existing = 0;
                                $counted = 0;
                                $counted_value = 0;
                                if (!empty($skus)) {
                                    //$i = 1;
                                    foreach ($skus as $row) {
                                        $cvalue = $cvalue + ($row->OriginalQty * $row->Cost);
                                        $pvalue = $pvalue + ($row->OriginalQty * $row->Price);
                                        $existing = $existing + ($row->OriginalQty);
                                        $counted = $counted + ($row->CountedQty);
                                        $counted_value = $counted_value + ($row->CountedQty * $row->Cost);
                                ?>
                                        <tr>
                                            <td><?php echo $row->CountingDate; ?></td>
                                            <td><?php echo $row->itemcode; ?></td>
                                            <td><?php echo $row->Alias; ?></td>
                                            <td><?php echo $row->Description; ?></a></td>
                                            <td class="text-right"><?php echo number_format($row->Cost, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Price, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->OriginalQty, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->CountedQty, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->CountedQty * $row->Cost, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->OriginalQty * $row->Cost, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->OriginalQty * $row->Price, 2); ?></td>
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
                                    <th class="text-right"><?php echo number_format($existing, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($counted, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($counted_value, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($cvalue, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($pvalue, 2); ?></th>
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
    })
    $('#department').change(function() {
        $("#category").html("<option value='0'>Select Category</option>")
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
        $("#subcategory").html("<option value='0'>Select SubCategory</option>")
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