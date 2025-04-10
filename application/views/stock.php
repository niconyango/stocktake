<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Counted Items
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
													<button type="button" class="btn btn-success btn-search" id="btn-search"><i class="fas
                                                    fa-search"></i>&nbsp;Search
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="col-4">
								<a class="btn btn-success float-end" href="<?php echo base_url('countedexcel'); ?>"><i
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
								<th>#</th>
								<th>Date</th>
								<th>Code</th>
								<th>Alias</th>
								<th>Description</th>
								<th class="text-end">Cost</th>
								<th class="text-end">Available Qty</th>
								<th class="text-end">Available Value</th>
								<th class="text-end">Counted Qty</th>
								<th class="text-end">Counted Value</th>
							
							</tr>
							</thead>
							<tbody>
							<?php
								$totalavailable = 0;
								$totalcounts = 0;
								$totala = 0;
								$totalc = 0;
								if (!empty($products)) {
									//$i = 1;
									foreach ($products as $row) {
										$totalavailable = $totalavailable + ($row->OriginalQty);
										$totalcounts = $totalcounts + ($row->CountedQty);
										$totala = $totala + ($row->OriginalQty * $row->Cost);
										$totalc = $totalc + ($row->CountedQty * $row->Cost);
										?>
										<tr>
											<td><?php echo $row->CountedDate; ?></td>
											<td><?php echo $row->Code; ?></td>
											<td><?php echo $row->Alias; ?></td>
											<td><a href="#" class="btn-items text-decoration-none" data-bs-target="#sheets"
											       data-bs-toggle="modal" entry="<?php
													echo $row->ItemID ?>"><?php echo $row->description; ?></a></td>
											<td class="text-end"><?php echo number_format($row->Cost, 2); ?></td>
											<td class="text-end"><?php echo number_format($row->OriginalQty, 2); ?></td>
											<td class="text-end"><?php echo number_format($row->OriginalQty * $row->Cost, 2); ?></td>
											<td class="text-end"><?php echo number_format($row->CountedQty, 2); ?></td>
											<td class="text-end"><?php echo number_format($row->CountedQty * $row->Cost, 2); ?></td>
										
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
								<th class="text-end"></th>
								<th></th>
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
<!--stocks capture modal-->
<div class="modal fade" id="sheets" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="border-radius: 10px;">
			<div class="modal-header">
				<h4 class="modal-title">Specific Item Counts</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<table id="details" class="display">
						<thead>
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Shelf</th>
							<th>Code</th>
							<th>Description</th>
							<th>Quantity</th>
							<th>Recorded By</th>
						</tr>
						</thead>
						<tbody>
						<?php
							if (!empty($product)) {
								$i = 1;
								foreach ($product as $row) {
									# code..
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $row->tDate; ?></td>
										<td><?php echo $row->bin; ?></td>
										<td><?php echo $row->ItemLookupCode; ?></td>
										<td><?php echo $row->Description; ?></td>
										<td><?php echo number_format($row->Quantity, 2); ?></td>
										<td><?php echo $row->Username; ?></td>
									</tr>
									
									<?php
									$i++;
								}
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
	$(document).ready(function () {
		$('.btn-items').click(function () {
			/** alert('Test '+ $(this).attr('entry')) */
			
			var id = $(this).attr('entry');
			var url = "<?php echo base_url('product'); ?>";
			
			$.ajax({
				data: "ItemID=" + id,
				type: "post",
				url: url,
				success: function (data) {
					var result = $.parseJSON(data);
					
					
					var table = "";
					var i = 1;
					
					if (result.product != null) {
						$.each(result.product, function (key, value) {
							
							table += "<tr>";
							table += "<td>" + i + "</td>";
							table += "<td>" + value.tDate + "</td>";
							table += "<td>" + value.bin + "</td>";
							table += "<td>" + value.ItemLookupCode + "</td>";
							table += "<td>" + value.Description + "</td>";
							table += "<td>" + value.Quantity + "</td>";
							table += "<td>" + value.Username + "</td>";
							table += "</tr>";
							
							i++;
						})
						
						console.log(table)
						$("#details tbody").html(table);
					} else {
						$("#details tbody").html("");
					}
				}
			})
		})
		
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
				url: "<?php echo base_url('fetch_stocks');?>",
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
					{"data": 5, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-right"},
					{"data": 6, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-right"},
					{"data": 7, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-right"},
					{"data": 8, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-right"},
					{"data": 9, orderable: true, "searchable": true, "render": formatCurrency, "className": "text-right"}
				],
			"order": [0, "asc"],
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
		});
		
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
		$("#details").DataTable({});
		$('#sDate,#eDate').datepicker({
			autoclose: true
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
	});
</script>