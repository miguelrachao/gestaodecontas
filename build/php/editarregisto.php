<?php
if(isset($_POST['id'])){
	include('conn.php');
	session_start();
	//recebe dados
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
	$id=$_POST['id'];
	$id_categoria=$_POST['id_categoria'];
	$detalhes=$_POST['detalhes'];
	$detalhes=base64_encode($detalhes);
	$valor=$_POST['valor'];
	$valor=base64_encode($valor);
	$data=$_POST['data'];
	$query_update=mysqli_query($conn, "UPDATE registos SET id_categoria='$id_categoria', detalhes='$detalhes', valor='$valor', data='$data' WHERE id='$id'");
	echo '1';
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>