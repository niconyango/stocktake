// JavaScript Document
var BASE_URL = "http://localhost/HRM/index.php/" 
$(document).ready(function(e) {
    
	$(".btn-edit").click(function(e){
		var id = $(this).attr("id");
		var type = $(this).attr("type");
		var leave = $(this).attr("leave");
		var fdate = $(this).attr("fdate");
		var tdate = $(this).attr("tdate");
		var DaysDiff = $(this).attr("DaysDiff");
		var Notes = $(this).attr("Notes");
		
		$("#leaveid").val(id);
		$("#leavetype").val(leave);
		$("#dateto").val(tdate);
		$("#datefrom").val(fdate);
		$("#Notes").val(Notes);

		$("#myModal").modal("show");
		
		})
		
	$("#submit_leave").click(function(e){
		
		save_leave();
	})
});

function save_leave(){
	var URL = BASE_URL + "leave/save_leave";
	var data = $("#leave_form").serialize();

	$.ajax({
		type:"POST",
		url:URL,
		data:data,
		success: function(data){
			
			var object = $.parseJSON(data)
			
			
			
			var droptable = "";
			var i = 1;
			
			$.each(object.leaves, function(key, value){
				
				var type = value.type;
				var taken = value.DaysDiff;
				var status = value.Approved;
				var fdate = value.fDate;
				var tdate = value.tDate;
				var notes = value.Notes;
			
				droptable += "<tr>";
				droptable +="<td>" + i+"</td>"
				droptable +="<td>" + type+"</td>"
				droptable +="<td>" + fdate+"</td>"
				droptable +="<td>" + tdate+"</td>"
				droptable +="<td>" + taken+"</td>"
				droptable +="<td>" + status+"</td>"
				droptable +="<td>" + notes+"</td>"	
				i++;
				
			});
			
				$("#leavetable tbody").html(droptable);
									
				$("#myModal").modal("hide");
				
				 clear();
				 
				 location.reload();
		}
		
		
		
		});
	
}

function clear(){
	
				 $(this).val(0);
				 $(this).val("");
				 $(this).val("");
				 $(this).val("");
				 $(this).val("");
				 $(this).val("");
				 $(this).val("");
}