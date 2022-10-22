jQuery(document).ready(function(){
	jQuery('.editar_categoria').submit(function(){
		var dados = jQuery( this ).serialize();
		jQuery.ajax({
			type: "POST",
			url: "build/php/editarcategoria.php",
			data: dados,
			success: function( data )
			{
				if(data=="1"){
					alert("Alterado com sucesso!");
					location.reload();
				}
			}
		});						
		return false;
	});					
});	