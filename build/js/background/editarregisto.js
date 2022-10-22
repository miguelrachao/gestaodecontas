jQuery(document).ready(function(){
	jQuery('#editar_registo').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/editarregisto.php",
			data: new FormData(document.getElementById("editar_registo")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Alterado com com sucesso!");
				}		
			}
		});						
		return false;
	});					
});	