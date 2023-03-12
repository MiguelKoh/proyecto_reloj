<?php

session_start();
include('conex.php');

if (!(isset($_SESSION["iddepto"]))) {
    echo "<script>location.href='index.php'</script>";
}

$cn = ConectaBD();
$idDepto = $_SESSION["iddepto"];

//obtengo nombre del departamento
$sente = "select nombre from departamento where iddepto = " .$idDepto;
$result_depto = mysql_query($sente, $cn);
$row_depto = mysql_fetch_array($result_depto);
$nomDepto = $row_depto['nombre'];


//obtener horario de empleados del departamento seleccionado
$sente = "select idEmp,nombre,fecha,horaini,horafin,checadaini1,checadafin1,ausente " .
        "from excel where departamento=" . $nomDepto;
         
$result = mysql_query($sente, $cn);
$row = mysql_fetch_array($result);
$idEmp = $row['idEmp'];
$nombre = $row['nombre'];
$fecha= $row['fecha'];
$horaini = $row['horaini'];
$horafin = $row['horafin'];
$checadaini1 = $row['checadaini1'];
$checadafin1 = $row['checadafin1'];
$ausente = $row['ausente'];
mysql_free_result($result);

?>
