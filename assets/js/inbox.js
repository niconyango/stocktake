// JavaScript Document
var BASE_URL = "http://localhost/HRM/index.php/";
$(document).ready(function(e) {
	
	getinbox();
	
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


		
		
function getinbox() {

    var URL = BASE_URL + "messages/get_inbox";
    var id = 0;

    $.ajax({
        type: "post",
        data: "id=" + id,
        url: URL,
        success: function(data) {

            if (data.length == 0)
            {
                var table = "<tr><td colspan ='6'>No records found</td></tr>"
                $("#inboxtable tbody").html(table);
            }
            else
            {

                var obj = $.parseJSON(data);

                var table = "";
                var i = 1;

                $.each(obj.inbox, function(key, value) {

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
						table += "<td class='mailbox-star'><a href='#'><i class='fa fa-star text-yellow'></i></a></td>";
						}else{
							table += "<td class='mailbox-star'><a href='#'><i class='fa fa-star-o text-yellow'></i></a></td>";
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
					$(".mess").text(obj.inbox.length);
                });

                $("#inboxtable tbody").html(table);

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

function edit(id,message) {
//alert('me');
$("#read").val(message);
$('#test').hide();
$('#test1').hide();
$('#test1').fadeIn(1000);

};