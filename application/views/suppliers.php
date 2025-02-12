<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Suppliers
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks'); ?>"><i class="fa fa-home"></i> Home</a></li>
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

                            <h3 class="box-title"></h3>
                        </div>
                        <div class="col-xs-6">
                            <a class="btn btn-success pull-right btn-xs btn-flat" href="<?php echo base_url() ?>countedexcel"><i class="fa fa-file-excel-o"></i>Excel</a>
                            <a class="btn btn-default pull-right btn-xs btn-flat" style="margin-right: 5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="suppliers" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Supplier Name</th>
                                    <th>Tax Number</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Email Address</th>
                                    <th>Telephone</th>
                                    <th>Contact Person</th>
                                    <th>Supplying</th>
                                    <th>Type Of Goods</th>
                                    <th>Withholding?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($suppliers)) {
                                    $i = 1;
                                    foreach ($suppliers as $row) {

                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row->Code; ?></td>
                                            <td><?php echo $row->SupplierName; ?></td>
                                            <td><?php echo $row->TaxNumber; ?></td>
                                            <td><?php echo $row->Address1; ?></td>
                                            <td><?php echo $row->City; ?></td>
                                            <td><?php echo $row->EmailAddress; ?></td>
                                            <td><?php echo $row->PhoneNumber; ?></td>
                                            <td><?php echo $row->ContactName; ?></td>
                                            <td><?php
                                                if ($row->Supplying == 0) {
                                                ?>
                                                    <a class="btn btn-flat btn-xs btn-success" href="#"><i class="fas fa-shipping-fast"></i>&nbsp;&nbsp;Goods
                                                    </a>
                                                <?php } else { ?>
                                                    <a class="btn btn-flat btn-xs btn-primary" href="#"><i class="fas fa-clipboard-check"></i> Services</a>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row->TypeofGoods; ?></td>
                                            <td>
                                                <?php
                                                if ($row->Withhold == 1) {
                                                ?>
                                                    <a class="btn btn-flat btn-xs btn-success" href="#"><i class="far fa-check-circle"></i>&nbsp;&nbsp; YES
                                                    </a>
                                                <?php } else { ?>
                                                    <a class="btn btn-flat btn-xs btn-warning" href="#"><i class="fas fa-ban"></i>&nbsp;&nbsp; NO</a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($row->Approved == 1) {
                                                ?>
                                                    <a class="btn btn-flat btn-xs btn-danger" href="<?php echo base_url() ?>deactivate/<?php echo $row->ID; ?>"><i class="fas fa-ban"></i> Block</a>
                                                <?php } else { ?>
                                                    <a class="btn btn-flat btn-xs btn-primary" href="<?php echo base_url() ?>approve/<?php echo $row->ID; ?>"><i class="fas fa-edit"></i> Approve</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function() {

        $("#suppliers").DataTable({});

    })
</script>