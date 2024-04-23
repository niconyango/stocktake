// JavaScript Document

$(document).ready(function(e) {

	 $("#btnsave").click(function(){
		alert('Details Successfuly Updated');
		save();
	});
	 $("#btnedit").click(function(){
		//alert('test');
		edit();
	});

});

	  function save(){

		var url = "http://127.0.0.1/Security/index.php/profile/update"
		var data = $("#profile").serialize();
		$(".error").text("Saving Info. Please wait....").css({"color":"green"})
		$.ajax({
					type:"post",
					 url:url,
					data:data,
				 success:function(data){

								$(".error").text(data).css({"color":"red"})//.fadeOut(2000);;

								//clear();
								//getitem();

								location.reload();
						}
				});

				$(".error").text("").css({"color":"black"})
}



function edit(id,Name,UserName,Cellphone,Address) {
	window.location = "profile";
	var url = "profile/get_profile";
	var id = value.ID;
	var Name = value.Name;
	var UserName = value.UserName;
	var Cellphone = value.Cellphone;
	var Address = value.Address;

	$("#address").val(Address);
	$("#username").val(UserName);
	$("#cellphone").val(Cellphone);
	$("#name").val(Name);

};
