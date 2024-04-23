<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock File
            <small>Import</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>stocks"><i class="fa fa-home"></i> Home</a></li>
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
                        <h3 class="box-title">Import file</h3>
                    </div><!-- /.box-header -->
                    <div class="box-footer">
                        <div class="alert" role="alert" id="error" style="display:none"></div>
                        <!-- <form role="form" id="import-form"> -->
                        <form action="<?php echo base_url(); ?>importData" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" id="action" value="0" />
                            <label>Bins Sheet File:</label>
                            <input type="file" name="uploadFile" value="" /><br><br>
                            <!-- <input type="hidden" name="action" id="action" value="0" /> -->
                            <!-- <label>Item LookupCode:</label>
                            <input type="file" name="sheet" id="sheet" autocomplete="off" required accept=".xls, .xlsx, .csv" /> -->
                            <input type="submit" name="submit" class="btn btn-success btn-flat fas fa-file-upload" value="Upload">
                        </form>
                    </div>
                    <!-- <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-success btn-flat" id="import" data-dismiss="modal" style="float:left"><i class="fas fa-file-upload"></i>&nbsp;&nbsp;Import Sheets</button>
                    </div> -->
                </div><!-- /.box -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->
</div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->
<script>
    $("#import").click(function(e) {
        var url = "<?php echo base_url() ?>importData";
        var data = $("#import-form").serialize();
        $.ajax({
            type: "POST",
            data: data,
            url: url,
            success: function(data) {
                $("#error").html(data).show().addClass("alert-success");

                location.reload();
            }
        })

    })
</script>