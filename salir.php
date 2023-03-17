<?php
session_start();
$host=$_SERVER['HTTP_HOST'];
$raiz="http://".$host."/proyecto_reloj/";
unset ( $_SESSION['idUsuario'] );
session_destroy(); // destruye la sesion
header("Location: ".$raiz."/login.php");

?>