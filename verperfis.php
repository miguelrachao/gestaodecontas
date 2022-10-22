<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/editarperfil.js" defer></script>
<script src="build/js/background/eliminarperfil.js" defer></script>
<style>
	table tr td{
		padding:5px;
		text-align:center;
		padding-top: 10px;
	}
	table tr{
		border-bottom: 1px solid #cecece;	
	}
	.dark .linha{
		border-bottom: 1px solid rgb(21,94,117);
	}
</style>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px; padding: 20px;">
    <h1 class="text-xl font-semibold text-center">Meus Perfis</h1>
	<?php
		if(isset($_SESSION['on_gdc'])){
			$id_utilizador=$_SESSION['id'];
		}else{
			$id_utilizador=$_COOKIE['id'];
		}
		$query_perfis=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
		$rows_perfis=mysqli_num_rows($query_perfis);
		$query_perfis_partilhados=mysqli_query($conn, "SELECT partilha_perfil.id_perfil AS id_perfil, 
						partilha_perfil.favorito AS favorito, 
						perfil.nome AS nome_perfil,
						utilizadores.nome AS nome_utilizador_partilhou
						FROM partilha_perfil 
						INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
						INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
						WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
		$rows_perfis_partilhados=mysqli_num_rows($query_perfis_partilhados);
		if($rows_perfis==0 && $rows_perfis_partilhados==0){
			echo '<p style="width:100%; text-align:left;">Neste momento não tem perfis para gerir.<br>Pode criar um <a href="index.php?p=2" style="color:blue;">aqui</a></p>';
		}else{
			?>
				<table>
					<tr class="dark: linha">
						<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Perfil</td>
						<td class="font-bold tracking-wider dark:text-light"><img src="build/images/favorite.png"></td>
						<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Guardar</td>
						<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Eliminar</td>
					</tr>
					
					<?php
						if($rows_perfis!=0){
							for($a=0;$a<$rows_perfis;$a++){
								$dados_perfis=mysqli_fetch_array($query_perfis);
								?>
									<tr class="dark: linha">
										<form method="post" enctype="multipart/form-data" name="editarperfil_form" id="editarperfil_form" class="editarperfil_form">
											<td class=" tracking-wider dark:text-light" style=" font-size: 8pt;"><input type="text" name="tipo" value="1" style="display:none;"><input type="text" name="id_perfil" value="<?php echo $dados_perfis['id'];?>" style="display:none;"><input type="text" name="nome" value="<?php echo base64_decode($dados_perfis['nome']);?>" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" style="padding:4px; margin:0px; font-size: 10pt; text-align:center;"></td>
											<td class=" tracking-wider dark:text-light"><input type="radio" id="favorito" name="favorito" <?php if($dados_perfis['favorito']==1){ echo 'checked';} ?>></td>
											<td class=" tracking-wider dark:text-light"><center> <input type="submit" style=" background: #fff; background-image: url('build/images/save.png'); width:32px; height:32px;border-radius: 5px 11px 5px 5px; cursor: pointer;" value=""></center></td>
										</form>
										<form method="post" enctype="multipart/form-data" name="eliminar_perfil" id="eliminar_perfil" class="eliminar_perfil">
											<td class=" tracking-wider dark:text-light"><center> <input type="text" name="tipo" value="1" style="display:none;"><input type="text" name="id_perfil" value="<?php echo $dados_perfis['id'];?>" style="display:none;"><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar este perfil? Todos os dados registados neste perfil serão perdidos!')"></center></td>
										</form>	
									</tr>
								<?php
							}
						}
					?>
					<?php
						if($rows_perfis_partilhados!=0){
							for($b=0;$b<$rows_perfis_partilhados;$b++){
								$dados_perfis_p=mysqli_fetch_array($query_perfis_partilhados);
								?>
									<tr>	
										<form method="post" enctype="multipart/form-data" name="editarperfil_form" id="editarperfil_form"class="editarperfil_form">	
											<td class=" tracking-wider dark:text-light" style=" font-size: 8pt;"><input type="text" name="tipo" value="2" style="display:none;"><input type="text" name="id_perfil" value="<?php echo $dados_perfis_p['id_perfil'];?>" style="display:none;"><input type="text" name="nome" value="<?php echo base64_decode($dados_perfis_p['nome_perfil']);?>" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" style="padding:4px; margin:0px; font-size: 10pt; text-align:center;"> partilhado por <?php echo base64_decode($dados_perfis_p['nome_utilizador_partilhou']);?></td>
											<td class=" tracking-wider dark:text-light"><input type="radio" id="favorito" name="favorito" <?php if($dados_perfis_p['favorito']==1){ echo 'checked';} ?>></td>
											<td class=" tracking-wider dark:text-light"><center> <input type="submit" style=" background: #fff; background-image: url('build/images/save.png'); width:32px; height:32px; cursor: pointer; height:32px;border-radius: 5px 11px 5px 5px;" value=""></center></td>
										</form>
										<form method="post" enctype="multipart/form-data" name="eliminar_perfil" id="eliminar_perfil" class="eliminar_perfil">
											<td class=" tracking-wider dark:text-light"><center> <input type="text" name="tipo" value="2" style="display:none;"><input type="text" name="id_perfil" value="<?php echo $dados_perfis_p['id_perfil'];?>" style="display:none;"><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar este perfil? Devido a ser um perfil partilhado, apenas deixará de ter acesso.')"></center></td>
										</form>	
									</tr>
									
								<?php
							}
						}
					?>	
				</table>
			<?php
		}
	?>
</div>
