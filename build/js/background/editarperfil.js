jQuery(document).ready(function(){
	jQuery('.editarperfil_form').submit(function(){
		var dados = jQuery( this ).serialize();
		jQuery.ajax({
			type: "POST",
			url: "build/php/editarperfil.php",
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