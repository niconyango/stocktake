<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header" style="font-family: 'Montserrat',Sans-Serif;">
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
			<div class="col-md-6">
				<!-- TABLE: LATEST ORDERS -->
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="row">
							<div class="col-md-4"
							<h3 style="font: bold">High Count Items</h3>
						</div>
						<div class="col-md-6">
							<h4 class="text-black">
								<strong>VALUE:&nbsp;<?php echo number_format($total, 2); ?></strong>
							</h4>
						</div>
					</div>
					<div class="box-tools float-end">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="display">
							<thead>
							<tr>
								<th>Code</th>
								<th>Description</th>
								<th class="text-end">Quantity</th>
								<th class="text-end">Value</th>
							</tr>
							</thead>
							<tbody>
							<?php
								if (!empty($progress)) {
									// $i = 1;
									foreach ($progress as $row) {
										?>
										<tr>
											<!-- <td><?php echo $i; ?></td> -->
											<td><a><?php echo $row->ItemLookupCode; ?></a></td>
											<td><?php echo $row->Itemdescription; ?></a></td>
											<td class="text-end"><?php echo number_format($row->Quantity); ?></td>
											<td class="text-end"><?php echo number_format($row->Value, 2); ?></td>
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
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
		
		<div class="col-md-6">
			<!-- /.info-box -->
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Departmental Progress</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div>
						<canvas id="departmentsDistributions"></canvas>
					</div>
					<!-- /.row -->
				</div>
				<!-- /.box-body -->
			</div>
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
	$(document).ready(function () {
		$("#display").DataTable({});
	})
	// Register the DataLabels Plugin
	Chart.register(ChartDataLabels);
	
	var ctx = document.getElementById("departmentsDistributions").getContext("2d");
	
	var departmentsDistributions = new Chart(ctx, {
		type: "bar",
		data: {
			labels: [],
			datasets: [{
				label: "Departments Distribution",
				data: [],
				backgroundColor: "rgba(75, 192, 192, 0.6)",
				borderColor: "rgba(75, 192, 192, 1)",
				borderRadius: 5,
				borderSkipped: false,
				borderWidth: 1,
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: {
					display: true
				},
				tooltip: {
					enabled: true
				},
				datalabels: {
					anchor: 'end',
					align: 'top',
					formatter: (value) => {
						return new Intl.NumberFormat('en-US', {
							minimumFractionDigits: 0
						}).format(value); // Format numbers with commas
					},
					font: {
						weight: 'bold'
					},
					color: '#000' // Optional: Change label color
				}
			},
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						callback: function (value) {
							return new Intl.NumberFormat('en-US', {
								minimumFractionDigits: 0
							}).format(value);
						}
					}
				}
			}
		}
	});
	
	var updateDepartmentChart = function () {
		$.ajax({
			url: '<?php echo base_url("sheetsdepartment_status"); ?>',
			type: 'GET',
			dataType: 'json',
			success: function (data) {
				departmentsDistributions.data.labels = data.departments.map(v => v.department);
				departmentsDistributions.data.datasets[0].data = data.departments.map(v => v.value);
				departmentsDistributions.update();
			},
			error: function (data) {
				console.error(data);
			}
		});
	};
	
	// Initial Load
	updateDepartmentChart();
	
	// Auto Refresh Every 5 Seconds
	setInterval(() => {
		updateDepartmentChart();
	}, 5000);

</script>