<?php
if(isset($_POST['nome'])){
	include('conn.php');
	session_start();
	//recebe dados
	$nome=$_POST['nome'];
	$nome=base64_encode($nome);
	$email=$_POST['email'];
	$email=base64_encode($email);
	$password=$_POST['password'];
	$query_verifica=mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email'");
	$rows_verifica=mysqli_num_rows($query_verifica);
	if($rows_verifica==0){
		$query_insert=mysqli_query($conn, "INSERT INTO utilizadores(nome, email, password) VALUES('$nome','$email',md5('$password'))");
		echo '1';
	}else{
		echo '2';
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>