<?php

function ConectaBD() {
/*
    //debe ser ODBC de sistema no de usuario
    $dsn = "BDReloj";
    $usuario = "";
    $clave = "";

    //realizamos la conexion mediante odbc
    if (!($link = odbc_connect($dsn, $usuario, $clave))) {
      echo "Error conectando a la base de datos.";
      exit();
      }
     */

    if (!($link = mysqli_connect("localhost", "root", ""))) {
    //if (!($link = mysql_connect("148.209.84.189", "root", "adminpr2"))) {        
        echo "Error conectando a la base de datos.";
        exit();
    }
    if (!mysqli_select_db($link,"reloj")) {
        echo "Error seleccionando la base de datos.";
        exit();
    }
    return $link;
}
?>

