<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Current Stock
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
                                <div class="box-body">
                                    <div class="col-md-12">
                                        <form role="form" method="post">
                                            <div class="row">
                                                <!-- Department  -->
                                                <div class="form-group col-md-3">
                                                    <select name="DepartmentID" id="department" class="form-select">
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
                                                    <select id="category" name="CategoryID" class="form-select">
                                                        <option value="0">Select Category</option>
                                                    </select>
                                                </div>
                                                <!--Sub Category-->
                                                <div class="form-group col-md-3">
                                                    <select id="subcategory" name="SubCategoryID" class="form-select">
                                                        <option value="0">Select Sub Category</option>

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <button type="button" style="border-radius: 5px;"
                                                            class="btn btn-success btn-flat btn-search" id="btn-search"><i class="fas
                                                            fa-search"></i>
                                                        Search
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <a class="btn btn-success float-end" href="<?php echo base_url('excel'); ?>"><i
                                            class="fal fa-file-excel"></i>&nbsp;
                                    Export Excel</a>
                                <a class="btn btn-danger float-end" style="margin-right: 5px;" target="_blank"
                                   href="<?php echo base_url('pdf'); ?>"><i class="fal fa-file-pdf"></i>&nbsp; Export PDF</a>
                            </div>
                        </div><!-- /.box-header -->
                    </div>
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
                                <th class="text-right">Counted Qty</th>
                                <th class="text-right">Counted Value</th>
                                <th class="text-right">Available Qty</th>
                                <th class="text-right">Available Value</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $totalc = 0;
                            $tvalue = 0;
                            $existing = 0;
                            $counted = 0;
                            if (!empty($freeze)) {
                                //$i = 1;
                                foreach ($freeze as $row) {
                                    $totalc = $totalc + ($row->OriginalQty * $row->Cost);
                                    $tvalue = $tvalue + ($row->CountedQty * $row->Cost);
                                    $existing = $existing + ($row->OriginalQty);
                                    $counted = $counted + ($row->CountedQty);
                                    ?>
                                    <tr>
                                        <td><?php echo $row->CountingDate; ?></td>
                                        <td><?php echo $row->itemcode; ?></td>
                                        <td><?php echo $row->Alias; ?></td>
                                        <td><?php echo $row->Description; ?></a></td>
                                        <td class="text-right"><?php echo number_format($row->Cost, 2); ?></td>
                                        <td class="text-right"><?php echo number_format($row->Price, 2); ?></td>
                                        <td class="text-right"><?php echo number_format($row->CountedQty, 2); ?></td>
                                        <td class="text-right"><?php echo number_format($row->CountedQty * $row->Cost, 2); ?></td>
                                        <td class="text-right"><?php echo number_format($row->OriginalQty, 2); ?></td>
                                        <td class="text-right"><?php echo number_format($row->OriginalQty * $row->Cost, 2); ?></td>


                                    </tr>
                                    <?php
                                    // $i++;
                                }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7">Totals</th>
                                <th class="text-right"></th>
                                <th></th>
                                <th class="text-right"></th>
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
        var table = $("#stock").DataTable({
            "autoWidth": false,
            "serverSide": true,
            "responsive": true,
            "paging": true,
            "pageLength": 10,
            "deferRender": true,
            "processing": true,
            // Get the selected column for filtering
            "ajax": {
                url: "<?php echo base_url('fetch_holdings');?>",
                type: "POST",
                data: function (d) {
                    d.DepartmentID = $('#department').val();
                    d.CategoryID = $('#category').val();
                    d.SubCategoryID = $('#subcategory').val();
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
                    }
                ],
            "order":
                [{
                    "column": 0, "dir": "asc"
                }], createdRow: function (row, data, dataIndex) {
                $(row).attr('onclick', `location.href='itemProperties/${data[0]}'`);
                $(row).css('cursor', 'pointer');
            },
            footerCallback: function (row, data, start, end, display) {
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
                    .pluck(9)
                    .reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);

                // Update footer
                $(api.column(7).footer()).html(
                    numberFormatter.format(availableTotal)
                );
                $(api.column(9).footer()).html(
                    numberFormatter.format(countedTotal)
                );
            }
        })
        // Trigger table reload on form submission
        $('#btn-search').on('click', function () {
            //table.ajax.draw(); // Redraw the table to apply new filters
            table.draw();
        });
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