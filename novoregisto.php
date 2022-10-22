<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/novoregisto.js" defer></script>
<script>
$( "#valor" ).blur(function() {
    this.value = parseFloat(this.value).toFixed(2);
});
</script>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
            <h1 class="text-xl font-semibold text-center">Novo registo</h1>
				<?php
					if(isset($_SESSION['on_gdc'])){
						$id_utilizador=$_SESSION['id'];
					}else{
						$id_utilizador=$_COOKIE['id'];
					}
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
							<form method="post" class="space-y-6" style="text-align:left;" name="novoregisto_form" id="novoregisto_form">
							<center><div class="alert" id="alerta"> </div></center>
							<!-- Permite adicionar registos em perfil partilhado-->
							<p style="margin-bottom:10px;">Perfil</p>
							<p style="margin:0px;">
								<select name="id_perfil"  onchange="if (this.value) window.location.href='index.php?p=1&perfil='+this.value" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker">
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
												<option value="0">Selecione a categoria</option>
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
									<p style="margin-bottom:10px;">Valor</p>
									<p style="margin:0px;">
										<input
											class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
											type="number"
											name="valor"
											placeholder="Valor"
											id="valor"
											required
											autocomplete="off"
											step="0.01"
										/>
									</p>
									<p style="margin-bottom:10px;">Detalhes</p>
									<p style="margin:0px;">
									<input
										class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
										type="text"
										name="detalhes"
										placeholder="Detalhes"
										id="detalhes"
										autocomplete="off"
									/>
									</p>
									<p style="margin-bottom:10px;">Data</p>
									<p style="margin:0px;">
										<input
											id="data"
											class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
											type="datetime-local"
											name="data"
											class="date"
											autocomplete="off"
										/>
										<script> //adiciona valor data ao campo
											window.addEventListener('load', () => {
											const now = new Date();
											now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
											now.setSeconds(null);
											now.setMilliseconds(null);
											document.getElementById('data').value = now.toISOString().slice(0, -1);
											});
										</script>
									</p>
									<div>
									<button
									type="submit"
									class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
									>
									Registar
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
							<form method="post" class="space-y-6" style="text-align:left;" name="novoregisto_form" id="novoregisto_form">
							<center><div class="alert" id="alerta"> </div></center>
							<!-- Permite adicionar registos em perfil -->
							<p style="margin-bottom:10px;">Perfil</p>
							<p style="margin:0px;">
								<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" onchange="if (this.value) window.location.href='index.php?p=1&perfil='+this.value">
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
												<option value="0">Selecione a categoria</option>
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
									<p style="margin-bottom:10px;">Valor</p>
									<p style="margin:0px;">
										<input
											class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
											type="number"
											name="valor"
											placeholder="Valor"
											id="valor"
											required
											autocomplete="off"
											step="0.01"
										/>
									</p>
									<p style="margin-bottom:10px;">Detalhes</p>
									<p style="margin:0px;">
									<input
										class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
										type="text"
										name="detalhes"
										placeholder="Detalhes"
										id="detalhes"
										autocomplete="off"
									/>
									</p>
									<p style="margin-bottom:10px;">Data</p>
									<p style="margin:0px;">
										<input
											id="data"
											class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
											type="datetime-local"
											name="data"
											class="date"
											autocomplete="off"
										/>
										<script> //adiciona valor data ao campo
											window.addEventListener('load', () => {
											const now = new Date();
											now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
											now.setSeconds(null);
											now.setMilliseconds(null);
											document.getElementById('data').value = now.toISOString().slice(0, -1);
											});
										</script>
									</p>
									<div>
									<button
									type="submit"
									name="submit"
									class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
									>
									Registar
									</button>
									<?php
										}
									?>
							</div>
							</form>
				<?php
						} 
					}
			?>
</div>