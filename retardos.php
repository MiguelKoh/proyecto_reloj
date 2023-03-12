<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

include('conex.php');
include('funciones_reloj.php');

//$cn = ConectaBD();
$idDepto = $_SESSION["iddepto"];
$idEmp = $_SESSION["idemp"];
$nomEmp = $_SESSION["nomemp"];
$nomDepto = $_SESSION["nomdepto"];
$op = $_SESSION["op"];
//$op2 = $_SESSION["op2"];

$fechaini = $_SESSION["fechaini"];
$fechafin = $_SESSION["fechafin"];

//unicamente se reinicia el contador cuando el valor de $reinicio_contador=1
$reinicio_contador = $_SESSION["reinicio_contador"];
$reinicio_contador_minutos = $_SESSION["reinicio_contador_minutos"];
$id_periodo = $_SESSION["id_periodo"];
    //obtengo el tipo de reporte
$tipoRep = $op;
if ($tipoRep == "1"){
   $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
}else{   
   $nomRep = "Registro completo de asistencias";
}

/*echo "contador".$reinicio_contador;
echo "<br>id_periodo".$id_periodo;*/

//$tipoRep = $op;
//if ($tipoRep == "1"){
//   $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
//}else{   
//   $nomRep = "Registro completo de asistencias"; 
//}

 
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Reloj</title>
    <!-- meta info -->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="keywords" content="Aid wear" />
    <meta name="description" content="App Reloj">
    <meta name="author" content="Reloj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../adds/estilo.css" rel="stylesheet" type="text/css" />
    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

        <style type="text/css">

            body {
                background-image: url(../imagenes/fondo.gif);
            }
            .Estilo13 {font-family: "Times New Roman", Times, serif; font-size: 16px; }
            .Estilo14 {font-size: 16px}
            .Estilo15 {
                font-size: 18px;
                font-weight: bold;
            }
            .Estilo16 {color: #FF0000}            
        </style>
        <script type="text/javascript" src="../adds/lib.js">
            function regresar(){
                location.href='Menu.php';
            }                        
        </script>
        
    </head>
    <!--Comienza area de Contenido -->
    <body>

    <div class="global-wrap">
          
         <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <img src="assets/img/logo_uady-gray.png" alt="Mountain View" style="margin-left:-4px;width:170px;height:auto;">
              <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="index.php">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="listar_catalogos.php">Catálogos</a>
                </li>
                <li>
                    <a href="consultar_empleados.php">Empleados</a>
                </li>
                <li>
                    <a href="reporte_horarios.php">Horarios</a>
                </li>
                <li>
                    <a href="captura_permisos.php">Permisos</a>
                </li>
                <li>
                    <a href="importar.php">Entradas/Salidas</a>
                </li>
                <li>
                    <a href="main.php">Reportes</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">

                <!-- //////////////////////////////////
                //////////////START PAGE CONTENT/////////
                ////////////////////////////////////-->
                <div style="margin-left: -80px">
<!--728-->
       <!-- <table width="95%" border="0" align="center"> -->
       <!--archivo original-->
        <table border="0" style="text-align:center;background-color: white" width="100%">
            <form action="reporte_retardos_x.php" target="_blank" method="post" id="Menu" >  
            <tr >
                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Imprimir Reporte</b></h3></td>
            </tr>
            <tr>
                <td colspan="4" align="right">
                     <a href='main.php'><img src='imagen/buscar.gif' alt='Consultar Checadas' width='30' height='30' border='0' /></a>
                </td>      
            </tr>
            <tr align ="left">
                <td><b>Departamento: </b><?php echo utf8_encode($nomDepto); ?> </td> 
            </tr>
            <tr align ="left">
                <td><b>Empleado: </b><?php echo utf8_encode($nomEmp); ?> </td>                
            </tr>
            <tr align ="left">
                <td><b>Reporte seleccionado: </b><?php echo utf8_encode($nomRep); ?> </td>                
            </tr> 
            <tr align ="left">
                <td><b>Fecha inicial: </b><?php echo $fechaini; ?> </td>                
            </tr> 
            <tr align ="left">
                <td><b>Fecha final: </b><?php echo $fechafin; ?> </td>                
            </tr>
            <tr>
                <?php 
                if ($tipoRep == '1') {
                ?> 
                <td colspan="3" align="center">                   
                    <input type="submit" name="btnImprime" value="Imprimir" onClick=""></input>
                </td>
                
                <?php } ?>                     
            </tr>
           

            </form>
       </table>

    </div>   

                 <!-- //////////////////////////////////
                //////////////END PAGE CONTENT/////////
                ////////////////////////////////////-->

                <div id="includedFooter"></div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
   
    </div>

    <!-- Scripts queries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        $(function(){
            $("#includedHeader").load("templates/header/header.html"); 
            $("#includedContent").load("assets/prueba.html"); 
            $("#includedFooter").load("templates/footer/footer.html"); 
        });

       
            $("#wrapper").toggleClass("toggled");
     
    </script>
    
</body>
</html>
