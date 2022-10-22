<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index" && $url!="/" ){
    Header('Location: index.php');
		exit();
	}
?>
<?php
                  date_default_timezone_set('Europe/London');
              		if(isset($_SESSION['on_gdc'])){
                    $id_utilizador=$_SESSION['id'];
                  }else{
                    $id_utilizador=$_COOKIE['id'];
                  }
                  $query_perfil=mysqli_query($conn, "SELECT * FROM perfil WHERE favorito='1' AND id_utilizador='$id_utilizador'");
                  $rows_perfil=mysqli_num_rows($query_perfil);
                  if($rows_perfil==0){
                    $query_perfil=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE favorito='1' AND utilizador_partilha='$id_utilizador'");
                    $rows_perfil=mysqli_num_rows($query_perfil);
                    if($rows_perfil==0){
                      //verifica se tem perfis
                      $query_perfil=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador' ORDER BY id DESC");
                      $rows_perfil=mysqli_num_rows($query_perfil);
                      if($rows_perfil==0){
                        //verifica se tem perfis partilhados
                        $query_perfil=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE utilizador_partilha='$id_utilizador' ORDER BY id DESC");
                        $rows_perfil=mysqli_num_rows($query_perfil);
                        if($rows_perfil==0){
                          //nao tem perfis para mostrar, criar um
                          $id_perfil=0;
                        }else{
                          //apresenta dados do perfil partilhado
                          $dados_perfil=mysqli_fetch_array($query_perfil);
                          $id_perfil=$dados_perfil['id_perfil'];
                        }
                      }else{
                        //apresenta dados do perfil
                        $dados_perfil=mysqli_fetch_array($query_perfil);
                        $id_perfil=$dados_perfil['id'];
                      }
                    }else{
                      //apresenta dados do perfil favorito partilhado
                      $dados_perfil=mysqli_fetch_array($query_perfil);
                      $id_perfil=$dados_perfil['id_perfil'];
                    }
                  }else{
                    //apresenta dados do perfil favorito
                    $dados_perfil=mysqli_fetch_array($query_perfil);
                    $id_perfil=$dados_perfil['id'];
                  }
                  if($id_perfil==0){
                    echo '<h1 class="text-2xl font-semibold" style="margin-top:50px;">Bem vindo ao gestor de contas. <br> Para começar <a href="index.php?p=2" style="color:blue;"> crie um perfil</a></h1>';
                  }else{
                    $query_perfil_categorias=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil'");
                    $rows_categorias=mysqli_num_rows($query_perfil_categorias);
                    if($rows_categorias==0){
                        echo '<h1 class="text-2xl font-semibold" style="margin-top:50px;">Agora, para apresentar estatísticas, <a href="index.php?p=3" style="color:blue;"> crie categorias</a></h1>';
                    }else{
            ?>
            <!-- Content header -->
            <div class="flex items-center px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
              <h1 class="text-2xl font-semibold">Estatísticas</h1>
			        <a style="margin-left:10px;" href="index.php?p=1"><img src="build/images/add.png"></a>
<?PHP
$ano = date("Y");
$mes = date("m");
?>
              <a style="margin-left:10px;" href="index.php?p=7&search=1&id_perfil=<?php echo $id_perfil;?>&id_categoria=0&ano=<?php echo $ano;?>&mes=<?php echo $mes;?>"><img src="build/images/verregistos.png"></a>
            </div>
             <p style="width:100%;text-align:left;margin-top:4px; margin-left:10px; font-size:10pt; color:#bebebe;">A apresentar dados do perfil favorito, mês corrente</p>         
            <!-- Content -->
            <div class="mt-2">
              <!-- State cards -->
              <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-4">
                <?php 
                $debito_total=0;
                $credito_total=0;
                  for($f_c=0;$f_c<$rows_categorias;$f_c++){
                    $dados_categorias=mysqli_fetch_array($query_perfil_categorias);
                    if(($dados_categorias['tipo']==0) || ($dados_categorias['tipo']==2) ){
                      $valor=0;
                      $id_categoria=$dados_categorias['id'];
                      $ano = date("Y");
                      $mes = date("m");
                      $filtro=$ano.'-'.$mes;
                      $query_categoria_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_categoria='$id_categoria' AND id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                      $rows_categoria_registos=mysqli_num_rows($query_categoria_registos);
                      if($rows_categoria_registos!=0){
                        for($f_r_c=0;$f_r_c<$rows_categoria_registos;$f_r_c++)
                        {
                          $dados_categoria_registos=mysqli_fetch_array($query_categoria_registos);
                          $valor=$valor+base64_decode($dados_categoria_registos['valor']);
                        }
                      }
                    ?>
                      <!-- CATEGORIAS VALORES DÉBITOS-->
                      <a href="index.php?p=7&search=1&id_perfil=<?php echo $dados_categoria_registos['id_perfil'];?>&id_categoria=<?php echo $dados_categorias['id']; ?>&ano=<?php echo $ano;?>&mes=<?php echo $mes;?>">
                        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                            <h6
                              class="text-xl font-medium leading-none tracking-wider text-gray-500 dark:text-primary-light"
                            >
                              <?php echo base64_decode($dados_categorias['categoria']);
                              if($dados_categorias['tipo']==0 || $dados_categorias['tipo']==2){
                                $debito_total=$debito_total+$valor;
                              }else{
                                $credito_total=$credito_total+$valor;
                              }
                              ?>
                            </h6>
                            <span class="text-xl font-semibold" style="color:red;"><?php echo $valor;?> €</span>
                        </div>
                      </a>
                      <!-- FIM DA CATEGORIA-->
                    <?php
                  }
                }
                  
                  $query_perfil_categorias=mysqli_query($conn, "SELECT * FROM categorias WHERE id_perfil='$id_perfil'");
                  $rows_categorias=mysqli_num_rows($query_perfil_categorias);
                  for($f_c=0;$f_c<$rows_categorias;$f_c++){
                    $dados_categorias=mysqli_fetch_array($query_perfil_categorias);
                    if($dados_categorias['tipo']==1){
                      $valor=0;
                      $id_categoria=$dados_categorias['id'];
                      $ano = date("Y");
                      $mes = date("m");
                      $filtro=$ano.'-'.$mes;
                      $query_categoria_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_categoria='$id_categoria' AND id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                      $rows_categoria_registos=mysqli_num_rows($query_categoria_registos);
                      if($rows_categoria_registos!=0){
                        for($f_r_c=0;$f_r_c<$rows_categoria_registos;$f_r_c++)
                        {
                          $dados_categoria_registos=mysqli_fetch_array($query_categoria_registos);
                          $valor=$valor+base64_decode($dados_categoria_registos['valor']);
                        }
                      }
                    ?>
                      <!-- CATEGORIAS VALORES DÉBITOS-->
                      <a href="index.php?p=7&search=1&id_perfil=<?php echo $dados_categoria_registos['id_perfil'];?>&id_categoria=<?php echo $dados_categorias['id']; ?>&ano=<?php echo $ano;?>&mes=<?php echo $mes;?>">
                        <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                            <h6
                              class="text-xl font-medium leading-none tracking-wider text-gray-500 dark:text-primary-light"
                            >
                              <?php
                                  echo base64_decode($dados_categorias['categoria']);
                                  if($dados_categorias['tipo']==0 || $dados_categorias['tipo']==2){
                                    $debito_total=$debito_total+$valor;
                                  }else{
                                    $credito_total=$credito_total+$valor;
                                  }
                              ?>
                            </h6>
                            <span class="text-xl font-semibold" style="color:green;"><?php echo $valor;?> €</span>
                        </div>
                    </a>
                      <!-- FIM DA CATEGORIA-->
                    <?php
                  }
                }
                ?>
                <div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker">
                            <h6
                              class="text-xl font-medium leading-none tracking-wider text-gray-500 dark:text-primary-light"
                            >
                              Diferença
                            </h6>
                            <?php 
                            $diferenca=$credito_total-$debito_total;
                            if($diferenca>=0){
                            ?>
                              <span class="text-xl font-semibold" style="color:green;"> <?php echo $diferenca;?>€</span>
                            <?php
                            }else{
                              ?>
                                <span class="text-xl font-semibold" style="color:red;"> <?php echo $diferenca;?>€</span>
                              <?php
                            }
                            ?> 
                        </div>
              </div>

              <?php 
                //verificacao valores anuais debitos
                $janeiro_debito=0;
                $fevereiro_debito=0;
                $marco_debito=0;
                $abril_debito=0;
                $maio_debito=0;
                $junho_debito=0;
                $julho_debito=0;
                $agosto_debito=0;
                $setembro_debito=0;
                $outubro_debito=0;
                $novembro_debito=0;
                $dezembro_debito=0;
                $janeiro_credito=0;
                $fevereiro_credito=0;
                $marco_credito=0;
                $abril_credito=0;
                $maio_credito=0;
                $junho_credito=0;
                $julho_credito=0;
                $agosto_credito=0;
                $setembro_credito=0;
                $outubro_credito=0;
                $novembro_credito=0;
                $dezembro_credito=0;
                $ano = date("Y");
                //janeiro debito
                $filtro=$ano.'-01';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $janeiro_debito=$janeiro_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //fevereiro debito
                $filtro=$ano.'-02';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $fevereiro_debito=$fevereiro_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //marco debito
                $filtro=$ano.'-03';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $marco_debito=$marco_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //abril debito
                $filtro=$ano.'-04';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $abril_debito=$abril_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //maio debito
                $filtro=$ano.'-05';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $maio_debito=$maio_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //junho debito
                $filtro=$ano.'-06';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $junho_debito=$junho_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //julho debito
                $filtro=$ano.'-07';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $julho_debito=$julho_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //agosto debito
                $filtro=$ano.'-08';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $agosto_debito=$agosto_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //setembro debito
                $filtro=$ano.'-09';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $setembro_debito=$setembro_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //outubro debito
                $filtro=$ano.'-10';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)){
                      $outubro_debito=$outubro_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //novembro debito
                $filtro=$ano.'-11';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $novembro_debito=$novembro_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //dezembro debito
                $filtro=$ano.'-12';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if(($d_c_m['tipo']==0) || ($d_c_m['tipo']==2)  ){
                      $dezembro_debito=$dezembro_debito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //janeiro credito
                $filtro=$ano.'-01';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $janeiro_credito=$janeiro_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //fevereiro credito
                $filtro=$ano.'-02';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $fevereiro_credito=$fevereiro_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //marco credito
                $filtro=$ano.'-03';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $marco_credito=$marco_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //abril credito
                $filtro=$ano.'-04';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $abril_credito=$abril_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //maio credito
                $filtro=$ano.'-05';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $maio_credito=$maio_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //junho credito
                $filtro=$ano.'-06';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $junho_credito=$junho_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //julho credito
                $filtro=$ano.'-07';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $julho_credito=$julho_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //agosto credito
                $filtro=$ano.'-08';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $agosto_credito=$agosto_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //setembro credito
                $filtro=$ano.'-09';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $setembro_credito=$setembro_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //outubro credito
                $filtro=$ano.'-10';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $outubro_credito=$outubro_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //novembro credito
                $filtro=$ano.'-11';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $novembro_credito=$novembro_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                //dezembro credito
                $filtro=$ano.'-12';
                $query_registos_m=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%'");
                $rows_registos_m=mysqli_num_rows($query_registos_m);
                if($rows_registos_m!=0){
                  for($frm=0;$frm<$rows_registos_m;$frm++){
                    $dados_registos_m=mysqli_fetch_array($query_registos_m);
                    $id_categoria_m=$dados_registos_m['id_categoria'];
                    $query_cat_m=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria_m'");
                    $d_c_m=mysqli_fetch_array($query_cat_m);
                    if($d_c_m['tipo']==1){
                      $dezembro_credito=$dezembro_credito+base64_decode($dados_registos_m['valor']);
                    }
                  }
                }
                $diferenca_janeiro=$janeiro_credito-$janeiro_debito;
                $diferenca_fevereiro=$fevereiro_credito-$fevereiro_debito;
                $diferenca_marco=$marco_credito-$marco_debito;
                $diferenca_abril=$abril_credito-$abril_debito;
                $diferenca_maio=$maio_credito-$maio_debito;
                $diferenca_junho=$junho_credito-$junho_debito;
                $diferenca_julho=$julho_credito-$julho_debito;
                $diferenca_agosto=$agosto_credito-$agosto_debito;
                $diferenca_setembro=$setembro_credito-$setembro_debito;
                $diferenca_outubro=$outubro_credito-$outubro_debito;
                $diferenca_novembro=$novembro_credito-$novembro_debito;
                $diferenca_dezembro=$dezembro_credito-$dezembro_debito;
              ?>
              <script>
                const dados_diferenca = () => {
                    return [
                      <?php echo $diferenca_janeiro;?>,
                      <?php echo $diferenca_fevereiro;?>,
                      <?php echo $diferenca_marco;?>,
                      <?php echo $diferenca_abril;?>,
                      <?php echo $diferenca_maio;?>,
                      <?php echo $diferenca_junho;?>,
                      <?php echo $diferenca_julho;?>,
                      <?php echo $diferenca_agosto;?>,
                      <?php echo $diferenca_setembro;?>,
                      <?php echo $diferenca_outubro;?>,
                      <?php echo $diferenca_novembro;?>,
                      <?php echo $diferenca_dezembro;?>,
                    ]
                  }
                  const dados_debitos = () => {
                    return [
                      <?php echo $janeiro_debito;?>,
                      <?php echo $fevereiro_debito;?>,
                      <?php echo $marco_debito;?>,
                      <?php echo $abril_debito;?>,
                      <?php echo $maio_debito;?>,
                      <?php echo $junho_debito;?>,
                      <?php echo $julho_debito;?>,
                      <?php echo $agosto_debito;?>,
                      <?php echo $setembro_debito;?>,
                      <?php echo $outubro_debito;?>,
                      <?php echo $novembro_debito;?>,
                      <?php echo $dezembro_debito;?>,
                    ]
                  }
                  const dados_creditos = () => {
                    return [
                      <?php echo $janeiro_credito;?>,
                      <?php echo $fevereiro_credito;?>,
                      <?php echo $marco_credito;?>,
                      <?php echo $abril_credito;?>,
                      <?php echo $maio_credito;?>,
                      <?php echo $junho_credito;?>,
                      <?php echo $julho_credito;?>,
                      <?php echo $agosto_credito;?>,
                      <?php echo $setembro_credito;?>,
                      <?php echo $outubro_credito;?>,
                      <?php echo $novembro_credito;?>,
                      <?php echo $dezembro_credito;?>,
                    ]
                  }
                </script>
                  <!-- APRESENTA DIFERENÇA ANUAL-->
                  <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false }" style="margin:17px;">
                  <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Diferença Anual</h4>
                  </div>
                  <div class="relative p-4 h-72">
                    <canvas id="barChart3"></canvas>
                  </div>
                </div>
				          <!-- APRESENTA DEBITO ANUAL-->
                <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false }" style="margin:17px;">
                  <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Débitos Anual</h4>
                  </div>
                  <div class="relative p-4 h-72">
                    <canvas id="barChart"></canvas>
                  </div>
                </div>
                <!-- APRESENTA CRÉDITO ANUAL-->
                <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false }" style="margin:17px;">
                  <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Créditos Anual</h4>
                  </div>
                  <div class="relative p-4 h-72">
                    <canvas id="barChart2"></canvas>
                  </div>
                </div>

            </div>
            <?php
                  }}
            ?>