<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Departments
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
                            <a class="btn btn-success pull-right btn-xs btn-flat" href="<?php echo base_url() ?>countedexcel"><i class="fa fa-file-excel-o"></i>Excel</a>
                            <a class="btn btn-default pull-right btn-xs btn-flat" style="margin-right: 5px;" target="_blank" href="<?php echo base_url(); ?>pdf"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="departments" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Dept Code</th>
                                    <th>Name</th>
                                    <th>Margin</th>
                                    <th>Commsission</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($departments)) {
                                    $i = 1;
                                    foreach ($departments as $row) {

                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row->Code; ?></td>
                                            <td><?php echo $row->Name; ?></td>
                                            <td><?php echo $row->pMargin; ?></td>
                                            <td><?php echo $row->pComm; ?></td>
                                            <td></td>
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

        $("#departments").DataTable({});

    })
</script>