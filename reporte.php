<?php

//Llamando las librerias
require_once('http://localhost:8080/JavaBridge/java/Java.inc');
require('../php-jru/php-jru.php');

//Llamando la funcion JRU de la libreria php-jru
$jru=new PJRU();

//Ruta del reporte compilado Jasper generado por IReports
$Reporte='C:/xampp/htdocs/Reloj/reportes/report3.jasper';

//Ruta a donde deseo Guardar Mi archivo de salida Pdf
$SalidaReporte='C:/xampp/htdocs/Reloj/reportes/report3.pdf';

//Parametro en caso de que el reporte no este parametrizado
$Parametro=new java('java.util.HashMap');

//Funcion de Conexion a mi Base de datos tipo MySql
//$Conexion= new JdbcConnection("com.mysql.jdbc.Driver","jdbc:mysql://localhost/reloj e","root","adminpr2");
$Conexion= new JdbcConnection("com.mysql.jdbc.Driver","jdbc:mysql://localhost/reloj","root","adminpr2");

//Generamos la Exportacion del reporte
$jru->runReportToPdfFile($Reporte,$SalidaReporte,$Parametro,$Conexion->getConnection());

//codigo para abrir el pdf
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="' . $SalidaReporte . '"');
readfile($SalidaReporte);

//Me voy al menu principal
echo "<script>location.href='main.php'</script>";

?>