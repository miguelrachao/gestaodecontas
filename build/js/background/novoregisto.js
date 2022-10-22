jQuery(document).ready(function(){
	jQuery('#novoregisto_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/novoregisto.php",
			data: new FormData(document.getElementById("novoregisto_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Selecione a categoria!");
					novoregisto_form.id_categoria.focus();
				}else if(data=="2"){
					alert("Registo efetuado com sucesso!");
					location.reload();
				}				
			}
		});						
		return false;
	});					
});	