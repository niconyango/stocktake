<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Items
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>stocks"><i class="fa fa-home"></i> Home</a></li>
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
                                    <form role="form" action="<?php echo base_url() ?>category_search" method="post">
                                        <div class="row">
                                            <!-- Department  -->
                                            <div class="form-group col-md-4">
                                                <select name="DepartmentID" id="department" class="form-control">
                                                    <option value="DepartmentID">Select Department</option>
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
                                                    <option value="">Select Category</option>
                                                </select>
                                            </div>
                                            <!--Sub Category-->
                                            <div class="form-group col-md-4">
                                                <select id="subcategory" name="SubCategoryID" class="form-control">
                                                    <option value="">Select Sub Category</option>

                                                </select>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <button type="submit" class="btn btn-success btn-flat btn-search"><i class="fas fa-microphone"></i>&nbsp;Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <h3 class="box-title"></h3>
                        </div>
                        <div class="col-xs-6">
                            <a class="btn btn-success pull-right btn-xs btn-flat" href="<?php echo base_url() ?>countedexcel"><i class="fa fa-file-excel-o"></i>Excel</a>
                            <a class="btn btn-default pull-right btn-xs btn-flat" style="margin-right: 5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="stock" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Department</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Code</th>
                                    <th>Alias</th>
                                    <th>Description</th>
                                    <th class="text-right">Cost</th>
                                    <th class="text-right">Available Qty</th>
                                    <th class="text-right">Value(:At Cost)</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Value(:At Price)</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalavailable = 0;
                                $totalcounts = 0;
                                $totala = 0;
                                $totalc = 0;
                                if (!empty($items)) {
                                    $i = 1;
                                    foreach ($items as $row) {
                                        $totalavailable = $totalavailable + ($row->OriginalQty);
                                        $totalcounts = $totalcounts + ($row->CountedQty);
                                        $totala = $totala + ($row->OriginalQty * $row->Cost);
                                        $totalc = $totalc + ($row->CountedQty * $row->Cost);
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row->Department; ?></td>
                                            <td><?php echo $row->Category; ?></td>
                                            <td><?php echo $row->SubCategory; ?></td>
                                            <td><a><?php echo $row->Code; ?></a></td>
                                            <td><a><?php echo $row->Alias; ?></a></td>
                                            <td><a href="#" entry="<?php echo $row->ID ?>"><?php echo $row->Description; ?></a></td>
                                            <td class="text-right"><?php echo number_format($row->Cost, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->quantity, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->quantity * $row->Cost, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->Price, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row->quantity * $row->Price, 2); ?></td>

                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th colspan="5">Totals</th>
                                    <th class="text-right"><?php echo number_format($totalavailable, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totala, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totalcounts, 2); ?></th>
                                    <th class="text-right"><?php echo number_format($totalc, 2); ?></th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!--stocks capture modal-->
<div class="modal fade" id="sheets" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Specific Item Counts</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <table id="details" class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Shelf</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Recorded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($product)) {
                                $i = 1;
                                foreach ($product as $row) {
                                    # code..
                            ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row->tDate; ?></td>
                                        <td><?php echo $row->bin; ?></td>
                                        <td><?php echo $row->ItemLookupCode; ?></td>
                                        <td><?php echo $row->Description; ?></td>
                                        <td><?php echo number_format($row->Quantity, 2); ?></td>
                                        <td><?php echo $row->Username; ?></td>
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
                                <th>Date</th>
                                <th>Shelf</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Recorded By</th>
                            </tr>
                        </tfoot>
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
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
    $(document).ready(function() {

        $("#stock").DataTable({});
        $("#details").DataTable({});
        $('#sDate').datepicker({
            autoclose: true
        })
        $('#eDate').datepicker({
            autoclose: true
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
    });
</script>