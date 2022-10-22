<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<script src="build/js/background/novacategoria.js" defer></script>
<div class="w-full max-w-sm px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker" style="margin-bottom: 10px;">
            <h1 class="text-xl font-semibold text-center">Nova Categoria</h1>
			<?php
				if(isset($_SESSION['on_gdc'])){
					$id_utilizador=$_SESSION['id'];
				}else{
					$id_utilizador=$_COOKIE['id'];
				}
				$query_perf=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
				$rows_perf=mysqli_num_rows($query_perf);
				$query_perf_partilhado=mysqli_query($conn, 
				"SELECT partilha_perfil.id_perfil AS id_perfil, 
				perfil.nome AS nome_perfil,
				utilizadores.nome AS nome_utilizador_partilhou
				FROM partilha_perfil 
				INNER JOIN perfil ON partilha_perfil.id_perfil=perfil.id 
				INNER JOIN utilizadores ON perfil.id_utilizador=utilizadores.id
				WHERE utilizador_partilha='$id_utilizador' ORDER BY id_perfil DESC");
				$rows_perf_partilhado=mysqli_num_rows($query_perf_partilhado);
				if($rows_perf==0 && $rows_perf_partilhado==0){
					echo '<p style="width:100%; text-align:left;">Para gerir categorias, por favor crie um perfil.<br>Poderá faze-lo <a href="index.php?p=2" style="color:blue;">aqui</a>.</p>';
				}else{
			?>
            <form method="post" class="space-y-6" style="text-align:left;" name="novacategoria_form" id="novacategoria_form">
			    <center><div class="alert" id="alerta"> </div></center>
					<p style="margin-bottom:10px;">Perfil</p>
					<p style="margin:0px;">
					<select name="id_perfil" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							>
									<?php
											if($rows_perf!=0){
												for($a=0;$a<$rows_perf;$a++){
													$d_p=mysqli_fetch_array($query_perf);
													echo '<option value="'.$d_p['id'].'">'.base64_decode($d_p['nome']).'</option>';
												}
											}
											if($rows_perf_partilhado!=0){
												for($b=0;$b<$rows_perf_partilhado;$b++){
													$d_p_p=mysqli_fetch_array($query_perf_partilhado);
													echo '<option value="'.$d_p_p['id_perfil'].'">'.base64_decode($d_p_p['nome_perfil']).' de '.base64_decode($d_p_p['nome_utilizador_partilhou']).'</option>';
												}
											}
									?>
								</select>
					</p>  
                	<p style="margin-bottom:10px;">Nome da categoria</p>
					<p style="margin:0px;">
						<input
							class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							type="text"
							id="nome"
                            name="nome"
							placeholder="Nome da categoria"
							autocomplete="off"
							required
						/>
					</p>    
					<p style="margin-bottom:10px;">Tipo de categoria</p>
					<p style="margin:0px;">
						<select name="tipo" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
							>
							<option value="0">Débito</option>
							<option value="1">Crédito</option>
							<option value="2">Débito Pessoal</option>
						</select>
					</p>    
                    <button
						type="submit"
						class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
					>Registar
					</button>          

            </form>
			<?php
				}
			?>
</div>