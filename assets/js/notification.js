// JavaScript Document

$(document).ready(function(e) {
	
	getnotifications();
	
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

$(function () {
        //Add text editor
        $("#compose-textarea").wysihtml5();
      });
	  
	  $("#btnsave").click(function(){
		alert('test');
		save();
	});
	  
	   $("#discard").click(function(){
		clear()
	});
	  function save(){
		
		var url= "notifications/reply"
		var data = $("#notification").serialize();
		$(".error").text("Saving Info. Please wait....").css({"color":"green"})
		$.ajax({
					type:"post",
					 url:url,
					data:data,
				 success:function(data){
								
								$(".error").text(data).css({"color":"red"})//.fadeOut(2000);;
								
								clear();
								//getitem();
								
								location.reload();
						}
				});
				
				$(".error").text("").css({"color":"black"})
}


		
		
function getnotifications() {

    var url = "notifications/get_notifications";
    var id = 0;

    $.ajax({
        type: "post",
        data: "id=" + id,
        url: url,
        success: function(data) {

            if (data.length == 0)
            {
                var table = "<tr><td colspan ='6'>No records found</td></tr>"
                $("#notificationstable tbody").html(table);
            }
            else
            {

                var obj = $.parseJSON(data);

                var table = "";
                var i = 1;

                $.each(obj.notifications, function(key, value) {

                    var id = value.ID;
                    var Sender = value.SenderID;
					var Sendern = value.Fullname;
                    var message = value.Message;
                    var del = value.delete;
				    var nStatus = value.nStatus;
					var updated = value.Lastupdated;
				
					
					 if (value.delete == 0) {
                    table += "<tr>";
					table += "<td><input type='checkbox' /></td>";
					if(value.nStatus == "NEW"){
						table += "<td class='mailbox-star'><a href='#'><i class='fa fa-star-o text-yellow'></i></a></td>";
						}else{
							table += "<td class='mailbox-star'><a href='#'><i class='fa fa-star text-yellow'></i></a></td>";
							}
                    table += "<td class='mailbox-subject'>" + updated + "</td>";
                    table += "<td class='mailbox-subject'>" + message + "</td>";
					 table += "<td class='mailbox-name'><a href='#'onclick=\"edit("+id +",'"+ Sender +"','"+ Sendern +"','"+ message +"')\">Reply</a></td>";
                    table += "</tr>";
					 }else {
                    	table += "<tr>";
						table += "<td>No reocrds found.</td>";
						table += "</tr>";
                      }

                    i++;
					$(".num").text(obj.notifications.length);
                });

                $("#notificationstable tbody").html(table);

            }

        }

    });

}

 $(function () {
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
        });

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
          var clicks = $(this).data('clicks');
          if (clicks) {
            //Uncheck all checkboxes
            $("input[type='checkbox']", ".mailbox-messages").iCheck("uncheck");
          } else {
            //Check all checkboxes
            $("input[type='checkbox']", ".mailbox-messages").iCheck("check");
          }
          $(this).data("clicks", !clicks);
        });

        //Handle starring for glyphicon and font awesome
        $(".mailbox-star").click(function (e) {
          e.preventDefault();
          //detect type
          var $this = $(this).find("a > i");
          var glyph = $this.hasClass("glyphicon");
          var fa = $this.hasClass("fa");          

          //Switch states
          if (glyph) {
            $this.toggleClass("glyphicon-star");
            $this.toggleClass("glyphicon-star-empty");
          }

          if (fa) {
            $this.toggleClass("fa-star");
            $this.toggleClass("fa-star-o");
          }
        });
      });
	  function RefreshTable () {
		
            var table = document.getElementById ("inboxtable");
            table.refresh ();
        }

function edit(id,Sender,Sendern,message) {
alert(Sender);

$("#ReceipientID").val(Sendern);
$("#Receipient").val(Sender);


$('#test').hide();
$('#test1').hide();
$('#test1').fadeIn(1000);

};

function clear(){
	
	$("input[type='text']").each(function() {
			
			$(this).val("");
        });
	$("#id").val("");
	$("#composed").val("");
	
	
}