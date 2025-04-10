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
	<style>
        .text-end {
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
                                                            fa-search"></i>Search
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="col-4">
								<a class="btn btn-success float-end" href="<?php echo base_url('excel'); ?>"><i
											class="fa-thin fa-file-xls"></i>&nbsp; Export Excel</a>
								<a class="btn btn-danger float-end" style="margin-right: 5px;" target="_blank"
								   href="<?php echo base_url('pdf'); ?>"><i class="fa-thin fa-file-pdf"></i>&nbsp; Export PDF</a>
							</div>
						</div><!-- /.box-header -->
					</div>
					<div class="box-body">
						<table id="stock" class="display">
							<thead>
							<tr>
								<th>Stock Date</th>
								<th>Code</th>
								<th>Alias</th>
								<th>Description</th>
								<th class="text-end">Cost</th>
								<th class="text-end">Price</th>
								<th class="text-end">Counted Qty</th>
								<th class="text-end">Counted Value</th>
								<th class="text-end">Available Qty</th>
								<th class="text-end">Available Value</th>
							</tr>
							</thead>
							<tbody>
							
							</tbody>
							<tfoot>
							<tr>
								<th colspan="7">Totals</th>
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
					{"data": 4, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 5, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 6, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 7, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 8, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"},
					{"data": 9, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-end"}
				], "order": [0, "asc"],
			footerCallback: function (row, data, start, end, display) {
				var api = this.api();
				// Column indexes to sum
				var columnIndexes = [7, 9];
				
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