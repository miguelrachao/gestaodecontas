jQuery(document).ready(function(){
	jQuery('#novacategoria_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/novacategoria.php",
			data: new FormData(document.getElementById("novacategoria_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Categoria já existente no perfil selecionado!");
					novacategoria_form.nome.focus();
				}else if(data=="2"){
					alert("Registo efetuado com sucesso!");
					location.reload();
				}				
			}
		});						
		return false;
	});					
});	