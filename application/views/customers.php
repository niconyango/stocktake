<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customers
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

                            <h3 class="box-title"></h3>
                        </div>
                        <div class="col-xs-6">
                            <a class="btn btn-success pull-right btn-flat" href="<?php echo base_url() ?>countedexcel"><i class="fa fa-file-excel-o"></i>Export Excel</a>
                            <a class="btn btn-default pull-right btn-flat" style="margin-right: 5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="suppliers" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account Number</th>
                                    <th>Company Name</th>
                                    <th>Tax Number</th>
                                    <th>Address</th>
                                    <th>Location</th>
                                    <th>Email Address</th>
                                    <th>Telephone</th>
                                    <th>Title</th>
                                    <th>Contact Person</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($customers)) {
                                    $i = 1;
                                    foreach ($customers as $row) {

                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row->AccountNumber; ?></td>
                                            <td><?php echo $row->Company; ?></td>
                                            <td><?php echo $row->TaxNumber; ?></td>
                                            <td><?php echo $row->Address; ?></td>
                                            <td><?php echo $row->State; ?></td>
                                            <td><?php echo $row->EmailAddress; ?></td>
                                            <td><?php echo $row->PhoneNumber; ?></td>
                                            <td><?php echo $row->Title; ?></td>
                                            <td><?php echo $row->ContactPerson; ?></td>
                                            <td>
                                                <?php if ($this->session->userdata('SecurityLevel') == 19 || $this->session->userdata('SecurityLevel') == 5) { ?>
                                                    <a class="btn btn-flat btn-xs btn-warning" href="<?php echo base_url() ?>customeredit/<?php echo $row->ID; ?>"><i class="fas fa-user-edit"></i>&nbsp; Edit</a>
                                                <?php } ?>
                                                <?php if ($row->Approved == 1) {
                                                ?>
                                                    <a class="btn btn-flat btn-xs btn-danger" href="<?php echo base_url() ?>deactivate/<?php echo $row->ID; ?>"><i class="fas fa-ban"></i>&nbsp; Block</a>
                                                <?php } else { ?>
                                                    <a class="btn btn-flat btn-xs btn-primary" href="<?php echo base_url() ?>approve/<?php echo $row->ID; ?>"><i class="fas fa-edit"></i>&nbsp; Approve</a>
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