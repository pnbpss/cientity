/*
* handle user login 
* author => panu boonpromsook
*/
$("#bpsInterface_loginForm button .submitField").click(function(){ do_bps_interface_submit_login()});
$("#bpsInterface_loginForm .inputField").keyup(function(e){
	var code = e.which;
	if(code==13)
	{
		do_bps_interface_submit_login();
	}
});

function do_bps_interface_submit_login()
{
	var Username = $("#bpsInterface_loginForm .inputField[fieldName='Username']").val();
	var Password = $("#bpsInterface_loginForm .inputField[fieldName='Password']").val();
	$.ajax({
		url: hrds_baseUrl+'user/login',
		data: {Username:Username,Password:Password},
		type: 'POST',
		dataType: 'json',
		success: function (data){
			if (data.stat=="F"){
			}else{
				window.location.replace(hrds_baseUrl);
			}
		}
	});
}