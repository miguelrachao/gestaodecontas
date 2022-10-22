jQuery(document).ready(function(){
	jQuery('#password_utilizador_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/passwordutilizador.php",
			data: new FormData(document.getElementById("password_utilizador_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Alterado com com sucesso!");
					password_utilizador_form.reset();
				}else if(data=="2"){
					alert("Passwords não correspondem!");
					password_utilizador_form.confirmar_password.focus();
				}else if(data=="3"){
					alert("Password errada!");
					password_utilizador_form.password.focus();
				}				
			}
		});						
		return false;
	});					
});	