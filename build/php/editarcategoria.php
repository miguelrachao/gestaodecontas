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
	$categoria=$_POST['categoria'];
	$categoria=base64_encode($categoria);
	$tipo=$_POST['tipo'];
	$query_uptade=mysqli_query($conn, "UPDATE categorias SET categoria='$categoria', tipo='$tipo' WHERE id='$id'");
	
	echo '1';
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>