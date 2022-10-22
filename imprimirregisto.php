<html>
    <head>
        <style>
            table tr td{
                padding:5px;
                border: 1px solid gray;
            }
            body table{
        
                text-align:center;
            }
            body{
                text-align:center;
                font-size: 13pt;
                font-family: arial;
            }
        </style>
    </head>
    <body onload="window.print()">
        <center>
        <h2>Gestão de contas</h2>
            <?php
                include('build/php/conn.php');
                session_start();
                if((isset($_COOKIE['session_logged_gdc'])) || (isset($_SESSION['on_gdc']))) {
                    if(isset($_SESSION['on_gdc'])){
                        $id_utilizador=$_SESSION['id'];
                    }else{
                        $id_utilizador=$_COOKIE['id'];
                    }
                    $id_perfil=$_GET['id_perfil'];
                    $id_categoria=$_GET['id_categoria'];
                    $ano=$_GET['ano'];
                    $mes=$_GET['mes'];
                    $query_perfil=mysqli_query($conn, "SELECT * FROM perfil WHERE id='$id_perfil' AND id_utilizador='$id_utilizador'");
                    $rows_perfil=mysqli_num_rows($query_perfil);
                    if($rows_perfil==0){
                        $query_perfil_partilhado=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE id_perfil='$id_perfil' AND utilizador_partilha='$id_utilizador'");
                        $rows_perfil_partilhado=mysqli_num_rows($query_perfil_partilhado);
                        if($rows_perfil_partilhado==0){
                            $perfil_encontrado=0;
                        }else{
                            $perfil_encontrado=2;
                        }
                    }else{
                        $perfil_encontrado=1;
                    }
                    if($perfil_encontrado==0){
                        echo '<h1>Perfil não encontrado!</h1>';
                    }elseif($perfil_encontrado==1){
                        if($id_categoria==0){
                            if($mes==0){
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$ano%' ORDER BY data ASC");
                            }else{
                                $filtro=$ano.'-'.$mes;
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%' ORDER BY data ASC");
                            }
                        }else{
                            if($mes==0){
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND id_categoria='$id_categoria' AND data LIKE '$ano%' ORDER BY data ASC");
                            }else{
                                $filtro=$ano.'-'.$mes;
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND id_categoria='$id_categoria' AND data LIKE '$filtro%' ORDER BY data ASC");
                            }
                        }
                        $rows_registos=mysqli_num_rows($query_registos);
                        if($rows_registos==0){
                            echo '<h1>Nenhum registo encontrado!</h1>';
                        }else{
                            if($id_categoria==0){
                                $categoria="Todas";
                            }else{
                                $query_categoria=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria'");
                                $dados_categoria=mysqli_fetch_array($query_categoria);
                                $categoria=base64_decode($dados_categoria['categoria']);
                            }
                            if($mes==0){
                                echo '<h4>Registos da categoria <b>'.$categoria.'</b> do ano <b>'.$ano.'</b></h4>';
                            }else{
                                if($mes=="01"){
                                    $mes="Janeiro";
                                }else if($mes=="02"){
                                    $mes="Fevereiro";
                                }else if($mes=="03"){
                                    $mes="Março";
                                }else if($mes=="04"){
                                    $mes="Abril";
                                }else if($mes=="05"){
                                    $mes="Maio";
                                }else if($mes=="06"){
                                    $mes="Junho";
                                }else if($mes=="07"){
                                    $mes="Julho";
                                }else if($mes=="08"){
                                    $mes="Agosto";
                                }else if($mes=="09"){
                                    $mes="Setembro";
                                }else if($mes=="10"){
                                    $mes="Outubro";
                                }else if($mes=="11"){
                                    $mes="Novembro";
                                }else if($mes=="12"){
                                    $mes="Dezembro";
                                }
                                echo '<h4 style="padding-top:10px;">Registos da categoria <b>'.$categoria.'</b> do ano <b>'.$ano.'</b>, mês de <b>'.$mes.'</b></h4>';
                            }
                            if($id_categoria==0){
                                echo '<table><tr><td style="font-weight: bold;">Categoria</td><td style="font-weight: bold;">Detalhes</td><td style="font-weight: bold;">Valor</td><td style="font-weight: bold;">Data</td></tr>';
                            }else{
                                echo '<table><tr><td>Detalhes</td><td>Valor</td><td>Data</td></tr>';
                            }
                            
                                if($id_categoria==0){
                                    $creditos_total=0;
                                    $debitos_total=0;
                                    for($fr=0;$fr<$rows_registos;$fr++){
                                        $dados_registo=mysqli_fetch_array($query_registos);
                                        $id_categoria=$dados_registo['id_categoria'];
                                        $query_categoria_especifica=mysqli_query($conn,"SELECT * FROM categorias WHERE id='$id_categoria'");
                                        $dados_categoria_especifica=mysqli_fetch_array($query_categoria_especifica);
                                        if($dados_categoria_especifica['tipo']==0 || $dados_categoria_especifica['tipo']==2){
                                            $debitos_total=$debitos_total+base64_decode($dados_registo['valor']);
                                        }else{
                                            $creditos_total=$creditos_total+base64_decode($dados_registo['valor']);
                                        }
                                        echo '<tr><td>'.base64_decode($dados_categoria_especifica['categoria']).'</td><td>'.base64_decode($dados_registo['detalhes']).'</td><td>'.base64_decode($dados_registo['valor']).' €</td><td>'.$dados_registo['data'].'</td></tr>';
                                    }
                                    }else{
                                        $total=0;
                                        for($fr=0;$fr<$rows_registos;$fr++){
                                            $dados_registo=mysqli_fetch_array($query_registos);
                                            $total=$total+base64_decode($dados_registo['valor']);
                                            echo '<tr><td>'.base64_decode($dados_registo['detalhes']).'</td><td>'.base64_decode($dados_registo['valor']).' €</td><td>'.$dados_registo['data'].'</td></tr>';
                                        }
                                    }
                            echo '</table>';
                            if(isset($total)){
                                ?>
                                    <table style="margin-top: 30px;">
                                        <tr><td>Total: </td><td><?php echo $total; ?> €</td></tr>
                                    </table>
                                <?php
                            }else{
                                ?>
                                    <table style="margin-top: 30px;">
                                        <tr><td>Total Créditos: </td><td><?php echo $creditos_total; ?> €</td></tr>
                                        <tr><td>Total Débitos: </td><td><?php echo $debitos_total; ?> €</td></tr>
                                        <tr><td>Diferença: </td><td><?php echo $creditos_total-$debitos_total; ?> €</td></tr>
                                    </table>
                                <?php
                            }
                        }
                    }elseif($perfil_encontrado==2){
                        if($id_categoria==0){
                            if($mes==0){
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$ano%' ORDER BY data ASC");
                            }else{
                                $filtro=$ano.'-'.$mes;
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND data LIKE '$filtro%' ORDER BY data ASC");
                            }
                        }else{
                            if($mes==0){
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND id_categoria='$id_categoria' AND data LIKE '$ano%' ORDER BY data ASC");
                            }else{
                                $filtro=$ano.'-'.$mes;
                                $query_registos=mysqli_query($conn, "SELECT * FROM registos WHERE id_perfil='$id_perfil' AND id_categoria='$id_categoria' AND data LIKE '$filtro%' ORDER BY data ASC");
                            }
                        }
                        $rows_registos=mysqli_num_rows($query_registos);
                        if($rows_registos==0){
                            echo '<h1>Nenhum registo encontrado!</h1>';
                        }else{
                            if($id_categoria==0){
                                $categoria="Todas";
                            }else{
                                $query_categoria=mysqli_query($conn, "SELECT * FROM categorias WHERE id='$id_categoria'");
                                $dados_categoria=mysqli_fetch_array($query_categoria);
                                $categoria=base64_decode($dados_categoria['categoria']);
                            }
                            if($mes==0){
                                echo '<h4>Registos da categoria <b>'.$categoria.'</b> do ano <b>'.$ano.'</b></h4>';
                            }else{
                                if($mes=="01"){
                                    $mes="Janeiro";
                                }else if($mes=="02"){
                                    $mes="Fevereiro";
                                }else if($mes=="03"){
                                    $mes="Março";
                                }else if($mes=="04"){
                                    $mes="Abril";
                                }else if($mes=="05"){
                                    $mes="Maio";
                                }else if($mes=="06"){
                                    $mes="Junho";
                                }else if($mes=="07"){
                                    $mes="Julho";
                                }else if($mes=="08"){
                                    $mes="Agosto";
                                }else if($mes=="09"){
                                    $mes="Setembro";
                                }else if($mes=="10"){
                                    $mes="Outubro";
                                }else if($mes=="11"){
                                    $mes="Novembro";
                                }else if($mes=="12"){
                                    $mes="Dezembro";
                                }
                                echo '<h4 style="padding-top:10px;">Registos da categoria <b>'.$categoria.'</b> do ano <b>'.$ano.'</b>, mês de <b>'.$mes.'</b></h4>';
                            }
                            if($id_categoria==0){
                                echo '<table><tr><td style="font-weight: bold;">Categoria</td><td style="font-weight: bold;">Detalhes</td><td style="font-weight: bold;">Valor</td><td style="font-weight: bold;">Data</td></tr>';
                            }else{
                                echo '<table><tr><td>Detalhes</td><td>Valor</td><td>Data</td></tr>';
                            }
                            
                                if($id_categoria==0){
                                    $creditos_total=0;
                                    $debitos_total=0;
                                    for($fr=0;$fr<$rows_registos;$fr++){
                                        $dados_registo=mysqli_fetch_array($query_registos);
                                        $id_categoria=$dados_registo['id_categoria'];
                                        $query_categoria_especifica=mysqli_query($conn,"SELECT * FROM categorias WHERE id='$id_categoria'");
                                        $dados_categoria_especifica=mysqli_fetch_array($query_categoria_especifica);
                                        if($dados_categoria_especifica['tipo']==0 || $dados_categoria_especifica['tipo']==2){
                                            $debitos_total=$debitos_total+base64_decode($dados_registo['valor']);
                                        }else{
                                            $creditos_total=$creditos_total+base64_decode($dados_registo['valor']);
                                        }
                                        echo '<tr><td>'.base64_decode($dados_categoria_especifica['categoria']).'</td><td>'.base64_decode($dados_registo['detalhes']).'</td><td>'.base64_decode($dados_registo['valor']).' €</td><td>'.$dados_registo['data'].'</td></tr>';
                                    }
                                    }else{
                                        $total=0;
                                        for($fr=0;$fr<$rows_registos;$fr++){
                                            $dados_registo=mysqli_fetch_array($query_registos);
                                            $total=$total+base64_decode($dados_registo['valor']);
                                            echo '<tr><td>'.base64_decode($dados_registo['detalhes']).'</td><td>'.base64_decode($dados_registo['valor']).' €</td><td>'.$dados_registo['data'].'</td></tr>';
                                        }
                                    }
                            echo '</table>';
                            if(isset($total)){
                                ?>
                                    <table style="margin-top: 30px;">
                                        <tr><td>Total: </td><td><?php echo $total; ?> €</td></tr>
                                    </table>
                                <?php
                            }else{
                                ?>
                                    <table style="margin-top: 30px;">
                                        <tr><td>Total Créditos: </td><td><?php echo $creditos_total; ?> €</td></tr>
                                        <tr><td>Total Débitos: </td><td><?php echo $debitos_total; ?> €</td></tr>
                                        <tr><td>Diferença: </td><td><?php echo $creditos_total-$debitos_total; ?> €</td></tr>
                                    </table>
                                <?php
                            }
                        }
                    }
                }else{
                    echo '<h1>Não tem acesso a esta página!</h1>';
                }
            ?>
        </center>
     </body>
</html>