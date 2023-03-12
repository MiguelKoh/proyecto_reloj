<?php
session_start();
$host=$_SERVER['HTTP_HOST'];
$uri=$_SERVER['REQUEST_URI'];
$raiz="http://".$host."/Reloj/";


if (($_SESSION['Access']) and ($_SESSION['idUsuario'] <> "")) {
  $idUsuario = $_SESSION['idUsuario'];  
  $nombre_completo_usuario = $_SESSION['NombreCompleto'];
} else {

  header("Location: ".$raiz."login.php");
  echo "Acceso no autorizado";
  die;

}
?>