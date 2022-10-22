<?php
if(isset($_POST['email'])){
	include('conn.php');
	//recebe dados
	$email=$_POST['email'];
	$email=base64_encode($email);
	$password=$_POST['password'];
	$manter_sessao=$_POST['manter_sessao'];
	//verificacao de existencia de conta
	$query1=mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email'");
	$rows1=mysqli_num_rows($query1);
	if($rows1==0){
		echo '1';
	}else{
		//verificacao de password
		$query2=mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email' AND password=md5('$password')");
		$rows2=mysqli_num_rows($query2);
		if($rows2==0){
			echo '2';
		}else{
			//login efetuado
			session_start();
			//guarda dados em variaveis de sessão
			$dados=mysqli_fetch_array($query2);
			
			//verifica se selecionou manter sessao
			
			if($manter_sessao=="1"){
				//ativacao de cookies ilimitado (contas para 10 anos)
				setcookie("session_logged_gdc", "1", time() + (10 * 365 * 24 * 60 * 60), "/"); 
				setcookie("id", $dados['id'], time() + (10 * 365 * 24 * 60 * 60), "/");
				setcookie("nome", $dados['nome'], time() + (10 * 365 * 24 * 60 * 60), "/"); 
			}else{
				$_SESSION['on_gdc']='1';
				$_SESSION['id']=$dados['id'];
				$_SESSION['nome']=$dados['nome'];
			}
			echo '3';
			
		}
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>