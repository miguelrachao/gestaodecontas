jQuery(document).ready(function(){
	jQuery('#dados_utilizador_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/dadosutilizador.php",
			data: new FormData(document.getElementById("dados_utilizador_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Alterado com com sucesso!");
				}else if(data=="2"){
					alert("Conta de email já existente!");
					dados_utilizador_form.email.focus();
				}			
			}
		});						
		return false;
	});					
});	