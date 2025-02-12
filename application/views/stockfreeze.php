<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Branch Stock
            <small>Take</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('stocks'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Stock Take</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Freeze Stocks</h3>
                    </div><!-- /.box-header -->
                    <div class="box-footer justify-content-between">
                        <?php
                        if ($stocktakestatus == 0) { ?>
                            <button onclick="location.href='<?php echo base_url("freeze"); ?>'" type="button" class="btn btn-info"><i class="far fa-play-circle"></i>&nbsp;Freeze Stocks</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-info" disabled><i class="fas fa-exclamation-circle"></i>&nbsp;Stock Take in Progress</button>
                            <?php
                            if ($stocktakeprogress == 0) { ?>
                                <button onclick="location.href='<?php echo base_url("undofreeze"); ?>'" type="button" class="btn btn-danger" data-dismiss="modal" style="float:right"><i class="fas fa-undo"></i>&nbsp;Undo Freeze</button>
                            <?php  } else { ?>
                                <button type="button" class="btn btn-success" style="float:right" disabled><i class="far fa-smile-wink"></i>&nbsp;Happy stock Taking</button>
                            <?php } ?>
                        <?php } ?>


                    </div><!-- /.box -->

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->