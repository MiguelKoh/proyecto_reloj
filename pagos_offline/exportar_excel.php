<?php

session_start();
include("conex.php");
$con=Conectarse();

/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
$salto_linea = "\n";

//titulo reporte
$titulo="Alumnos inscritos a 2do y 3ero para curso escolar 2015-2016";

//header info for browser
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$titulo.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $titulo.$salto_linea.$salto_linea;

//nombre de columnas
$nombre_campos = "Folio".$sep."Nombres".$sep."Apellido Paterno".$sep."Apellido Materno".$sep."Curso".$sep."Direccion".$sep."Email".$sep."Telefono1".$sep."Telefono2".$sep."Ficha Pago".$salto_linea;
echo $nombre_campos;
 

//Items que voy a presentar

$sql = "select Folio,convert(cast(convert(Nombres using latin1) as binary) using utf8) AS Nombres,convert(cast(convert(ApePat using latin1) as binary) using utf8) AS ApePat,convert(cast(convert(ApeMat using latin1) as binary) using utf8) AS ApeMat,Curso,Direccion,".
         "Correo,Tel1,Tel2,tipo,movimiento ".
         "from aspirantes as a order by Folio";
$result = mysql_query($sql,$con);

//Impresion de la informacion
while($row = mysql_fetch_array($result)) //mysql_fetch_row
{
    echo $row['Folio'].$sep.strtoupper($row['Nombres']).$sep.strtoupper($row['ApePat']).$sep.strtoupper($row['ApeMat']).$sep.$row['Curso'].
            $sep.strtoupper($row['Direccion']).$sep.$row['Correo'].$sep.$row['Tel1'].$sep.$row['Tel2'].$sep.$row['movimiento'];
    echo $salto_linea;
   
}        
              
   

?>