<?php

session_start();

//$_POST = array();

include("conex.php");

$cn = ConectaBD();
//if (isset($_POST["idemp"], $cn)){
//   $idemp = mysql_real_escape_string($_POST["idemp"], $cn); 
//}

 $fechacai = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
 $fechacaf = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
 $BuscoPeriodo="SELECT idperiodo,fecha_inicio,fecha_fin FROM periodos 
 WHERE fecha_inicio='".$_POST["fechaInicio"]."' AND fecha_fin='".$_POST["fechaFin"]."'";
$queryBusca=mysqli_query($cn,$BuscoPeriodo);
$exist=mysqli_num_rows($queryBusca);
if($exist>0){


if (isset($_POST["profesorid"], $cn)){
   $idemp = mysqli_real_escape_string($cn,$_POST["profesorid"]); 
}

if (isset($_GET["profesorid"], $cn)){
   $idemp = mysqli_real_escape_string($cn,$_GET["profesorid"]); 
}

 
//$id_periodo = mysqli_real_escape_string($cn,$_POST["lstPeriodo"]);

 $fechacai = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
 $fechacaf = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
 $BuscoPeriodo="SELECT idperiodo,fecha_inicio,fecha_fin FROM periodos 
 WHERE fecha_inicio='".$_POST["fechaInicio"]."' AND fecha_fin='".$_POST["fechaFin"]."'";
$queryBusca=mysqli_query($cn,$BuscoPeriodo);
$busca=mysqli_fetch_array($queryBusca);
$id_periodo=$busca['idperiodo'];


if (isset($_POST["fechaInicio"], $cn)){
  $fechaI = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
  $_SESSION["fechaI"] = $fechaI;
}
if (isset($_POST["fechaInicio"], $cn)){
  $fechaF = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
  $_SESSION["fechaF"] = $fechaF;
}
/*$_SESSION["fechaI"] = $busca['fecha_inicio'];
$_SESSION["fechaF"] = $busca['fecha_fin'];*/


//-------------------------------------------------------------------
//obtengo fecha inicial y final, dependiendo del periodo seleccionado
 $sente0 = sprintf("select fecha_inicio, fecha_fin, reinicio_contador from periodos where idperiodo =%d",$id_periodo);
$result0 = mysqli_query($cn,$sente0) or die(mysqli_error());
$row0 = mysqli_fetch_array($result0);

$_SESSION["id_periodo"] = $id_periodo;
$_SESSION["fechaini"] = $row0['fecha_inicio'];
$_SESSION["fechafin"] = $row0['fecha_fin'];
$_SESSION["reinicio_contador"] = $row0['reinicio_contador'];
$_SESSION["reinicio_contador_minutos"] = $row0['reinicio_contador'];

//---------------------------------------------


//$_SESSION["fechaini"] = mysql_real_escape_string($_POST["dFechaInicio"], $cn);
//$_SESSION["fechafin"] = mysql_real_escape_string($_POST["dFechaFin"], $cn);
$_SESSION["op"] = mysqli_real_escape_string($cn,$_POST["chkop"]);

$iddepto = mysqli_real_escape_string($cn,$_POST["lstDepto"]);


//$idemp = $_POST["idemp"]; 
if ($iddepto <> 0) {   
// $iddepto == 0 son todos los departamentos
    // obtengo nombre depto si se selecciono uno en especial
    
    //el depto 1000 es un valor ficticio para aprovechar el codigo ya escrito
    if ($iddepto != '1000'){
        $sente1 = sprintf("select nombre from departamento where iddepto='%s'", $iddepto);
        $result1 = mysqli_query($cn,$sente1) or die(mysqli_error()); //("Error al buscar datos del alumno.");
        $row1 = mysqli_fetch_array($result1);

        if ($row1) {
            $_SESSION["nomdepto"] = $row1['nombre'];
        }
    }else{
        $_SESSION["nomdepto"] = "SERVICIO SOCIAL";
    }
    if ($idemp <> 0){ // $idemp == 0 son todos los empleados
        // obtengo id empleado
        $sente1 = sprintf("select idemp, nombre, iddepto from empleado where idemp='%s'", $idemp);
        $result1 = mysqli_query($cn,$sente1) or die(mysqli_error()); //("Error al buscar datos del alumno.");
        $row1 = mysqli_fetch_array($result1);

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

 "<tr><td><input type='text' value='".$idemp."'></td></tr>";

mysqli_close($cn);

if ($_SESSION["nomemp"] != "" && $_SESSION["nomdepto"] != "" ) {
    switch ($_SESSION["op"]) {
        case "1": 
            echo "<script>location.href='retardos.php'</script>";
            //echo "<script>location.href='Registros_entrada_salida.php'</script>"; //comentado el 11/03/2014 para pruebas
            break;
        case "2": 
            echo "<script>location.href='retardos.php'</script>";
            break;
        case "3": 
            echo "<script>location.href='horas_extras.php'</script>";
            break;
        case "4": 
            echo "<script>location.href='horas_trabajadas.php'</script>"; //servicio social
            break;    
    }
} else {
    echo "<script>location.href='main.php'</script>";
}
}else{
    echo '<script>alert("Verifique:\n -Las fechas no coinciden con una quincena\nIntente de nuevo.")</script>';
    echo "<script>location.href='main.php'</script>"; 
}

?>
