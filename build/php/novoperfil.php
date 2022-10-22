<?php
if(isset($_POST['nome'])){
	include('conn.php');
	session_start();
	//recebe dados
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
	$nome=$_POST['nome'];
	$nome=base64_encode($nome);
	$query_perfil_v=mysqli_query($conn,"SELECT * FROM perfil WHERE nome='$nome' AND id_utilizador='$id_utilizador'");
	$rows_perfil_v=mysqli_num_rows($query_perfil_v);
	if($rows_perfil_v!=0){
		echo '1';
	}
	else{
		$query_verifica_perfil_f=mysqli_query($conn, "SELECT * FROM perfil WHERE id_utilizador='$id_utilizador'");
		$rows_verifica_perfil_f=mysqli_num_rows($query_verifica_perfil_f);
		$query_verifica_perfil_f_p=mysqli_query($conn, "SELECT * FROM partilha_perfil WHERE utilizador_partilha='$id_utilizador'");
		$rows_verifica_perfil_f_p=mysqli_num_rows($query_verifica_perfil_f_p);
		if($rows_verifica_perfil_f==0 && $rows_verifica_perfil_f_p==0){
			$favorito=1;
		}else{
			$favorito=0;
		}
		$query_registo=mysqli_query($conn, "INSERT INTO perfil(nome, id_utilizador, favorito) VALUES('$nome','$id_utilizador','$favorito')");
		echo '2';
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>