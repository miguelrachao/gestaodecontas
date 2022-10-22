<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/editarregisto.js" defer></script>
<script>
$( "#valor" ).blur(function() {
    this.value = parseFloat(this.value).toFixed(2);
});
</script>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
			<p style="text-align:left;">
                <a href="<?php echo $_SERVER['HTTP_REFERER'];?>"><img src="build/images/voltar.png"></a>
                <h1 class="text-xl font-semibold text-center" style="margin-top: -30px;">Editar registo</h1>
            </p>
			<?php
				if(isset($_SESSION['on_gdc'])){
					$id_utilizador=$_SESSION['id'];
				}else{
					$id_utilizador=$_COOKIE['id'];
				}
				$id_registo=$_GET['id'];
				$query_registo=mysqli_query($conn, "SELECT * FROM registos WHERE id='$id_registo'");
				$rows_registo=mysqli_num_rows($query_registo);
				if($rows_registo==0){
					echo '<p style="width:100%; text-align:left;">Registo não encontrado!</p>';
				}else{
					$dados_registo=mysqli_fetch_array($query_registo);
					$id_perfil=$dados_registo['id_perfil'];
					$query_verifica_perfil=mysqli_query($conn, "SELECT * FROM perfil WHERE id='$id_perfil' AND id_utilizador='$id_utilizador'");
					$rows_verifica_perfil=mysqli_num_rows($query_verifica_perfil);
					if($rows_verifica_perfil==0){
						$query_verifica_perfil_partilhado=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE id_perfil='$id_perfil' AND utilizador_partilha='$id_utilizador'");
						$rows_verficia_perfil_partilhado=mysqli_num_rows($query_verifica_perfil_partilhado);
						if($rows_verficia_perfil_partilhado==0){
							echo '<p style="width:100%; text-align:left; color:red;">Não tem acesso a este registo!</p>';
						}else{
							?>
								<form method="post" class="space-y-6" style="text-align:left;" name="editar_registo" id="editar_registo">
									<center><div class="alert" id="alerta"> </div></center>
														<p style="margin-bottom:10px;">Categoria</p>
														<p style="margin:0px;">
														<input type="text" value="<?php echo $id_registo ;?>" name="id" style="display:none;">
														<select name="id_categoria" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
															<?php 
																$query_categoria=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil'");
																$rows_categorias=mysqli_num_rows($query_categoria);
																for($fc=0;$fc<$rows_categorias;$fc++){
																	$dados_categoria=mysqli_fetch_array($query_categoria);
																	if($dados_registo['id_categoria']==$dados_categoria['id']){
																		echo '<option value="'.$dados_categoria['id'].'" selected>'.base64_decode($dados_categoria['categoria']).'</option>';
																	}else{
																		echo '<option value="'.$dados_categoria['id'].'">'.base64_decode($dados_categoria['categoria']).'</option>';
																	}
																}
															?>
														</select>
														</p>
														<p style="margin-bottom:10px;">Detalhes</p>
														<p style="margin:0px;">
														<input name="detalhes" type="text" value="<?php echo base64_decode($dados_registo['detalhes']);?>" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														</p>    
														</p>
														<p style="margin-bottom:10px;">Valor</p>
														<p style="margin:0px;">
														<input name="valor" type="number" step="0.01" value="<?php echo base64_decode($dados_registo['valor']);?>" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														</p>   
														<p style="margin-bottom:10px;">Data</p>
														<p style="margin:0px;">
														<?php
															$data = date("Y-m-d", strtotime($dados_registo['data']));  
															$hora = date("H:i", strtotime($dados_registo['data']));  
															echo '<input type="datetime-local" name="data" value="'.$data.'T'.$hora.'" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">';
														?>
														</p>   
														<button
														type="submit"
														class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
														>
														Alterar
														</button>          

								</form>
						<?php
						}
					}else{
						?>
							<form method="post" class="space-y-6" style="text-align:left;" name="editar_registo" id="editar_registo">
									<center><div class="alert" id="alerta"> </div></center>
														<p style="margin-bottom:10px;">Categoria</p>
														<p style="margin:0px;">
														<input type="text" value="<?php echo $id_registo ;?>" name="id" style="display:none;">
														<select name="id_categoria" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
															<?php 
																$query_categoria=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil'");
																$rows_categorias=mysqli_num_rows($query_categoria);
																for($fc=0;$fc<$rows_categorias;$fc++){
																	$dados_categoria=mysqli_fetch_array($query_categoria);
																	if($dados_registo['id_categoria']==$dados_categoria['id']){
																		echo '<option value="'.$dados_categoria['id'].'" selected>'.base64_decode($dados_categoria['categoria']).'</option>';
																	}else{
																		echo '<option value="'.$dados_categoria['id'].'">'.base64_decode($dados_categoria['categoria']).'</option>';
																	}
																}
															?>
														</select>
														</p>
														<p style="margin-bottom:10px;">Detalhes</p>
														<p style="margin:0px;">
														<input name="detalhes" type="text" value="<?php echo base64_decode($dados_registo['detalhes']);?>" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														</p>    
														</p>
														<p style="margin-bottom:10px;">Valor</p>
														<p style="margin:0px;">
														<input name="valor" type="number"  step="0.01" value="<?php echo base64_decode($dados_registo['valor']);?>" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														</p>   
														<p style="margin-bottom:10px;">Data</p>
														<p style="margin:0px;">
														<?php
															$data = date("Y-m-d", strtotime($dados_registo['data']));  
															$hora = date("H:i", strtotime($dados_registo['data']));  
															echo '<input type="datetime-local" name="data" value="'.$data.'T'.$hora.'" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">';
														?>
														</p>   
														<button
														type="submit"
														class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
														>
														Alterar
														</button>          

								</form>

						<?php
					}
				}
			?>
</div>