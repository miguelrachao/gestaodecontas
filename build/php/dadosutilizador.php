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
	$email=$_POST['email'];
	$nome=base64_encode($nome);
	$email=base64_encode($email);
	$query_verifica=mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email' AND id!='$id_utilizador'");
	$rows_verifica=mysqli_num_rows($query_verifica);
	if($rows_verifica==0){
		$query_update=mysqli_query($conn,"UPDATE utilizadores SET email='$email', nome='$nome' WHERE id='$id_utilizador'");
		echo '1';
	}else{
		echo '2';
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
	
?>