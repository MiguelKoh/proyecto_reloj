<?php

session_start();
session_unset();

//$_POST = array();

include("conex.php");

$cn = ConectaBD();
$idemp= "";

//if (isset($_POST["idemp"], $cn)){
//   $idemp = mysql_real_escape_string($_POST["idemp"], $cn); 
//}

if (isset($_POST["lstEmpleado"], $cn)){
   $idemp = mysql_real_escape_string($_POST["lstEmpleado"], $cn); 
}

if (isset($_GET["lstEmpleado"], $cn)){
   $idemp = mysql_real_escape_string($_GET["lstEmpleado"], $cn); 
}

$id_periodo = mysql_real_escape_string($_POST["lstPeriodo"],$cn);

//-------------------------------------------------------------------
//obtengo fecha inicial y final, dependiendo del periodo seleccionado
$sente0 = sprintf("select fecha_inicio, fecha_fin, reinicio_contador from periodos where idperiodo =%d",$id_periodo);
$result0 = mysql_query($sente0, $cn) or die(mysql_error());
$row0 = mysql_fetch_array($result0);

$_SESSION["id_periodo"] = $id_periodo;
$_SESSION["fechaini"] = $row0['fecha_inicio'];
$_SESSION["fechafin"] = $row0['fecha_fin'];
$_SESSION["reinicio_contador"] = $row0['reinicio_contador'];

//---------------------------------------------


//$_SESSION["fechaini"] = mysql_real_escape_string($_POST["dFechaInicio"], $cn);
//$_SESSION["fechafin"] = mysql_real_escape_string($_POST["dFechaFin"], $cn);

$_SESSION["op"] = mysql_real_escape_string($_POST["chkop"], $cn);

$iddepto = mysql_real_escape_string($_POST["lstDepto"], $cn);

//$idemp = $_POST["idemp"]; 


if ($iddepto <> 0) {   // $iddepto == 0 son todos los departamentos
    // obtengo nombre depto si se selecciono uno en especial
    
    //el depto 1000 es un valor ficticio para aprovechar el codigo ya escrito
    if ($iddepto != '1000'){
        $sente1 = sprintf("select nombre from departamento where iddepto='%s'", $iddepto);
        $result1 = mysql_query($sente1, $cn) or die(mysql_error()); //("Error al buscar datos del alumno.");
        $row1 = mysql_fetch_array($result1);

        if ($row1) {
            $_SESSION["nomdepto"] = $row1['nombre'];
        }
    }else{
        $_SESSION["nomdepto"] = "SERVICIO SOCIAL";
    }
    
    if ($idemp <> 0){ // $idemp == 0 son todos los empleados
        // obtengo id empleado
        $sente1 = sprintf("select idemp, nombre, iddepto from empleado where idemp='%s'", $idemp);
        $result1 = mysql_query($sente1, $cn) or die(mysql_error()); //("Error al buscar datos del alumno.");
        $row1 = mysql_fetch_array($result1);

        if ($row1) {
            $_SESSION["idemp"] = $row1['idemp'];
            $_SESSION["nomemp"] = $row1['nombre'];
            $_SESSION["iddepto"] = $row1['iddepto'];
        }        
    }else{
        $_SESSION["idemp"] = "0";
        $_SESSION["nomemp"] = "TODOS";
        $_SESSION["iddepto"] = $iddepto;
    }    
    
}else{
    $_SESSION["nomdepto"] = "TODOS";
    $_SESSION["nomemp"] = "TODOS";
    $_SESSION["iddepto"] = "0";
    $_SESSION["idemp"] = "0";
}

echo "<tr><td><input type='text' value='".$idemp."'></td></tr>";

mysql_close($cn);

if ($_SESSION["nomemp"] != "" && $_SESSION["nomdepto"] != "" ) {
    switch ($_SESSION["op"]) {
        case "1": 
            echo "<script>location.href='retardos.php'</script>";
            break;
        case "2": 
            echo "<script>location.href='retardos.php'</script>";
            break;
        case "3": 
            echo "<script>location.href='horas_extras.php'</script>";
            break;
        case "4": 
            echo "<script>location.href='horas_trabajadas.php'</script>";
            break;    
    }
} else {
    echo "<script>location.href='menu.php'</script>";
}

?>
