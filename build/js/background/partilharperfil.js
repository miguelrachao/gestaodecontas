jQuery(document).ready(function(){
	jQuery('#partilharperfil_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/partilharperfil.php",
			data: new FormData(document.getElementById("partilharperfil_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Conta de email não encontrada!");
					partilharperfil_form.email.focus();
				}else if(data=="2"){
					alert("Este perfil já foi partilhado com esta conta de email!");
					partilharperfil_form.email.focus();
				}else if(data=="3"){
					alert("Tem de escolher outra conta de email!");
					partilharperfil_form.email.focus();
				}else if(data=="4"){
					alert("Perfil partilhado com sucesso!");
					location.reload();
				}			
			}
		});						
		return false;
	});					
});	