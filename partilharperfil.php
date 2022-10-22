<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/partilharperfil.js" defer></script>
<script src="build/js/background/eliminarperfilpartilhado.js" defer></script>
<style>
	table tr td{
		padding:5px;
		text-align:center;
		padding-top: 10px;
	}
	table tr{
		border-bottom: 1px solid #cecece;	
	}
	.dark .teste{
		border-bottom: 1px solid rgb(21,94,117);
	}
</style>
<?php
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
?>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
            <h1 class="text-xl font-semibold text-center">Partilhar Perfil</h1>
			<?php
				$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
				$rows_perf=mysqli_num_rows($query_perf);
				if($rows_perf==0){
					echo '<p style="width:100%; text-align:left;">Neste momento não tem nenhum perfil.<br>Poderá criar um <a href="index.php?p=2" style="color:blue;">aqui</a>.</p></div>';
				}else{

			?>
            <form method="post" class="space-y-6" style="text-align:left;" name="partilharperfil_form" id="partilharperfil_form">
			    <center><div class="alert" id="alerta"> </div></center>
                <p style="margin-bottom:10px;">Perfil a partilhar</p>
									<p style="margin:0px;">
									<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
									<?php
											if($rows_perf!=0){
												for($a=0;$a<$rows_perf;$a++){
													$d_p=mysqli_fetch_array($query_perf);
													echo '<option value="'.$d_p['id'].'">'.base64_decode($d_p['nome']).'</option>';
												}
											}
									?>
								</select>
								<p style="margin-bottom:10px;">Conta de E-mail</p>
									<p style="margin:0px;">
									<input
										class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
										type="text"
										name="email"
										placeholder="Email com quem vai partilhar o perfil"
										id="email"
										required
										autocomplete="off"
									/>
									</p>
									</p>     
                                    <button
									type="submit"
									class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
									onclick="return confirm('Tem a certeza que deseja partilhar este perfil? O utilizador em questão terá acesso total aos registos desse perfil.')"
									>
									Partilhar
									</button>          

            </form>
			</div>
			<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
				<h1 class="text-xl font-semibold text-center">Perfis partilhados</h1>
				<?php
					$query_perf_partilhado=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE id_utilizador='$id_utilizador' ORDER BY id_perfil DESC");
					$rows_perf_part=mysqli_num_rows($query_perf_partilhado);
					if($rows_perf_part==0){
						echo '<p>Neste momento não tem nenhum perfil partilhado.</p>';
					}else{
						?>
							<table>
								<tr class="dark: teste">
									<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Perfil</td>
									<td class="font-bold tracking-wider dark:text-light">Utilizador</td>
									<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Eliminar</td>
								</tr>
								
						<?php
						for($b=0;$b<$rows_perf_part;$b++){
							$d_pp=mysqli_fetch_array($query_perf_partilhado);
							$utilizador_partilha=$d_pp['utilizador_partilha'];
							$id_perfil=$d_pp['id_perfil'];
							$query_d_up=mysqli_query($conn, "SELECT * FROM utilizadores WHERE id='$utilizador_partilha'");
							$d_up=mysqli_fetch_array($query_d_up);
							$query_d_p=mysqli_query($conn, "SELECT * FROM perfil WHERE id='$id_perfil'");
							$d_perfil=mysqli_fetch_array($query_d_p);
							?>
								<tr class="dark: teste">
									<td class=" tracking-wider dark:text-light"><?php echo base64_decode($d_perfil['nome']);?></td>
									<td class=" tracking-wider dark:text-light"><?php echo base64_decode($d_up['nome']);?></td>
									<form method="post" enctype="multipart/form-data" name="eliminar_perfil_partilhado" id="eliminar_perfil_partilhado" class="eliminar_perfil_partilhado">
										<td class=" tracking-wider dark:text-light"><center> <input type="text" name="tipo" value="1" style="display:none;"><input type="text" name="id" value="<?php echo $d_pp['id'];?>" style="display:none;"><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar esta partilha?')"></center></td>
									</form>	
								</tr>
							<?php
						} ?> 
							</table>
						<?php
					}
				?>
			</div>
			<?php
				}
			?>
