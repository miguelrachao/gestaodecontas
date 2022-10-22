<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/dadosutilizador.js" defer></script>
<script src="build/js/background/passwordutilizador.js" defer></script>
<style>
	hr{
		border-color:#cecece;	
		
	}
	.dark .linha{
		border-color: rgba(21,94,117,1);
	}
</style>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
            <h1 class="text-xl font-semibold text-center">Minha conta</h1>
			<?php
				if(isset($_SESSION['on_gdc'])){
					$id_utilizador=$_SESSION['id'];
				}else{
					$id_utilizador=$_COOKIE['id'];
				}
				$query_utilizador=mysqli_query($conn, "SELECT * FROM utilizadores WHERE id='$id_utilizador'");
				$dados_utilizador=mysqli_fetch_array($query_utilizador);
?>
            <form method="post" class="space-y-6" style="text-align:left;" name="dados_utilizador_form" id="dados_utilizador_form">
			    <center><div class="alert" id="alerta"> </div></center>
				<p style="margin-bottom:10px;">Nome</p>
					<p style="margin:0px;">
						<input
							class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							type="text"
							autocomplete="off"
							id="nome"
                            name="nome"
							placeholder="Nome"
							value="<?php echo base64_decode($dados_utilizador['nome']);?>"
							required
						/>
					</p>
                	<p>Conta de email</p>
					<p style="font-size:8pt; margin-bottom:10px; margin-top: 0px;">Uma conta de email errada, impossibilita-o de fazer login</p>
					<p style="margin:0px;">
						<input
							class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							type="email"
							autocomplete="off"
							id="email"
                            name="email"
							placeholder="Email"
							value="<?php echo base64_decode($dados_utilizador['email']);?>"
							required
						/>
					</p>
					<button
						type="submit"
						class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
					>Alterar
					</button>    
			</form>
			<form method="post" class="space-y-6" style="text-align:left;" name="password_utilizador_form" id="password_utilizador_form">
					<hr class="dark: linha">
					<h1 class="text-xl font-semibold text-center">Alteração de password</h1>
					<p style="margin-bottom:10px;">Password antiga</p>
					<p style="margin:0px;">
					<input
							class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							type="password"
							autocomplete="off"
							id="password"
                            name="password"
							placeholder="Password antiga"
							required
						/>
					</p>   
					<p style="margin-bottom:10px;">Nova password</p>
					<p style="margin:0px;">
					<input
							class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							type="password"
							autocomplete="off"
							id="nova_password"
                            name="nova_password"
							placeholder="Nova password"
							required
						/>
					</p>   
					<p style="margin-bottom:10px;">Confirmar password</p>
					<p style="margin:0px;">
					<input
							class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							type="password"
							autocomplete="off"
							id="confirmar_password"
                            name="confirmar_password"
							placeholder="Confirmar password"
							required
						/>
					</p>    
                    <button
						type="submit"
						class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
					>Alterar
					</button>          

            </form>
</div>