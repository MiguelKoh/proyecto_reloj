<?php

session_start();
include("conex.php");

$cn = ConectaBD();

$tipo_archivo= mysql_real_escape_string($_POST["chkop1"], $cn);
$archivo = $_FILES['excel']['name'];

//$_SESSION["archivo"] = $archivo; //este es el nombre del archivo


session_register('archivo');

if ($tipo_archivo == 1) {
    echo "<script>location.href='SubirInfoCsv.php'</script>";
} else {
    echo "<script>location.href='SubirInfoCsvPermisos.php'</script>";
}

?>
