<?php
if(isset($_POST['id_perfil'])){
	include('conn.php');
	session_start();
	//recebe dados
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
	$id_perfil=$_POST['id_perfil'];
	$nome=$_POST['nome'];
	$nome=base64_encode($nome);
	$tipo=$_POST['tipo'];
	$query_perfil_v=mysqli_query($conn,"SELECT * FROM categorias WHERE categoria='$nome' AND id_perfil='$id_perfil'");
	$rows_perfil_v=mysqli_num_rows($query_perfil_v);
	if($rows_perfil_v!=0){
		echo '1';
	}
	else{
		$query_registo=mysqli_query($conn, "INSERT INTO categorias(categoria, tipo, id_utilizador, id_perfil) VALUES('$nome', '$tipo', '$id_utilizador', '$id_perfil')");
		echo '2';
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>