<?php
//logout sessao normal
session_start();
session_destroy();
//logout sessao permanente
setcookie('session_logged_gdc', '', time() - 3600, '/');
setcookie('id', '', time() - 3600, '/');
setcookie('nome', '', time() - 3600, '/');
//redirecionamento pagina inicial
header("Location: index.php")
?>