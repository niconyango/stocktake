 $(function () {
    
        $("#drivers-table").dataTable();
        $("#vehicles-table").dataTable();
        $("#cards-table").dataTable();
        $("#issues-table").dataTable();
        
        //Date range as a button
        $('#daterange-btn').daterangepicker(
                {
                  ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                  },
                  startDate: moment().subtract('days', 29),
                  endDate: moment()
                },
                function (start, end) {
                    
                  var startDate = start.format('MMMM D, YYYY');
                  var endDate = end.format('MMMM D, YYYY');
                  
                  $('#report_range').append(" For "+start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                  
                  var host = $(location).attr('host');
                  var path = $(location).attr('pathname');
                  var params = {from:startDate,to:endDate};
                  
                  url = "?"+jQuery.param(params);
                  
                 $(location).attr('href',url);
                }
        );

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        
        $("#search_promotion").click(function(){
            
            var promo = $("#promotion").val();
            
            var params = {promo:promo};
                  
            url = "?"+jQuery.param(params);         

            $(location).attr('href',url);
        });
        
        $("#search_doc").click(function(){
            
            var doc = $("#doc").val();
            
            var params = {doc:doc};
                  
            url = "?"+jQuery.param(params);         

            $(location).attr('href',url);
        });
        
        $(".generate").click(function(e){
            
            e.preventDefault();
            var search = $(location).attr('search');
            var url = $(this).attr('href');
            
            url += search;          

            window.open(url, '_blank');
           // $(location).attr('href',url);
            
        });

         $(".btn-edit1").click(function(e){
          var name= $(this).attr('name')
          
          $("#driver_id").val($(this).attr('id'));
          $("#driver").val($(this).attr('name'));
          $("#idnumber").val($(this).attr('idnumber'));
          $("#cellphone").val($(this).attr('cellphone'));
          $("#pass").val($(this).attr('password'));
          $("#drivers-modal").modal("show")
        })
      
      // $("#btn-save-contact").click(function(e){
          
      //   var url ="<?php echo base_url()?>index.php/Welcome/add_contact";
      //   var data = $("#contact-form").serialize();
      //   // alert(data)
        
      //   $.ajax({
      //        type:"POST",
      //        data:data,
      //       url:url,
      //     success:function(data){                 
      //       $("#error").html(data).show().addClass("alert-success");
            
      //       location.reload();
      //     }
      //   });
      
      // })

        $("#header li a").on('click', function () {
            $("#header li a").removeClass("active");
            $(this).addClass("active");
            return false;
        });
      });