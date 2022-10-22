<?php
if(isset($_POST['tipo'])){
	include('conn.php');
	session_start();
	//recebe dados
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
	$tipo=$_POST['tipo'];
	$nome=$_POST['nome'];
	$nome=base64_encode($nome);
	$id_perfil=$_POST['id_perfil'];
	$favorito=$_POST['favorito'];
	if($tipo==1){
		if(isset($_POST['favorito'])){
			$query_update=mysqli_query($conn, "UPDATE perfil SET favorito='0' WHERE id_utilizador='$id_utilizador'");
			$query_update_1=mysqli_query($conn, "UPDATE partilha_perfil SET favorito='0' WHERE utilizador_partilha='$id_utilizador'");
			$query_uptade_2=mysqli_query($conn, "UPDATE perfil SET favorito='1', nome='$nome' WHERE id='$id_perfil'");
		}else{
			$query_uptade_3=mysqli_query($conn, "UPDATE perfil SET nome='$nome' WHERE id='$id_perfil'");
		}
	}else{
		if(isset($_POST['favorito'])){
			$query_update=mysqli_query($conn, "UPDATE perfil SET favorito='0' WHERE id_utilizador='$id_utilizador'");
			$query_update_1=mysqli_query($conn, "UPDATE partilha_perfil SET favorito='0' WHERE id_utilizador='$id_utilizador'");
			$query_uptade_2=mysqli_query($conn, "UPDATE partilha_perfil SET favorito='1' WHERE id_perfil='$id_perfil'");
			$query_uptade_2=mysqli_query($conn, "UPDATE perfil SET nome='$nome' WHERE id='$id_perfil'");
		}else{
			$query_uptade_2=mysqli_query($conn, "UPDATE perfil SET nome='$nome' WHERE id='$id_perfil'");
		}
	}
	echo '1';
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>