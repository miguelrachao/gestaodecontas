<?php
if(isset($_POST['id_registo'])){
	include('conn.php');
	session_start();
	//recebe dados

    $id=$_POST['id_registo'];
	$query_delete=mysqli_query($conn, "DELETE FROM registos WHERE id='$id'");
    echo '1';
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>