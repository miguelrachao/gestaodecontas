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
    // elimia partilha de perfil
    $query_delete=mysqli_query($conn, "DELETE FROM partilha_perfil WHERE id='$id' AND id_utilizador='$id_utilizador'");
    echo '1';
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>