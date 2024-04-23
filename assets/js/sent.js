// JavaScript Document

$(document).ready(function(e) {
	
	getsent();
	
	$("#bntinbox").on('click', function(){
				$('#test').hide();
				$('#test1').hide();
				$('#test1').fadeIn(1000);
			});
	
	$("#bntinbox2").on('click', function(){
				$('#test1').hide();
				$('#test').hide();
				$('#test').fadeIn(1000);
			});
});


		
		
function getsent() {

    var URL = BASE_URL + "sent/get_sent";
    var id = 0;

    $.ajax({
        type: "post",
        data: "id=" + id,
        url: URL,
        success: function(data) {

            if (data.length == 0)
            {
                var table = "<tr><td colspan ='6'>No records found</td></tr>"
                $("#senttable tbody").html(table);
            }
            else
            {

                var obj = $.parseJSON(data);

                var table = "";
                var i = 1;

                $.each(obj.sent, function(key, value) {

                    var id = value.ID;
                    var Sender = value.Sender;
                    var message = value.Message;
                    var del = value.delete;
				    var nStatus = value.nStatus;
					var updated = value.Lastupdated;
					
					 if (value.delete == 0) {
                    table += "<tr>";
                    table += "<td>" + i + "</td>";
					table += "<td><input type='checkbox' /></td>";
					if(value.nStatus == "NEW"){
						table += "<td class='mailbox-star'><a href='#'><i class='fa fa-star-o text-yellow'></i></a></td>";
						}else{
							table += "<td class='mailbox-star'><a href='#'><i class='fa fa-star text-yellow'></i></a></td>";
							}
                      table += "<td class='mailbox-name'><a href='#'onclick=\"edit("+id +",'"+ message +"')\">"+Sender+"</a></td>";
                    table += "<td class='mailbox-subject'>" + message + "</td>";
                    table += "<td class='mailbox-subject'>" + updated + "</td>";
					
                    table += "</tr>";
					 }else {
                    	table += "<tr>";
						table += "<td>No reocrds found.</td>";
						table += "</tr>";
                      }

                    i++;
					$(".top").text(obj.sent.length);
                });

                $("#senttable tbody").html(table);

            }

        }

    });

}


function edit(id,message) {

$("#read").val(message);
$('#test').hide();
$('#test1').hide();
$('#test1').fadeIn(1000);

};