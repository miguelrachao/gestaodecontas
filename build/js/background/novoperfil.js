jQuery(document).ready(function(){
	jQuery('#novoperfil_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/novoperfil.php",
			data: new FormData(document.getElementById("novoperfil_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Perfil já existente!");
					novoperfil_form.nome.focus();
				}else if(data=="2"){
					alert("Registo efetuado com sucesso!");
					location.reload();
					
				}				
			}
		});						
		return false;
	});					
});	