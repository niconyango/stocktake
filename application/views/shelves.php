<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Bin/(Shelf)
			<small>Report</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('stocks'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Report</li>
		</ol>
	</section>
	<style>
        .text-right {
            text-align: right;
        }
	</style>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-12">
				<div class="box box-default">
					<div class="box-header with-border">
						<div class="row">
							<div class="col-8">
							</div>
							<div class="col-4">
								<a class="btn btn-success float-end" href="<?php echo base_url('binexcel'); ?>"><i
											class="fa-thin fa-file-xls"></i>&nbsp; Export Excel</a>
								<a class="btn btn-danger float-end" style="margin-right: 5px;" target="_blank"
								   href="<?php echo base_url('pdf'); ?>"><i class="fa-thin fa-file-pdf"></i>&nbsp; Export PDF</a>
							</div>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">
						<table id="sheets" class="display">
							<thead>
							<tr>
								<th>Stock Date</th>
								<th>Bin/(Shelf):</th>
								<th>Code</th>
								<th>Description</th>
								<th class="text-end">Cost</th>
								<th class="text-end">Price</th>
								<th class="text-end">Counted</th>
								<th class="text-end">Total Cost</th>
								<th class="text-end">Total Price</th>
								<th>User</th>
							</tr>
							</thead>
							<tbody>
							
							</tbody>
							<tfoot>
							<tr>
								<th colspan="6">Totals</th>
								<th class="text-end"></th>
								<th class="text-end"></th>
								<th class="text-end"></th>
								<th>User</th>
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
		var table = $("#sheets").DataTable({
			"autoWidth": false,
			"serverSide": true,
			"responsive": true,
			"paging": true,
			"pageLength": 10,
			"deferRender": true,
			"processing": true,
			// Get the selected column for filtering
			"ajax": {
				url: "<?php echo base_url('fetch_sheets');?>",
				type: "POST",
				data: function (d) {
					d.LookupCode = $("#lookupcode").val();
				}
			},
			"columns":
				[
					{"data": 0, orderable: true, "searchable": true},
					{"data": 1, orderable: true, "searchable": true},
					{"data": 2, orderable: true, "searchable": true},
					{"data": 3, orderable: true, "searchable": true},
					{"data": 4, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 5, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 6, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 7, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 8, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 9, orderable: true, "searchable": true}
				],
			"order": [0, "asc"],
			footerCallback: function (row, data, start, end, display) {
				var api = this.api();
				// Column indexes to sum
				var columnIndexes = [7, 8];
				
				columnIndexes.forEach(function (colIndex) {
					// Use column().data() without {search: 'applied'} to get total data, not just the visible rows
					var sum = api.column(colIndex).data().reduce(sumReducer, 0);
					$(api.column(colIndex).footer()).html(formatCurrency(sum));
				});
			}
		})
		
		function formatCurrency(data) {
			return new Intl.NumberFormat('en-US', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			}).format(data || 0);
		}
		
		function sumReducer(a, b) {
			return parseFloat(a || 0) + parseFloat(b || 0);
		}
	});
</script>