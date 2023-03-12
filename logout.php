<?php 
session_start();
$host=$_SERVER['HTTP_HOST'];
$uri=$_SERVER['REQUEST_URI'];
$raiz="http://".$host."/Reloj/";
unset ( $_SESSION['idUsuario'] );
//session_unset();   // destruye las variables de sesion
session_destroy(); // destruye la sesion
header("Location: ".$raiz."login.php");
?>