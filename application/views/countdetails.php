<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock Take
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
                        <div class="col-12">
                            <?php
                            if (!empty($info)) {
                                foreach ($info as $row) {
                                    $id = $row->ID;
                                    ?>
                                    <a class="btn btn-success float-end" href="<?php echo base_url(); ?>detailsexcel/<?php echo $id; ?>"><i
                                                class="fad fa-file-excel"></i> &nbsp; Export Excel</a>
                                    <a class="btn btn-danger float-end" style="margin-right: 5px;" target="_blank"
                                       href="<?php echo base_url('pdf'); ?>"><i class="fas fa-file-pdf"></i>&nbsp;Export PDF</a>
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
                                <th>Description</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Available Qty</th>
                                <th class="text-end">Available Value</th>
                                <th class="text-end">Counted Qty</th>
                                <th class="text-end">Counted Value</th>
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
                                        <td><a class="text-decoration-none" href="#" class="btn-items" data-bs-target="#sheets"
                                               data-bs-toggle="modal"
                                               entry="<?php echo $row->lookup ?>"><?php echo $row->lookup; ?></a></td>
                                        <td><?php echo $row->Alias; ?></a></td>
                                        <td><?php echo $row->Description; ?></a></td>
                                        <td class="text-end"><?php echo number_format($row->Cost, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->OriginalQty, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->Cost * $row->OriginalQty, 2) ?></td>
                                        <td class="text-end"><?php echo number_format($row->CountedQty, 2); ?></td>
                                        <td class="text-end"><?php echo number_format($row->Cost * $row->CountedQty, 2) ?></td>
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
                                <th class="text-end"></th>
                                <th class="text-end"></th>
                                <th class="text-end"></th>
                                <th class="text-end"></th>
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
        var id = "<?php echo $id;?>"
        var table = $("#details").DataTable({
            "autoWidth": false,
            "serverSide": true,
            "responsive": true,
            "paging": true,
            "pageLength": 10,
            "deferRender": true,
            "processing": true,
            // Get the selected column for filtering
            "ajax": {
                url: "<?php echo base_url();?>fetch_details/" + id,
                type: "POST"
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
                    }
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
                    .pluck(6)
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
                $(api.column(6).footer()).html(
                    numberFormatter.format(availableTotal)
                );
                $(api.column(8).footer()).html(
                    numberFormatter.format(countedTotal)
                );
            }
        })
    })
    $('#department').change(function () {
        $("#category").html("<option value='0'>Select Category</option>")
        var department = $(this).val()
        $.ajax({
            type: 'post',
            url: '<?php echo base_url("get_categories_department");?>',
            data: 'departmentid=' + department,
            success: function (data) {
                var json = $.parseJSON(data)
                $.each(json.Category, function (index, value) {
                    var nicholas = '<option value="' + value.ID + '">' + value.Name + '</option>';

                    $("#category").append(nicholas)
                })
            }
        })
    })
    $('#category').change(function () {
        $("#subcategory").html("<option value='0'>Select SubCategory</option>")

        var category = $(this).val()
        $.ajax({
            type: 'post',
            url: '<?php echo base_url("get_subcategories_department");?>',
            data: 'categoryid=' + category,
            success: function (data) {
                var json = $.parseJSON(data)
                $.each(json.SubCategory, function (index, value) {
                    var nicholas = '<option value="' + value.ID + '">' + value.Name + '</option>';

                    $("#subcategory").append(nicholas)
                })
            }
        })
    })
</script>