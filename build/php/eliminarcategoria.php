<?php
if(isset($_POST['id'])){
	include('conn.php');
	session_start();
	//recebe dados

    $id=$_POST['id'];
	$query_delete=mysqli_query($conn, "DELETE FROM categorias WHERE id='$id'");
	$query_delete2=mysqli_query($conn, "DELETE FROM registos WHERE id_categoria='$id'");
    echo '1';
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>