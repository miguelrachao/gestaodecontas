<?php
	//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/eliminarregisto.js" defer></script>
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
					date_default_timezone_set('Europe/London');
					if(isset($_SESSION['on_gdc'])){
						$id_utilizador=$_SESSION['id'];
					}else{
						$id_utilizador=$_COOKIE['id'];
					}
					if(isset($_GET['search'])){
						$id_perfil_post=$_GET['id_perfil'];
						$id_categoria_post=$_GET['id_categoria'];
						$ano_post=$_GET['ano'];
						$mes_post=$_GET['mes'];
						$query_1=mysqli_query($conn, "SELECT * FROM perfil WHERE id='$id_perfil_post' AND id_utilizador='$id_utilizador'");
						$rows_1=mysqli_num_rows($query_1);
						if($rows_1==0){
							//nao encntrou o perfil, verifica se é partilhado
							$query_2=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE id_perfil='$id_perfil_post' AND utilizador_partilha='$id_utilizador'");
							$rows_2=mysqli_num_rows($query_2);
							if($rows_2==0){
								//erro, perfil nao encontrado 
								?>
								<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px; width:100%;">
								<p style="width:100%; text-align:left; margin-left: 10px;"><a href="index.php?p=7"><img src="build/images/voltar.png"></p>
								<h1 class="text-xl font-semibold text-center" style="margin-top:-30px;"></a>Registos</h1>

								<?php
									echo '<p style="padding:30px;"><b style="color:red; font-size:14pt;">Não tem acesso a estes registos!</b></p></div>';
							}else{
								//escreve dados do perfil encontrado
								?>
								<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px; width:100%;">
								<h1 class="text-xl font-semibold text-center" style="display:inline-block; padding-left:125px;">Registos <a href="imprimirregisto.php?id_perfil=<?php echo $id_perfil_post;?>&id_categoria=<?php echo $id_categoria_post;?>&ano=<?php echo $ano_post;?>&mes=<?php echo $mes_post;?>" target="new_blank()" style="display:inline-block; padding-left:90px;"><img src="build/images/print.png"></a></h1>
									
								
									<?php
										if($id_categoria_post==0){
											if($mes_post==0){
												//mostra registos de todas as categorias e do ano daquele perfil
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND data LIKE '$ano_post%' ORDER BY id DESC");
											}else{
												//mostra registos de todas as categorias e do mes em especifico daquele perfil
												$filtro=$ano_post.'-'.$mes_post;
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND data LIKE '$filtro%' ORDER BY id DESC");
											}
										}else{
											if($mes_post==0){
												//mostra registos daquela categoria do ano daquele perfil
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND id_categoria='$id_categoria_post' AND data LIKE '$ano_post%' ORDER BY id DESC");
												
											}else{
												//mostra registos daquela categoria e do mes em especifico daquele perfil
												$filtro=$ano_post.'-'.$mes_post;
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND id_categoria='$id_categoria_post' AND data LIKE '$filtro%' ORDER BY data DESC");
											}
										}
										$rows_dados_registos=mysqli_num_rows($query_dados_registos);
										if($rows_dados_registos==0){
											echo '<p><b>Sem registos!</b></p>';
										}else{
											//Aqui serão apresentados os valores
											?>
												<table>
													<tr class="dark: linha">
														<td class="font-bold tracking-wider dark:text-light">Categoria</td>
														<td class="font-bold tracking-wider dark:text-light">Detalhes</td>
														<td class="font-bold tracking-wider dark:text-light">Valor</td>
														<td class="font-bold tracking-wider dark:text-light">Data</td>
													</tr>
													<?php
														$creditos=0;
														$debitos=0;
														$debitos_pessoais=0;
														$total_debitos=0;
														for($frg=0;$frg<$rows_dados_registos;$frg++){
															$dados_registos=mysqli_fetch_array($query_dados_registos);
															$id_categoria_registo=$dados_registos['id_categoria'];
															$query_d_c=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_registo'");
															$dados_categoria_r=mysqli_fetch_array($query_d_c);
															echo '<tr class="dark: linha">';
															echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt;"><a href="index.php?p=9&id='.$dados_registos['id'].'">'.base64_decode($dados_categoria_r['categoria']).'</a></td>';
															echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt;">'.base64_decode($dados_registos['detalhes']).'</td>';
															if($dados_categoria_r['tipo']==0){
																$debitos=$debitos+base64_decode($dados_registos['valor']);
																$total_debitos=$total_debitos+base64_decode($dados_registos['valor']);
																echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt; font-weight:bold; color:red;">'.base64_decode($dados_registos['valor']).'€</td>';
															}elseif($dados_categoria_r['tipo']==1){
																$creditos=$creditos+base64_decode($dados_registos['valor']);
																echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt; font-weight:bold; color:green;">'.base64_decode($dados_registos['valor']).'€</td>';
															}elseif($dados_categoria_r['tipo']==2){
																$debitos_pessoais=$debitos_pessoais+base64_decode($dados_registos['valor']);
																$total_debitos=$total_debitos+base64_decode($dados_registos['valor']);
																echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt; font-weight:bold; color:red;">'.base64_decode($dados_registos['valor']).'€</td>';
															}
															$data = date("d/m/Y H:i", strtotime($dados_registos['data']));
															echo '<td class=" tracking-wider dark:text-light" style=" font-size: 10pt;">'.$data.'</td>';
															echo '<td class=" tracking-wider dark:text-light"><form method="post" enctype="multipart/form-data" name="eliminar_registo" id="eliminar_registo" class="eliminar_registo">';
															echo '<input type="text" name="id_registo" value="'.$dados_registos['id'].'" style="display:none;">';
															echo '<a href="index.php?p=9&id='.$dados_registos['id'].'"><img src="build/images/editar.png" style="margin-bottom:5px;"></a>';
															?><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar este registo?')"></form></td><?php
															echo '</tr>';
														}
													?>
												</table>
												<table>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Créditos</td><td style="color:green; font-weight:bold;"><?php echo $creditos; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Débitos</td><td style="color:red; font-weight:bold;"><?php echo $debitos; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Débitos Pessoais</td><td style="color:red; font-weight:bold;"><?php echo $debitos_pessoais; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Total Débitos</td><td style="color:red; font-weight:bold;"><?php echo $total_debitos; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<?php
																$diferenca=$creditos-$total_debitos;
																if($diferenca>=0){
																	echo '<td class="font-bold tracking-wider dark:text-light">Diferença</td><td style="color:green; font-weight:bold;">'.$diferenca.' €</td>';
																}else{
																	echo '<td class="font-bold tracking-wider dark:text-light">Diferença</td><td style="color:red; font-weight:bold;">'.$diferenca.' €</td>';
																}
															?>
														</tr>
												</table>
													<?php

										}
									?>
								</div>

								<?php
									$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
									$rows_perf=mysqli_num_rows($query_perf);
									$query_perf_partilhado=mysqli_query($conn, 
									"SELECT partilha_perfil.id_perfil AS id_perfil, 
									partilha_perfil.favorito AS favorito, 
									perfil.nome AS nome_perfil,
									utilizadores.nome AS nome_utilizador_partilhou
									FROM partilha_perfil 
									INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
									INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
									WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
									$rows_perf_partilhado=mysqli_num_rows($query_perf_partilhado);
								?>
								<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px; width:100%;">
								<h1 class="text-xl font-semibold text-center" ></a>Ver registos</h1>
								<form method="GET" class="space-y-6" style="text-align:left;">
									<p style="margin-bottom:10px;">Perfil</p>
									<p style="margin:0px;">
										<input type="text" name="p" value="7" style="display:none;">
										<input type="text" name="search" value="1" style="display:none;">
										<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" onchange="if (this.value) window.location.href='index.php?p=7&perfil='+this.value">
											<?php
													if($rows_perf!=0){
														for($a=0;$a<$rows_perf;$a++){
															$d_p=mysqli_fetch_array($query_perf);
															if($d_p['id']==$id_perfil_post){
																echo '<option value="'.$d_p['id'].'" selected>'.base64_decode($d_p['nome']).'</option>';
															}else{
																echo '<option value="'.$d_p['id'].'">'.base64_decode($d_p['nome']).'</option>';
															}
															

														}
													}
													if($rows_perf_partilhado!=0){
														for($b=0;$b<$rows_perf_partilhado;$b++){
															$d_p_p=mysqli_fetch_array($query_perf_partilhado);
															if($d_p_p['id_perfil']==$id_perfil_post){
																echo '<option value="'.$d_p_p['id_perfil'].'" selected>'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
															}else{
																echo '<option value="'.$d_p_p['id_perfil'].'">'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
															}
														}
													}
											?>
										</select>
									</p>
									<!-- Selecionar a categoria do registo consoante o perfil -->
									<p style="margin-bottom:10px;">Categoria</p>
									<p style="margin:0px;">
										<?php
												$query_c=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil_post'");
												$rows_c=mysqli_num_rows($query_c);
												if($rows_c==0){
													echo '<p style="width:100%; text-align:left;">Este perfil ainda não tem categorias.<br>Pode criar <a href="index.php?p=3" style="color:blue;">Aqui</a></p>';
												}else{
										?>
													<select name="id_categoria" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														<option value="0" <?php if($id_categoria_post==0){echo 'selected';}?>>Todas</option>
														<?php
																for($c=0;$c<$rows_c;$c++){
																	$d_c=mysqli_fetch_array($query_c);
																	if($d_c['id']==$id_categoria_post){
																		echo '<option value="'.$d_c['id'].'" selected>'.base64_decode($d_c['categoria']).'</option>';
																	}else{
																		echo '<option value="'.$d_c['id'].'">'.base64_decode($d_c['categoria']).'</option>';
																	}
																	
																}
														?>
													</select>
													<?php
													}
													?>
									</p>
										<?php
											if($rows_c!=0){
												
										?>
											<p style="margin-bottom:10px;">Período</p>
												<p style="margin:0px;">
													<select name="ano" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														<?php 
														$ano=date("Y");
														$ano_de=$ano-10; 
														foreach(array_reverse(range($ano_de, $ano), TRUE) as $key => $value)
															{
																if($value==$ano_post){
																	echo '<option value="'.$value.'" selected>'.$value.'</option>';
																}else{
																	echo '<option value="'.$value.'">'.$value.'</option>';
																}
															
															}
														?>
													
													</select>
													<select name="mes" style="margin-top:5px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														<option value="01" <?php if($mes_post=='01'){ echo 'selected';} ?>>Janeiro</option>
														<option value="02" <?php if($mes_post=='02'){ echo 'selected';} ?>>Fevereiro</option>
														<option value="03" <?php if($mes_post=='03'){ echo 'selected';} ?>>Março</option>
														<option value="04" <?php if($mes_post=='04'){ echo 'selected';} ?>>Abril</option>
														<option value="05" <?php if($mes_post=='05'){ echo 'selected';} ?>>Maio</option>
														<option value="06" <?php if($mes_post=='06'){ echo 'selected';} ?>>Junho</option>
														<option value="07" <?php if($mes_post=='07'){ echo 'selected';} ?>>Julho</option>
														<option value="08" <?php if($mes_post=='08'){ echo 'selected';} ?>>Agosto</option>
														<option value="09" <?php if($mes_post=='09'){ echo 'selected';} ?>>Setembro</option>
														<option value="10" <?php if($mes_post=='10'){ echo 'selected';} ?>>Outubro</option>
														<option value="11" <?php if($mes_post=='11'){ echo 'selected';} ?>>Novembro</option>
														<option value="12" <?php if($mes_post=='12'){ echo 'selected';} ?>>Dezembro</option>
														<option value="0" <?php if($mes_post=='0'){ echo 'selected';} ?>>Todos</option>
													</select>
												</p>
											<div>
											<button
											type="submit"
											class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
											>
											Filtrar
											</button>
											<?php
												}
											?>
									</div>
									</form>
								<?php
									}
							
						}else{
							//escreve dados do perfil encontrado
							?>
								<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px; width:100%;">
								<h1 class="text-xl font-semibold text-center" style="display:inline-block; padding-left:125px;">Registos <a href="imprimirregisto.php?id_perfil=<?php echo $id_perfil_post;?>&id_categoria=<?php echo $id_categoria_post;?>&ano=<?php echo $ano_post;?>&mes=<?php echo $mes_post;?>" target="new_blank()" style="display:inline-block; padding-left:90px;"><img src="build/images/print.png"></a></h1>
									<?php
										if($id_categoria_post==0){
											if($mes_post==0){
												//mostra registos de todas as categorias e do ano daquele perfil
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND data LIKE '$ano_post%' ORDER BY id DESC");
											}else{
												//mostra registos de todas as categorias e do mes em especifico daquele perfil
												$filtro=$ano_post.'-'.$mes_post;
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND data LIKE '$filtro%' ORDER BY id DESC");
											}
										}else{
											if($mes_post==0){
												//mostra registos daquela categoria do ano daquele perfil
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND id_categoria='$id_categoria_post' AND data LIKE '$ano_post%' ORDER BY id DESC");
												
											}else{
												//mostra registos daquela categoria e do mes em especifico daquele perfil
												$filtro=$ano_post.'-'.$mes_post;
												$query_dados_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil_post' AND id_categoria='$id_categoria_post' AND data LIKE '$filtro%' ORDER BY id DESC");
											}
										}
										$rows_dados_registos=mysqli_num_rows($query_dados_registos);
										if($rows_dados_registos==0){
											echo '<p><b>Sem registos!</b></p>';
										}else{
											//Aqui serão apresentados os valores  
											?>
												<table>
													<tr class="dark: linha">
														<td class="font-bold tracking-wider dark:text-light">Categoria</td>
														<td class="font-bold tracking-wider dark:text-light">Detalhes</td>
														<td class="font-bold tracking-wider dark:text-light">Valor</td>
														<td class="font-bold tracking-wider dark:text-light">Data</td>
													</tr>
													<?php
														$creditos=0;
														$debitos=0;
														$debitos_pessoais=0;
														$total_debitos=0;
														for($frg=0;$frg<$rows_dados_registos;$frg++){
															$dados_registos=mysqli_fetch_array($query_dados_registos);
															$id_categoria_registo=$dados_registos['id_categoria'];
															$query_d_c=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_registo'");
															$dados_categoria_r=mysqli_fetch_array($query_d_c);
															echo '<tr class="dark: linha">';
															echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt;"><a href="index.php?p=9&id='.$dados_registos['id'].'">'.base64_decode($dados_categoria_r['categoria']).'</a></td>';
															echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt;">'.base64_decode($dados_registos['detalhes']).'</td>';
															if($dados_categoria_r['tipo']==0){
																$debitos=$debitos+base64_decode($dados_registos['valor']);
																$total_debitos=$total_debitos+base64_decode($dados_registos['valor']);
																echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt; font-weight:bold; color:red;">'.base64_decode($dados_registos['valor']).'€</td>';
															}elseif($dados_categoria_r['tipo']==1){
																$creditos=$creditos+base64_decode($dados_registos['valor']);
																echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt; font-weight:bold; color:green;">'.base64_decode($dados_registos['valor']).'€</td>';
															}elseif($dados_categoria_r['tipo']==2){
																$debitos_pessoais=$debitos_pessoais+base64_decode($dados_registos['valor']);
																$total_debitos=$total_debitos+base64_decode($dados_registos['valor']);
																echo '<td class=" tracking-wider dark:text-light"  style=" font-size: 10pt; font-weight:bold; color:red;">'.base64_decode($dados_registos['valor']).'€</td>';
															}
															$data = date("d/m/Y H:i", strtotime($dados_registos['data']));
															echo '<td class=" tracking-wider dark:text-light" style=" font-size: 10pt;">'.$data.'</td>';
															echo '<td class=" tracking-wider dark:text-light"><form method="post" enctype="multipart/form-data" name="eliminar_registo" id="eliminar_registo" class="eliminar_registo">';
															echo '<input type="text" name="id_registo" value="'.$dados_registos['id'].'" style="display:none;">';
															echo '<a href="index.php?p=9&id='.$dados_registos['id'].'"><img src="build/images/editar.png" style="margin-bottom:5px;"></a>';
															?><input type="submit" style=" background: #fff; background-image: url('build/images/delete.png'); width:32px; height:32px; cursor: pointer; border-radius:12px;" value="" onclick="return confirm('Tem a certeza que deseja eliminar este registo?')"></form></td><?php
															echo '</tr>';
														}
													?>
												</table>
												<table>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Créditos</td><td style="color:green; font-weight:bold;"><?php echo $creditos; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Débitos</td><td style="color:red; font-weight:bold;"><?php echo $debitos; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Débitos Pessoais</td><td style="color:red; font-weight:bold;"><?php echo $debitos_pessoais; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<td class="font-bold tracking-wider dark:text-light">Total Débitos</td><td style="color:red; font-weight:bold;"><?php echo $total_debitos; ?>€</td>
														</tr>
														<tr class="dark: linha">
															<?php
																$diferenca=$creditos-$total_debitos;
																if($diferenca>=0){
																	echo '<td class="font-bold tracking-wider dark:text-light">Diferença</td><td style="color:green; font-weight:bold;">'.$diferenca.' €</td>';
																}else{
																	echo '<td class="font-bold tracking-wider dark:text-light">Diferença</td><td style="color:red; font-weight:bold;">'.$diferenca.' €</td>';
																}
															?>
														</tr>
												</table>
													<?php

										}
									?>
								</div>

								<?php
									$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
									$rows_perf=mysqli_num_rows($query_perf);
									$query_perf_partilhado=mysqli_query($conn, 
									"SELECT partilha_perfil.id_perfil AS id_perfil, 
									partilha_perfil.favorito AS favorito, 
									perfil.nome AS nome_perfil,
									utilizadores.nome AS nome_utilizador_partilhou
									FROM partilha_perfil 
									INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
									INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
									WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
									$rows_perf_partilhado=mysqli_num_rows($query_perf_partilhado);
								?>
								<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px; width:100%;">
								<h1 class="text-xl font-semibold text-center" ></a>Ver registos</h1>
								<form method="GET" class="space-y-6" style="text-align:left;">
									<p style="margin-bottom:10px;">Perfil</p>
									<p style="margin:0px;">
										<input type="text" name="p" value="7" style="display:none;">
										<input type="text" name="search" value="1" style="display:none;">
										<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" onchange="if (this.value) window.location.href='index.php?p=7&perfil='+this.value">
											<?php
													if($rows_perf!=0){
														for($a=0;$a<$rows_perf;$a++){
															$d_p=mysqli_fetch_array($query_perf);
															if($d_p['id']==$id_perfil_post){
																echo '<option value="'.$d_p['id'].'" selected>'.base64_decode($d_p['nome']).'</option>';
															}else{
																echo '<option value="'.$d_p['id'].'">'.base64_decode($d_p['nome']).'</option>';
															}
															

														}
													}
													if($rows_perf_partilhado!=0){
														for($b=0;$b<$rows_perf_partilhado;$b++){
															$d_p_p=mysqli_fetch_array($query_perf_partilhado);
															if($d_p_p['id_perfil']==$id_perfil_post){
																echo '<option value="'.$d_p_p['id_perfil'].'" selected>'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
															}else{
																echo '<option value="'.$d_p_p['id_perfil'].'">'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
															}
														}
													}
											?>
										</select>
									</p>
									<!-- Selecionar a categoria do registo consoante o perfil -->
									<p style="margin-bottom:10px;">Categoria</p>
									<p style="margin:0px;">
										<?php
												$query_c=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil_post'");
												$rows_c=mysqli_num_rows($query_c);
												if($rows_c==0){
													echo '<p style="width:100%; text-align:left;">Este perfil ainda não tem categorias.<br>Pode criar <a href="index.php?p=3" style="color:blue;">Aqui</a></p>';
												}else{
										?>
													<select name="id_categoria" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														<option value="0" <?php if($id_categoria_post==0){echo 'selected';}?>>Todas</option>
														<?php
																for($c=0;$c<$rows_c;$c++){
																	$d_c=mysqli_fetch_array($query_c);
																	if($d_c['id']==$id_categoria_post){
																		echo '<option value="'.$d_c['id'].'" selected>'.base64_decode($d_c['categoria']).'</option>';
																	}else{
																		echo '<option value="'.$d_c['id'].'">'.base64_decode($d_c['categoria']).'</option>';
																	}
																	
																}
														?>
													</select>
													<?php
													}
													?>
									</p>
										<?php
											if($rows_c!=0){
												
										?>
											<p style="margin-bottom:10px;">Período</p>
												<p style="margin:0px;">
													<select name="ano" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														<?php 
														$ano=date("Y");
														$ano_de=$ano-10; 
														foreach(array_reverse(range($ano_de, $ano), TRUE) as $key => $value)
															{
																if($value==$ano_post){
																	echo '<option value="'.$value.'" selected>'.$value.'</option>';
																}else{
																	echo '<option value="'.$value.'">'.$value.'</option>';
																}
															
															}
														?>
													
													</select>
													<select name="mes" style="margin-top:5px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
														<option value="01" <?php if($mes_post=='01'){ echo 'selected';} ?>>Janeiro</option>
														<option value="02" <?php if($mes_post=='02'){ echo 'selected';} ?>>Fevereiro</option>
														<option value="03" <?php if($mes_post=='03'){ echo 'selected';} ?>>Março</option>
														<option value="04" <?php if($mes_post=='04'){ echo 'selected';} ?>>Abril</option>
														<option value="05" <?php if($mes_post=='05'){ echo 'selected';} ?>>Maio</option>
														<option value="06" <?php if($mes_post=='06'){ echo 'selected';} ?>>Junho</option>
														<option value="07" <?php if($mes_post=='07'){ echo 'selected';} ?>>Julho</option>
														<option value="08" <?php if($mes_post=='08'){ echo 'selected';} ?>>Agosto</option>
														<option value="09" <?php if($mes_post=='09'){ echo 'selected';} ?>>Setembro</option>
														<option value="10" <?php if($mes_post=='10'){ echo 'selected';} ?>>Outubro</option>
														<option value="11" <?php if($mes_post=='11'){ echo 'selected';} ?>>Novembro</option>
														<option value="12" <?php if($mes_post=='12'){ echo 'selected';} ?>>Dezembro</option>
														<option value="0" <?php if($mes_post=='0'){ echo 'selected';} ?>>Todos</option>
													</select>
												</p>
											<div>
											<button
											type="submit"
											class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
											>
											Filtrar
											</button>
											<?php
												}
											?>
									</div>
									</form>
								<?php
								}
						
					}else{
					?>
							<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px; width:100%;">
							<h1 class="text-xl font-semibold text-center">Ver registos</h1>
					<?php
					if(isset($_GET['perfil'])){
						$id_perfil=$_GET['perfil'];
						$query_verifica_identidade=mysqli_query($conn,"SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' AND id='$id_perfil'");
						$query_verifica_identidade_partilhado=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE id_perfil='$id_perfil' AND utilizador_partilha='$id_utilizador'");
						$rows_v_i=mysqli_num_rows($query_verifica_identidade);
						$rows_v_i_p=mysqli_num_rows($query_verifica_identidade_partilhado);
						
						if($rows_v_i==0 && $rows_v_i_p==0){
							echo '<p style="padding:30px;"><b style="color:red; font-size:14pt;">Não tem acesso a este perfil!</b></p>';
						}else{
						
						$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador'");
						$rows_perf=mysqli_num_rows($query_perf);
						$query_perf_partilhado=mysqli_query($conn, 
						"SELECT partilha_perfil.id_perfil AS id_perfil, 
						partilha_perfil.favorito AS favorito, 
						perfil.nome AS nome_perfil,
						utilizadores.nome AS nome_utilizador_partilhou
						FROM partilha_perfil 
						INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
						INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
						WHERE utilizador_partilha='$id_utilizador'");
						$rows_perf_partilhado=mysqli_num_rows($query_perf_partilhado);
						if($rows_perf==0 && $rows_perf_partilhado==0){
							echo '<p style="width:100%; text-align:left;">Para adicionar um registo, por favor crie um perfil.<br>Poderá faze-lo <a href="index.php?p=2" style="color:blue;">aqui</a></p>';
						}else{
							
					?>
							<form method="GET" class="space-y-6" style="text-align:left;" >
							<center><div class="alert" id="alerta"> </div></center>
							<input type="text" name="p" value="7" style="display:none;">
							<input type="text" name="search" value="1" style="display:none;">
							<p style="margin-bottom:10px;">Perfil</p>
							<p style="margin:0px;">
								<select name="id_perfil"  onchange="if (this.value) window.location.href='index.php?p=7&perfil='+this.value" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
									<?php
											if($rows_perf!=0){
												for($a=0;$a<$rows_perf;$a++){
													$d_p=mysqli_fetch_array($query_perf);
													if($d_p['id']==$id_perfil){
														echo '<option value="'.$d_p['id'].'" selected>'.base64_decode($d_p['nome']).'</option>';
													}else{
														echo '<option value="'.$d_p['id'].'">'.base64_decode($d_p['nome']).'</option>';
													}
													

												}
											}
											if($rows_perf_partilhado!=0){
												for($b=0;$b<$rows_perf_partilhado;$b++){
													$d_p_p=mysqli_fetch_array($query_perf_partilhado);
													if($d_p_p['id_perfil']==$id_perfil){
														echo '<option value="'.$d_p_p['id_perfil'].'" selected>'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
													}else{
														echo '<option value="'.$d_p_p['id_perfil'].'">'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
													}
												}
											}
									?>
								</select>
							</p>
							<!-- Selecionar a categoria do registo consoante o perfil -->
							<p style="margin-bottom:10px;">Categoria</p>
							<p style="margin:0px;">
								<?php
										$query_c=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil'");
										$rows_c=mysqli_num_rows($query_c);
										if($rows_c==0){
											echo '<p style="width:100%; text-align:left;">Este perfil ainda não tem categorias.<br>Pode criar <a href="index.php?p=3" style="color:blue;">Aqui</a></p>';
										}else{
								?>
											<select name="id_categoria" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
											<option value="0">Todas</option>
												<?php
														for($c=0;$c<$rows_c;$c++){
															$d_c=mysqli_fetch_array($query_c);
															echo '<option value="'.$d_c['id'].'">'.base64_decode($d_c['categoria']).'</option>';
														}
												?>
											</select>
											<?php
											}
											?>
							</p>
								<?php
									if($rows_c!=0){
								?>
									<p style="margin-bottom:10px;">Período</p>
										<p style="margin:0px;">
											<select name="ano" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
												<?php 
												$ano=date("Y");
												$ano_de=$ano-10; 
												foreach(array_reverse(range($ano_de, $ano), TRUE) as $key => $value)
													{
													  echo '<option value="'.$value.'">'.$value.'</option>';
													}
												?>
											
											</select>
											<select name="mes" style="margin-top:5px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
												<option value="01" <?php if(date("m")=='01'){ echo 'selected';} ?>>Janeiro</option>
												<option value="02" <?php if(date("m")=='02'){ echo 'selected';} ?>>Fevereiro</option>
												<option value="03" <?php if(date("m")=='03'){ echo 'selected';} ?>>Março</option>
												<option value="04" <?php if(date("m")=='04'){ echo 'selected';} ?>>Abril</option>
												<option value="05" <?php if(date("m")=='05'){ echo 'selected';} ?>>Maio</option>
												<option value="06" <?php if(date("m")=='06'){ echo 'selected';} ?>>Junho</option>
												<option value="07" <?php if(date("m")=='07'){ echo 'selected';} ?>>Julho</option>
												<option value="08" <?php if(date("m")=='08'){ echo 'selected';} ?>>Agosto</option>
												<option value="09" <?php if(date("m")=='09'){ echo 'selected';} ?>>Setembro</option>
												<option value="10" <?php if(date("m")=='10'){ echo 'selected';} ?>>Outubro</option>
												<option value="11" <?php if(date("m")=='11'){ echo 'selected';} ?>>Novembro</option>
												<option value="12" <?php if(date("m")=='12'){ echo 'selected';} ?>>Dezembro</option>
												<option value="0">Todos</option>
											</select>
										</p>
									<div>
									<button
									type="submit"
									class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
									>
									Filtrar
									</button>
									<?php
										}
									?>
							</div>
							</form>
				<?php
						} 
							// pagina dinamica
					}}else{
						$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
						$rows_perf=mysqli_num_rows($query_perf);
						$query_perf_partilhado=mysqli_query($conn, 
						"SELECT partilha_perfil.id_perfil AS id_perfil, 
						partilha_perfil.favorito AS favorito, 
						perfil.nome AS nome_perfil,
						utilizadores.nome AS nome_utilizador_partilhou
						FROM partilha_perfil 
						INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
						INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
						WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
						$rows_perf_partilhado=mysqli_num_rows($query_perf_partilhado);
						if($rows_perf==0 && $rows_perf_partilhado==0){
							echo '<p style="width:100%; text-align:left;">Para adicionar um registo, por favor crie um perfil.<br>Poderá faze-lo <a href="index.php?p=2" style="color:blue;">aqui</a></p>';
						}else{
							
					?>
							<form method="GET" class="space-y-6" style="text-align:left;">
							<p style="margin-bottom:10px;">Perfil</p>
							<p style="margin:0px;">
								<input type="text" name="p" value="7" style="display:none;">
								<input type="text" name="search" value="1" style="display:none;">
								<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" onchange="if (this.value) window.location.href='index.php?p=7&perfil='+this.value">
									<?php
											if($rows_perf!=0){
												for($a=0;$a<$rows_perf;$a++){
													$d_p=mysqli_fetch_array($query_perf);
													if($d_p['favorito']==1){
														$perfil_favorito=$d_p['id'];
														echo '<option value="'.$d_p['id'].'" selected>'.base64_decode($d_p['nome']).'</option>';
													}else{
														echo '<option value="'.$d_p['id'].'">'.base64_decode($d_p['nome']).'</option>';
													}
													

												}
											}
											if($rows_perf_partilhado!=0){
												for($b=0;$b<$rows_perf_partilhado;$b++){
													$d_p_p=mysqli_fetch_array($query_perf_partilhado);
													if($d_p_p['favorito']==1){
														$perfil_favorito=$d_p_p['id_perfil'];
														echo '<option value="'.$d_p_p['id_perfil'].'" selected>'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
													}else{
														echo '<option value="'.$d_p_p['id_perfil'].'">'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
													}
												}
											}
									?>
								</select>
							</p>
							<!-- Selecionar a categoria do registo consoante o perfil -->
							<p style="margin-bottom:10px;">Categoria</p>
							<p style="margin:0px;">
								<?php
										if(!isset($perfil_favorito)){
											//atribui o primeiro registo de perfil, ou perfil partilhado, como favorito
											$query_perf_fv=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
											$rows_perf_fv=mysqli_num_rows($query_perf_fv);
											if($rows_perf_fv==0){
												$query_perf_p_fv=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE utilizador_partilha='$id_utilizador' ORDER BY id DESC");
												$rows_perf_p_fv=mysqli_num_rows($query_perf_p_fv);
												if($rows_perf_p_fv!=0){
													//atribui favorito ao ultimo perfil partilhado
													$d_p_p_f=mysqli_fetch_array($query_perf_p_fv);
													$perfil_favorito=$d_p_p_f['id_perfil'];
												}
											}else{
												//atribui favorito ao ultimo perfil
												$d_p_f=mysqli_fetch_array($query_perf_fv);
												$perfil_favorito=$d_p_f['id'];
											}
										}
										$query_c=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$perfil_favorito'");
										$rows_c=mysqli_num_rows($query_c);
										if($rows_c==0){
											echo '<p style="width:100%; text-align:left;">Este perfil ainda não tem categorias.<br>Pode criar <a href="index.php?p=3" style="color:blue;">Aqui</a></p>';
										}else{
								?>
											<select name="id_categoria" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
											<option value="0">Todas</option>
												<?php
														for($c=0;$c<$rows_c;$c++){
															$d_c=mysqli_fetch_array($query_c);
															echo '<option value="'.$d_c['id'].'">'.base64_decode($d_c['categoria']).'</option>';
														}
												?>
											</select>
											<?php
											}
											?>
							</p>
								<?php
									if($rows_c!=0){
										
								?>
									<p style="margin-bottom:10px;">Período</p>
										<p style="margin:0px;">
											<select name="ano" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
												<?php 
												$ano=date("Y");
												$ano_de=$ano-10; 
												foreach(array_reverse(range($ano_de, $ano), TRUE) as $key => $value)
													{
													  echo '<option value="'.$value.'">'.$value.'</option>';
													}
												?>
											
											</select>
											<select name="mes" style="margin-top:5px;" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
												<option value="01" <?php if(date("m")=='01'){ echo 'selected';} ?>>Janeiro</option>
												<option value="02" <?php if(date("m")=='02'){ echo 'selected';} ?>>Fevereiro</option>
												<option value="03" <?php if(date("m")=='03'){ echo 'selected';} ?>>Março</option>
												<option value="04" <?php if(date("m")=='04'){ echo 'selected';} ?>>Abril</option>
												<option value="05" <?php if(date("m")=='05'){ echo 'selected';} ?>>Maio</option>
												<option value="06" <?php if(date("m")=='06'){ echo 'selected';} ?>>Junho</option>
												<option value="07" <?php if(date("m")=='07'){ echo 'selected';} ?>>Julho</option>
												<option value="08" <?php if(date("m")=='08'){ echo 'selected';} ?>>Agosto</option>
												<option value="09" <?php if(date("m")=='09'){ echo 'selected';} ?>>Setembro</option>
												<option value="10" <?php if(date("m")=='10'){ echo 'selected';} ?>>Outubro</option>
												<option value="11" <?php if(date("m")=='11'){ echo 'selected';} ?>>Novembro</option>
												<option value="12" <?php if(date("m")=='12'){ echo 'selected';} ?>>Dezembro</option>
												<option value="0">Todos</option>
											</select>
										</p>
									<div>
									<button
									type="submit"
									class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
									>
									Filtrar
									</button>
									<?php
										}
									?>
							</div>
							</form>
				<?php
						} 
					}}
			?>
</div>