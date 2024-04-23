// JavaScript Document
var BASE_URL = "http://localhost/HRM/index.php/";
$(document).ready(function(e) {
	
	getleave();
	
	 $(function () {
                $('#from').datepicker();
            });
	 $(function () {
                $('#to').datepicker();
            });
	
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
});
function getleave() {

    var URL = BASE_URL + "leave/get_leaves";
    var id = 0;

    $.ajax({
        type: "post",
        data: "id=" + id,
        url: URL,
        success: function(data) {

            if (data.length == 0)
            {
                var table = "<tr><td colspan ='6'>No records found</td></tr>"
                $("#leavetable tbody").html(table);
            }
            else
            {

                var obj = $.parseJSON(data);

                var table = "";
                var i = 1;

                $.each(obj.leaves, function(key, value) {

                    var id = value.ID;
                    var type = value.type;
                    var from = value.fDate;
                    var to = value.tDate;
				    var diff = value.DaysDiff;
					var approved = value.Approved;
					var notes = value.Notes;

                    table += "<tr>";
                    table += "<td>" + i + "</td>";
                    table += "<td class='mailbox-name'>" + type + "</td>";
                    table += "<td class='mailbox-subject'>" + from + "</td>";
                    table += "<td class='mailbox-subject'>" + to + "</td>";
					table += "<td class='mailbox-subject'>" + diff + "</td>";
					table += "<td class='mailbox-subject'>" + approved + "</td>";
					table += "<td class='mailbox-subject'>" + status + "</td>";
					table += "<td class='mailbox-subject'>" + notes+ "</td>";
                    table += "<td><button type='button' class='btn btn-link' onclick=\"edit()\">Edit</button></td>";
                    table += "</tr>";

                    i++;
                });

                $("#leavetable tbody").html(table);

            }

        }

    });

}

function editpo(id,type,from,to,diff,approved) {

$("#type").val(type);
$("#from").val(from);
$("#to").val(to);
$("#diff").val(diff);
$("#approved").val(approved);
};