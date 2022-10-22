jQuery(document).ready(function(){
	jQuery('#registoconta_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/registo_conta.php",
			data: new FormData(document.getElementById("registoconta_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("Registo efetuado com sucesso!");
					registoconta_form.reset();
				}else if(data=="2")	{
					alert("Esta conta de email já existe!");
					registoconta_form.email.focus();
				}		
			}
		});						
		return false;
	});					
});	