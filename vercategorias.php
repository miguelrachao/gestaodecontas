<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/editarcategoria.js" defer></script>
<script src="build/js/background/eliminarcategoria.js" defer></script>
<style>
	table tr td{
		padding:2px;
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
<?php
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
?>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
				<h1 class="text-xl font-semibold text-center">Categorias</h1>
			
			<?php
				if(isset($_GET['perfil'])){
					$perfil=$_GET['perfil'];
					if(isset($_GET['tipo'])){
						if($_GET['tipo']==2){
							//faz verificacao tabela partilha perfil
							$query_v_p=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE id_perfil='$perfil' AND utilizador_partilha='$id_utilizador'");
							$rows_v_p=mysqli_num_rows($query_v_p);
							if($rows_v_p==0){
								echo '<p style="padding:30px;"><b style="color:red; font-size:14pt;">Não tem acesso a este perfil!</b></p>';
							}else{
								//mostra a página com o perfil selecionado
								
								$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
								$rows_perf=mysqli_num_rows($query_perf);
								$query_perfis_partilhados=mysqli_query($conn, "SELECT partilha_perfil.id_perfil AS id_perfil, 
										perfil.nome AS nome_perfil,
										utilizadores.nome AS nome_utilizador_partilhou
										FROM partilha_perfil 
										INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
										INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
										WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
								$rows_perfis_partilhados=mysqli_num_rows($query_perfis_partilhados);
								if($rows_perf==0 && $rows_perfis_partilhados==0){
									echo '<p style="width:100%; text-align:left;">Para gerir categorias, por favor crie um perfil.<br>Poderá faze-lo <a href="index.php?p=2" style="color:blue;">aqui</a>.</p></div>';
								}else{

							?>
								<p style="margin-bottom:10px; text-align:left;">Perfil</p>
													<p style="margin:0px;">
													<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" onchange="if (this.value) window.location.href='index.php?p=6&perfil='+this.value">
													<?php
															if($rows_perf!=0){
																for($a=0;$a<$rows_perf;$a++){
																	$d_p=mysqli_fetch_array($query_perf);
																	if($perfil==$d_p['id']){
																		echo '<option value="'.$d_p['id'].'&tipo=1" selected>'.base64_decode($d_p['nome']).'</option>';
																	}else{
																		echo '<option value="'.$d_p['id'].'&tipo=1">'.base64_decode($d_p['nome']).'</option>';
																	}
																}
															}
													?>
													<?php
														if($rows_perfis_partilhados!=0){
															for($f_p=0;$f_p<$rows_perfis_partilhados;$f_p++){
																$dados_perfis_p=mysqli_fetch_array($query_perfis_partilhados);
																if($perfil==$dados_perfis_p['id_perfil']){
																	?>
																	<option value="<?php echo $dados_perfis_p['id_perfil'];?>&tipo=2" selected><?php echo base64_decode($dados_perfis_p['nome_perfil']);?> partilhado por <?php echo base64_decode($dados_perfis_p['nome_utilizador_partilhou']);?></option>
																<?php
																}else{
																	?>
																	<option value="<?php echo $dados_perfis_p['id_perfil'];?>&tipo=2"><?php echo base64_decode($dados_perfis_p['nome_perfil']);?> partilhado por <?php echo base64_decode($dados_perfis_p['nome_utilizador_partilhou']);?></option>
																<?php
																}
																
															}
														}
													?>	
												</select>
													</p> 
								<?php
									$query_categorias=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$perfil'");
									$rows_categorias=mysqli_num_rows($query_categorias);
									if($rows_categorias==0){
										echo '<p style="width:100%; text-align:left;">Este perfil ainda não tem categorias.<br> Pode criar <a href="index.php?p=3" style="color:blue;">Aqui</a></p>';
									}else{
										?>
											<table>
											<tr class="dark: linha">
												<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Categoria</td>
												<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Tipo</td>
												<td class="font-bold tracking-wider dark:text-light">Guardar</td>
												<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Eliminar</td>
											</tr>
															
										<?php
										for($b=0;$b<$rows_categorias;$b++){
											$d_c=mysqli_fetch_array($query_categorias);
											?>
												<tr class="dark: linha">
													<form method="post" enctype="multipart/form-data" name="editar_categoria" id="editar_categoria" class="editar_categoria">
														<td class=" tracking-wider dark:text-light"><input type="text" name="id" value="<?php echo $d_c['id'];?>" style="display:none;"><input type="text" style="padding:0px; padding-left:2px; max-width:100px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" value="<?php echo base64_decode($d_c['categoria']);?>" name="categoria" required></td>
														<td class=" tracking-wider dark:text-light"> 
														<select name="tipo" style="padding:0px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
															<option value="0" <?php if($d_c['tipo']==0){echo'selected';}?>>Débito</option>
															<option value="1"  <?php if($d_c['tipo']==1){echo'selected';}?>>Crédito</option>
															<option value="2"  <?php if($d_c['tipo']==2){echo'selected';}?>>Débito Pessoal</option>														</select>
														</td>
														<td class=" tracking-wider dark:text-light"><center> <input type="submit" style=" background: #fff; background-image: url('build/images/save.png'); width:32px; height:32px; cursor: pointer; height:32px;border-radius: 5px 11px 5px 5px;" value=""></center></td>
													</form>
													<form method="post" enctype="multipart/form-data" name="eliminar_categoria" id="eliminar_categoria" class="eliminar_categoria">
														<td class=" tracking-wider dark:text-light"><center> <input type="text" name="id" value="<?php echo $d_c['id'];?>" style="display:none;"><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar esta caregoria? Todos os registos associados a esta categoria serão perdidos!')"></center></td>
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
								
								//fim da página com o perfil selecionado
							}
						}elseif($_GET['tipo']==1){
							//faz verificacao tabela perfil
							$query_v_p=mysqli_query($conn, "SELECT * FROM perfil WHERE id='$perfil' AND id_utilizador='$id_utilizador'");
							$rows_v_p=mysqli_num_rows($query_v_p);
							if($rows_v_p==0){
								echo '<p style="padding:30px;"><b style="color:red; font-size:14pt;">Não tem acesso a este perfil!</b></p>';
							}else{
								//mostra a página com o perfil selecionado
								
								$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
								$rows_perf=mysqli_num_rows($query_perf);
								$query_perfis_partilhados=mysqli_query($conn, "SELECT partilha_perfil.id_perfil AS id_perfil, 
										perfil.nome AS nome_perfil,
										utilizadores.nome AS nome_utilizador_partilhou
										FROM partilha_perfil 
										INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
										INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
										WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
								$rows_perfis_partilhados=mysqli_num_rows($query_perfis_partilhados);
								if($rows_perf==0 && $rows_perfis_partilhados==0){
									echo '<p style="width:100%; text-align:left;">Para gerir categorias, por favor crie um perfil.<br>Poderá faze-lo <a href="index.php?p=2" style="color:blue;">aqui</a>.</p></div>';
								}else{

							?>
								<p style="margin-bottom:10px; text-align:left;">Perfil</p>
													<p style="margin:0px;">
													<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" onchange="if (this.value) window.location.href='index.php?p=6&perfil='+this.value">
													<?php
															if($rows_perf!=0){
																for($a=0;$a<$rows_perf;$a++){
																	$d_p=mysqli_fetch_array($query_perf);
																	if($perfil==$d_p['id']){
																		echo '<option value="'.$d_p['id'].'&tipo=1" selected>'.base64_decode($d_p['nome']).'</option>';
																	}else{
																		echo '<option value="'.$d_p['id'].'&tipo=1">'.base64_decode($d_p['nome']).'</option>';
																	}
																}
															}
													?>
													<?php
														if($rows_perfis_partilhados!=0){
															for($f_p=0;$f_p<$rows_perfis_partilhados;$f_p++){
																$dados_perfis_p=mysqli_fetch_array($query_perfis_partilhados);
																if($perfil==$dados_perfis_p['id_perfil']){
																	?>
																	<option value="<?php echo $dados_perfis_p['id_perfil'];?>&tipo=2" selected><?php echo base64_decode($dados_perfis_p['nome_perfil']);?> de <?php echo base64_decode($dados_perfis_p['nome_utilizador_partilhou']);?></option>
																<?php
																}else{
																	?>
																	<option value="<?php echo $dados_perfis_p['id_perfil'];?>&tipo=2"><?php echo base64_decode($dados_perfis_p['nome_perfil']);?> de <?php echo base64_decode($dados_perfis_p['nome_utilizador_partilhou']);?></option>
																<?php
																}
															}
														}
													?>	
												</select>
													</p> 
								<?php
									$query_categorias=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$perfil'");
									$rows_categorias=mysqli_num_rows($query_categorias);
									if($rows_categorias==0){
										echo '<p style="width:100%; text-align:left;">Este perfil ainda não tem categorias.<br> Pode criar <a href="index.php?p=3" style="color:blue;">Aqui</a></p>';
									}else{
										?>
											<table>
											<tr class="dark: linha">
												<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Categoria</td>
												<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Tipo</td>
												<td class="font-bold tracking-wider dark:text-light">Guardar</td>
												<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Eliminar</td>
											</tr>
												
										<?php
										for($b=0;$b<$rows_categorias;$b++){
											$d_c=mysqli_fetch_array($query_categorias);
											?>
												<tr class="dark: linha">
												<form method="post" enctype="multipart/form-data" name="editar_categoria" id="editar_categoria" class="editar_categoria">
													<td class=" tracking-wider dark:text-light"><input type="text" name="id" value="<?php echo $d_c['id'];?>" style="display:none;"><input type="text" style="padding:0px; padding-left:2px; max-width:100px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" value="<?php echo base64_decode($d_c['categoria']);?>" name="categoria" required></td>
													<td class=" tracking-wider dark:text-light"> 
													<select name="tipo" style="padding:0px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														<option value="0" <?php if($d_c['tipo']==0){echo'selected';}?>>Débito</option>
														<option value="1"  <?php if($d_c['tipo']==1){echo'selected';}?>>Crédito</option>
														<option value="2"  <?php if($d_c['tipo']==2){echo'selected';}?>>Débito Pessoal</option>
													</select>
													</td>
													<td class=" tracking-wider dark:text-light"><center> <input type="submit" style=" background: #fff; background-image: url('build/images/save.png'); width:32px; height:32px; cursor: pointer; height:32px;border-radius: 5px 11px 5px 5px;" value=""></center></td>
												</form>
												<form method="post" enctype="multipart/form-data" name="eliminar_categoria" id="eliminar_categoria" class="eliminar_categoria">
													<td class=" tracking-wider dark:text-light"><center> <input type="text" name="id" value="<?php echo $d_c['id'];?>" style="display:none;"><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar esta caregoria? Todos os registos associados a esta categoria serão perdidos!')"></center></td>
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
								
								//fim da página com o perfil selecionado
							}
						}else{
							echo '<p style="padding:30px;"><b style="color:red; font-size:14pt;">Não é possivel aceder a este conteúdo!</b></p>';
						}
					}else{
						echo '<p style="padding:30px;"><b style="color:red; font-size:14pt;">Não é possivel aceder a este conteúdo!</b></p>';
					}
				}else{
			?>
			
			<!-- APRESENTACAO INICIAL-->
			<?php
				$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
				$rows_perf=mysqli_num_rows($query_perf);
				$query_perfis_partilhados=mysqli_query($conn, "SELECT partilha_perfil.id_perfil AS id_perfil, 
						perfil.nome AS nome_perfil,
						utilizadores.nome AS nome_utilizador_partilhou
						FROM partilha_perfil 
						INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
						INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
						WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
				$rows_perfis_partilhados=mysqli_num_rows($query_perfis_partilhados);
				if($rows_perf==0 && $rows_perfis_partilhados==0){
					echo '<p style="width:100%; text-align:left;">Para gerir categorias, por favor crie um perfil.<br>Poderá faze-lo <a href="index.php?p=2" style="color:blue;">aqui</a>.</p></div>';
				}else{

			?>
				<p style="margin-bottom:10px; text-align:left;">Perfil</p>
									<p style="margin:0px;">
									<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" onchange="if (this.value) window.location.href='index.php?p=6&perfil='+this.value">
									<?php
											if($rows_perf!=0){
												for($a=0;$a<$rows_perf;$a++){
													$d_p=mysqli_fetch_array($query_perf);
													echo '<option value="'.$d_p['id'].'&tipo=1">'.base64_decode($d_p['nome']).'</option>';
												}
											}
									?>
									<?php
										if($rows_perfis_partilhados!=0){
											for($f_p=0;$f_p<$rows_perfis_partilhados;$f_p++){
												$dados_perfis_p=mysqli_fetch_array($query_perfis_partilhados);
												?>
													<option value="<?php echo $dados_perfis_p['id_perfil'];?>&tipo=2"><?php echo base64_decode($dados_perfis_p['nome_perfil']);?> de <?php echo base64_decode($dados_perfis_p['nome_utilizador_partilhou']);?></option>
													
												<?php
											}
										}
									?>	
								</select>
									</p> 
				<?php
					$query_verifica_id=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
					$rows_query_verifica_id=mysqli_num_rows($query_verifica_id);
					if($rows_query_verifica_id==0){
						$query_verifica_id=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
						$d_v_id=mysqli_fetch_array($query_verifica_id);
						$id_perfil=$d_v_id['id_perfil'];
					}else{
						$d_v_id=mysqli_fetch_array($query_verifica_id);
						$id_perfil=$d_v_id['id'];
						}
					
					$query_categorias=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil'");
					$rows_categorias=mysqli_num_rows($query_categorias);
					if($rows_categorias==0){
						echo '<p style="width:100%; text-align:left;">Este perfil ainda não tem categorias.<br> Pode criar <a href="index.php?p=3" style="color:blue;">Aqui</a></p>';
					}else{
						?>
							<table>
								<tr class="dark: linha">
									<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Categoria</td>
									<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Tipo</td>
									<td class="font-bold tracking-wider dark:text-light">Guardar</td>
									<td class="font-bold tracking-wider dark:text-light" style="font-size: 11pt;">Eliminar</td>
								</tr>
								
						<?php
						for($b=0;$b<$rows_categorias;$b++){
							$d_c=mysqli_fetch_array($query_categorias);
							?>
								<tr class="dark: linha">
									<form method="post" enctype="multipart/form-data" name="editar_categoria" id="editar_categoria" class="editar_categoria">
										<td class=" tracking-wider dark:text-light"><input type="text" name="id" value="<?php echo $d_c['id'];?>" style="display:none;"><input type="text" style="padding:0px; padding-left:2px; max-width:100px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" value="<?php echo base64_decode($d_c['categoria']);?>" name="categoria" required></td>
										<td class=" tracking-wider dark:text-light"> 
										<select name="tipo" style="padding:0px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
											<option value="0" <?php if($d_c['tipo']==0){echo'selected';}?>>Débito</option>
											<option value="1"  <?php if($d_c['tipo']==1){echo'selected';}?>>Crédito</option>
											<option value="2"  <?php if($d_c['tipo']==2){echo'selected';}?>>Débito Pessoal</option>
										</select>
										</td>
										<td class=" tracking-wider dark:text-light"><center> <input type="submit" style=" background: #fff; background-image: url('build/images/save.png'); width:32px; height:32px; cursor: pointer; height:32px;border-radius: 5px 11px 5px 5px;" value=""></center></td>
									</form>
									<form method="post" enctype="multipart/form-data" name="eliminar_categoria" id="eliminar_categoria" class="eliminar_categoria">
										<td class=" tracking-wider dark:text-light"><center> <input type="text" name="id" value="<?php echo $d_c['id'];?>" style="display:none;"><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar esta caregoria? Todos os registos associados a esta categoria serão perdidos!')"></center></td>
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
				}}
			?>
			<!-- FIM APRESENTACAO INICIAL-->