<?php
if(isset($_POST['id_perfil'])){
    include('conn.php');
	session_start();
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
	$id_perfil=$_POST['id_perfil'];
	$email=$_POST['email'];
	$email=base64_encode($email);
	$query_verifica=mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email'");
	$rows_verifica=mysqli_num_rows($query_verifica);
	if($rows_verifica==0){
		echo '1';
	}else{
		$d_v=mysqli_fetch_array($query_verifica);
		$id_utilizador_p=$d_v['id'];
		if($id_utilizador_p!=$id_utilizador){
			$query_verifica_p=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE id_perfil='$id_perfil' AND id_utilizador='$id_utilizador' AND utilizador_partilha='$id_utilizador_p'");
			$rows_query_verifica_p=mysqli_num_rows($query_verifica_p);
			if($rows_query_verifica_p==0){
				//faz a partilha
				$query_insert=mysqli_query($conn, "INSERT INTO partilha_perfil(id_perfil, id_utilizador, utilizador_partilha, favorito) VALUES('$id_perfil','$id_utilizador','$id_utilizador_p','0')");
				echo '4';
			}else{
				//perfil já partilhado
				echo '2';
			}
		}else{
			//nao pode partilhar consigo mesmo
			echo '3';
		}
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>