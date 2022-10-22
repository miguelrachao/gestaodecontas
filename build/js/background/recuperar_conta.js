jQuery(document).ready(function(){
	jQuery('#recuperar_form').submit(function(){
		jQuery.ajax({
			type: "POST",
			url: "build/php/recuperar_conta.php",
			data: new FormData(document.getElementById("recuperar_form")),
			mimeType: "multipart/form-data",
			contentType: false,
			processData:false,
			success: function( data )
			{
				if(data=="1"){
					alert("A sua password foi reposta e enviada por email. Verifique a sua caixa de correio.");
					recuperar_form.reset();
				}else if(data=="2")	{
					alert("Esta conta de email não existe!");
					recuperar_form.email.focus();
				}		
			}
		});						
		return false;
	});					
});	