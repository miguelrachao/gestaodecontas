jQuery(document).ready(function(){
	jQuery('#login_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/login_process.php",
			data: new FormData(document.getElementById("login_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Conta não encontrada!");
					login_form.email.focus();
				}else if(data=="2"){
					alert("Password errada!");
					login_form.password.focus();
				}else if(data=="3"){
					document.getElementById("body_hide").style.display = "none";
					document.getElementById("loading").style.display = "inline-block";
					setTimeout((function() {
					  window.location.reload();
					}), 500);
				}				
			}
		});						
		return false;
	});					
});	