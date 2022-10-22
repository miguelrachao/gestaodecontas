jQuery(document).ready(function(){
	jQuery('.eliminar_categoria').submit(function(){
		var dados = jQuery( this ).serialize();
		jQuery.ajax({
			type: "POST",
			url: "build/php/eliminarcategoria.php",
			data: dados,
			success: function( data )
			{
				if(data=="1"){
					alert("Eliminado com sucesso!");
					location.reload();
				}
			}
		});						
		return false;
	});					
});	