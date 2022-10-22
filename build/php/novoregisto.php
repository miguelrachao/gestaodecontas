<?php
if(isset($_POST['id_perfil'])){
	include('conn.php');
	session_start();
	//recebe dados
	$id_categoria=$_POST['id_categoria'];
	if($id_categoria==0){
		echo '1';
	}
	else{
		if(isset($_SESSION['on_gdc'])){
			$id_utilizador=$_SESSION['id'];
		}else{
			$id_utilizador=$_COOKIE['id'];
		}
		$id_perfil=$_POST['id_perfil'];
		$detalhes=$_POST['detalhes'];
		$valor=$_POST['valor'];
		$detalhes=base64_encode($detalhes);
		$valor=base64_encode($valor);
		$data=$_POST['data'];
		$query_registo=mysqli_query($conn, "INSERT INTO registos(id_categoria, id_perfil, detalhes, valor, data, id_utilizador_log) VALUES('$id_categoria','$id_perfil','$detalhes','$valor','$data','$id_utilizador')");
		echo '2';
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>