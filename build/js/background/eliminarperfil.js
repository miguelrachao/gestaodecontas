jQuery(document).ready(function(){
	jQuery('.eliminar_perfil').submit(function(){
		var dados = jQuery( this ).serialize();
		jQuery.ajax({
			type: "POST",
			url: "build/php/eliminarperfil.php",
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