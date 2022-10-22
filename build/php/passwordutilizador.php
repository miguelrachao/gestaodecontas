<?php
if(isset($_POST['password'])){
	include('conn.php');
	session_start();
	//recebe dados
	if(isset($_SESSION['on_gdc'])){
		$id_utilizador=$_SESSION['id'];
	}else{
		$id_utilizador=$_COOKIE['id'];
	}
	$password=$_POST['password'];
	$nova_password=$_POST['nova_password'];
	$confirmar_password=$_POST['confirmar_password'];
	$query_verifica=mysqli_query($conn, "SELECT * FROM utilizadores WHERE password=md5('$password') AND id='$id_utilizador'");
	$rows_verifica=mysqli_num_rows($query_verifica);
	if($nova_password!=$confirmar_password){
		echo '2';
	}elseif($rows_verifica==0){
		echo '3';
	}else{
		$query_update=mysqli_query($conn, "UPDATE utilizadores SET password=md5('$nova_password') WHERE id='$id_utilizador'");
		echo '1';
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>