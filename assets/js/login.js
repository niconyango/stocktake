$(document).ready(function(){
	
	$("#login_btn").click(function(e){
		
		e.preventDefault();
		
		if(validate() == false)
			login();
		
	});
})
function validate(){
	
	var Staffno = $("#Staffno").val();
	var password = $("#Pass").val();
	var hasErrors = false;
	
	if($.trim(Staffno) == "")
	{
		$("#Staffno_error").text("Enter Name").css({'color':'red'});
		
		hasErrors = true;
	}
	else if($.trim(Staffno) == "")
	{
		$("#Staffno_error").text("Invalid password").css({'color':'red'});
		hasErrors = true;
	}
	else
	{
	$("#Staffno_error").text("").css({'color':'black'});
	}
	
	if($.trim(Pass) == "")
	{
		$("#pass_error").text("Enter password").css({'color':'red'});
		
		hasErrors = true;
	}
	else
	{
	$("#pass_error").text("").css({'color':'black'});
	}
	
	return hasErrors;
}

function login(){
	
	var formData = $("#login_form").serialize();
	var url =  "index.php/login/process_login"
	
	$.ajax({
		type:"post",
		url:url,
		data:formData,
		success:function(data){
			var json = $.parseJSON(data);
			
			if(json.response == 1)
			{
				$(".alert").html("Login successful. Redirecting...")
						   .addClass("alert-success")
						   .fadeIn('slow');
						   
				window.location = "index.php/"+json.message
				
		
			}
			else
			{
				$(".alert").html(json.message)
						   .addClass("alert-danger")
						   .fadeIn('slow');
			}
		}
	});
}