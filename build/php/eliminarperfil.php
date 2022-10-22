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
    $id_perfil=$_POST['id_perfil'];
    if($tipo==1){
       // elimina perfil e dados associados
       $query_delete=mysqli_query($conn, "DELETE FROM categorias WHERE id_perfil='$id_perfil'");
       $query_delete2=mysqli_query($conn, "DELETE FROM partilha_perfil WHERE id_perfil='$id_perfil'");
       $query_delete3=mysqli_query($conn, "DELETE FROM perfil WHERE id='$id_perfil'");
       $query_delete4=mysqli_query($conn, "DELETE FROM registos WHERE id_perfil='$id_perfil'");
    }else{
        // elimia partilha de perfil
        $query_delete=mysqli_query($conn, "DELETE FROM partilha_perfil WHERE id_perfil='$id_perfil' AND utilizador_partilha='$id_utilizador'");
    }
    echo '1';
}else{
	Header('Location: ..\..\index.php');
	exit();
}
?>