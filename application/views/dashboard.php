  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
              Dashboard
              <small>Version 1.0</small>
          </h1>
          <ol class="breadcrumb">
              <li><a href="#"><i class="fal fa-home"></i> Home</a></li>
              <li class="active">Dashboard</li>
          </ol>
      </section>

      <!-- Main content -->
      <section class="content">

          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
              <!-- Left col -->
              <div class="col-md-8">
                  <!-- TABLE: LATEST ORDERS -->
                  <div class="box box-warning">
                      <div class="box-header with-border">
                          <h3 class="box-title">High Count Items</h3>

                          <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                          </div>
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                          <div class="table-responsive">
                              <table class="table no-margin">
                                  <thead>
                                      <tr>
                                          <th>Code</th>
                                          <th>Description</th>
                                          <th class="text-right">Quantity</th>
                                          <th class="text-right">Value</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php

                                        use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

                                        if (!empty($progress)) {
                                            // $i = 1;
                                            foreach ($progress as $row) {
                                        ?>
                                              <tr>
                                                  <!-- <td><?php echo $i; ?></td> -->
                                                  <td><a><?php echo $row->ItemLookupCode; ?></a></td>
                                                  <td><?php echo $row->Itemdescription; ?></a></td>
                                                  <td class="text-right"><?php echo number_format($row->Quantity, 2); ?></td>
                                                  <td class="text-right"><?php echo number_format($row->Value, 2); ?></td>

                                              </tr>
                                      <?php
                                                // $i++;
                                            }
                                        }
                                        ?>
                                  </tbody>
                              </table>
                          </div>
                          <!-- /.table-responsive -->
                      </div>
                      <!-- /.box-body -->
                      <!-- <div class="box-footer clearfix">
                          <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
                          <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
                      </div> -->
                      <!-- /.box-footer -->
                  </div>
                  <!-- /.box -->
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                  <!-- /.info-box -->
                  <div class="box box-success">
                      <div class="box-header with-border">
                          <h3 class="box-title">Departmental Distribution</h3>

                          <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                          </div>
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                          <div class="row">
                              <div class="col-md-12">
                                  <div id="pieChart" height="200"></div>
                              </div>
                          </div>
                          <!-- /.row -->
                      </div>
                      <!-- /.box-body -->
                  </div>
                  <!-- /.box -->

                  <!-- /.box -->
              </div>
              <!-- /.col -->
          </div>
          <!-- /.row -->
      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="application/javascript">
      $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>sheetsdepartment_status",
          //data: dataString,
          dataType: "json",
          beforeSend: function() {

          },
          success: function(data) {

              Morris.Donut({
                  element: 'pieChart',
                  data: data,
                  formatter: function(x) {
                      return x + "%"
                  }
              }).on('click', function(i, row) {
                  console.log(i, row);
              });

          },
          error: function(data) {
              console.log(data);
          }

      });
  </script>