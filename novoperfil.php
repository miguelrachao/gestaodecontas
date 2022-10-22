<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/novoperfil.js" defer></script>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
            <h1 class="text-xl font-semibold text-center">Novo Perfil</h1>
            <form method="post" class="space-y-6" style="text-align:left;" name="novoperfil_form" id="novoperfil_form">
			    <center><div class="alert" id="alerta"> </div></center>
                <p style="margin-bottom:10px;">Nome do perfil</p>
									<p style="margin:0px;">
									<input
										class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
										type="text"
										id="nome"
                                        name="nome"
										placeholder="Nome do perfil"
										autocomplete="off"
										required
									/>
									</p>     
                                    <button
									type="submit"
									class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
									>
									Registar
									</button>          

            </form>
</div>