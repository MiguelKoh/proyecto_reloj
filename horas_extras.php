<?php

session_start();

include('conex.php');
include('funciones_reloj.php');

//$cn = ConectaBD();
$idDepto = $_SESSION["iddepto"];
$idEmp = $_SESSION["idemp"];
$nomEmp = $_SESSION["nomemp"];
$nomDepto = $_SESSION["nomdepto"];
$op = $_SESSION["op"];

$fechaini = $_SESSION["fechaini"];
$fechafin = $_SESSION["fechafin"];

$tipoRep = $op;
$nomRep = "Horas extras";

function regChecadas() {

    $cn = ConectaBD();
    $idDepto = $_SESSION["iddepto"];
    $idEmp = $_SESSION["idemp"];
    $nomEmp = $_SESSION["nomemp"];
    $nomDepto = $_SESSION["nomdepto"];
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"];
    $op = $_SESSION["op"];
    
    $id_periodo = $_SESSION["id_periodo"];
    
    $numero_empleados = 0; // empleados seleccionados.     
        
    
    //cambio posicion de fecha
    $fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    $fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;       
    
    //nombre del reporte
    $nomRep = "Horas extras";

    //obtener horario de empleados del departamento seleccionado
    if ($nomDepto <> "TODOS") { 
        if ($nomEmp <> "TODOS"){
            // depto y empleado especifico           
                $sente = "select distinct a.idemp, a.fecha, a.horaini, a.horafin, a.horastrabajadas, e.nombre, e.idtipo, ".
                            "d.nombre as departamento, a.fecha, h.descripcion as horario_teorico ".
                            "from checadas_depuradas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                                "left outer join horario_teorico as h ".
                                    "on a.idemp = h.idemp and a.fecha = h.fecha ".
                            "where a.idemp = '" . $idEmp .
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .                           
                            "' order by a.idemp, a.fecha";
            }else {         
            // todos los empleados de un solo depto           
                $sente = "select distinct a.idemp, a.fecha, a.horaini, a.horafin, a.horastrabajadas, e.nombre, e.idtipo, ".
                            "d.nombre as departamento, a.fecha, h.descripcion as horario_teorico ".
                            "from checadas_depuradas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                                "left outer join horario_teorico as h ".
                                    "on a.idemp = h.idemp and a.fecha = h.fecha ".                        
                            "where STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .  
                            "' and d.nombre = '".$nomDepto .                             
                            "' order by a.idemp, a.fecha";

        }
    }else {
            //todos
                $sente = "select distinct a.idemp, a.fecha, a.horaini, a.horafin, a.horastrabajadas, e.nombre, e.idtipo, ".
                            "d.nombre as departamento, a.fecha, h.descripcion as horario_teorico ".
                            "from checadas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                                "left outer join horario_teorico as h ".
                                    "on a.idemp = h.idemp and a.fecha = h.fecha ".                        
                            "where STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .                              
                            "' order by a.idemp, a.fecha";             

    }     
    
    //echo $sente;
    
    $result = mysqli_query($cn,$sente) ;

    $linea = 0;
    $Num = 1;
    $idempant = "";
    $nvo = "";
    $registros_x_empleado = 0;
    $clase = ' class="FilaImpar"';
    $tot_hrs_trabajadas = 0;
    $tot_min_trabajados = 0;    

    while ($row = mysqli_fetch_array($result)) {          
            //-------------------------------------------------------------------------

            if ($linea == 0) {
                $clase = ' class="FilaPar"';
                $linea = 1;
            } else {
                $clase = ' class="FilaImpar"';
                $linea = 0;
            }


            if ($row['departamento'] == $nomDepto) {
                $marca = " checked ";
            } else {
                $marca = "";
            }


            //determino si ya es nuevo empleado o no
            if ($idempant == "") {
                $idempant = $row['idemp'];
                $nombreant = $row['nombre'];
                $departamentoant = $row['departamento'];
                $nvo = "SI";
                                
                $registros_x_empleado = 0;
                $tot_hrs_trabajadas = 0;
                $tot_min_trabajados = 0;                
                

            } else {
                if ($idempant != $row['idemp']) {
    
                    $idempant = $row['idemp'];
                    $nombreant = $row['nombre'];
                    $departamentoant = $row['departamento'];
                    $nvo = "SI";                

                    $tot_hrs_trabajadas = convierte_mins_a_horas($tot_min_trabajados,"NO");
    
                    echo '<tr align="right"  ' . $clase . '>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>                                  
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                                        $tot_hrs_trabajadas . '</b></td>                    
                    </tr>';

                    echo '</table><table width="100%" border="0" align="center" bgcolor="#FFFFFF">';                   
                    
                }else{
                    $nvo = "";
                }
            }

            if ($nvo == "SI") {

                //------------------------------------------------------------------------
                //obtengo el tipo de empleado
                $sente5 = "SELECT idTipo FROM empleado where idEmp =". $idempant;
                $result5 = mysqli_query($cn,$sente5) ;    
                $row5 = mysqli_fetch_array($result5);
                $tipo_empleado = $row5['idTipo'];

                $sente6 = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
                $result6 = mysqli_query($cn,$sente6) ;    
                $row6 = mysqli_fetch_array($result6);
                $tipo_empleado_desc = $row6['Descripcion'];                   

                echo '<td>&nbsp&nbsp;</td>';            
                echo '<table width="100%" border="1" align="center" bgcolor="#08088A">';
                echo '<tr align "left"><td align="left"><font color="#FFFFFF" size="3"><b>' . 
                          $departamentoant . " - " . $idempant . " - " .  $nombreant . " -   " . $tipo_empleado_desc . '</b></font></td>';
                echo '</table>';

                echo '<table width="100%" border="0" align="center" bgcolor="#FFFFFF">';
                echo '<tr align="right"' . $clase . '>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Fecha registro</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Horario teorico</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora entrada</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora salida</b></td>                            
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Horas trabajadas (HH:MM)</b></td> 
                      </tr>';                         
                $registros_x_empleado = 0;
                $numero_empleados += 1;  

                $tot_hrs_trabajadas = 0;
                $tot_min_trabajados = 0;                                  

            }     
            
            //verifico si la entrada o salida es null
            $entrada = $row['horaini'];           
            if ($entrada == NULL) {
                $entrada = 0;
            }
            
            $salida = $row['horafin'];
            if ($salida == NULL) {
                $salida = 0;
            }     
                        
            
            //obtengo horas trabajadas por dia    
            $hrs_trabajadas = 0;
            
            if ($entrada != 0 && $salida != 0) {
                $seg_trabajados = strtotime($salida) - strtotime($entrada);
                
                if ($seg_trabajados < 0 ){
                    $seg_trabajados = $seg_trabajados * -1;
                }
                
                $min_trabajados = $seg_trabajados / 60;                
                $hrs_trabajadas = convierte_mins_a_horas($min_trabajados,"N");

                $tot_min_trabajados += $min_trabajados;                            
                            
            } 
            
            echo '<tr align="right"' . $clase . ' >
                            <td align="center" style="font-size:13px;">' . $row['fecha'] . '</td>
                            <td align="center" style="font-size:13px;">' . $row['horario_teorico'] . '</td>
                            <td align="center" style="font-size:14px;">' . $entrada . '</td>
                            <td align="center" style="font-size:14px;">' . $salida . '</td>
                            <td align="center" style="font-size:14px;">' . $hrs_trabajadas . '</td> 
            </tr>';                        
      
            
    //    }
    }//fin while
    
    $tot_hrs_trabajadas = convierte_mins_a_horas($tot_min_trabajados,"NO");
   
   // echo "<tr><td>".$sente1 ."</td></tr>";
    
    echo '<tr align="right"' . $clase . ' border="1">
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td>  
         <td align="center" style="font-size:15px;"></td>           
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td> 
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                 $tot_hrs_trabajadas . '</b></td>                                
        </tr><tr><td><p></td></tr>';            

                     
     mysqli_free_result($result);
   //  mysql_free_result($result1);
     mysqli_close($cn);
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Control de Asistencia v2.0</title>
    <!-- meta info -->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="keywords" content="Aid wear" />
    <meta name="description" content="App Reloj">
    <meta name="author" content="Reloj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
      
        
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
        <table border="0" style="text-align:center;background-color: white" width="100%">
        <form action="imprimir_retardos.php" target="_blank" method="post" id="Menu"> 
        <tr>                                
         
         <td style="text-align:center;vertical-align:middle;"><h3><b>Reporte Horas Extra</b></h3><br></td>
        </tr>

            
            <tr align ="left">
                <td><b>Departamento: </b><?php echo $nomDepto; ?> </td> 
            </tr>
            <tr align ="left">
                <td><b>Empleado: </b><?php echo $nomEmp; ?> </td>                
            </tr>
            <tr align ="left">
                <td><b>Reporte seleccionado: </b><?php echo $nomRep; ?> </td>                
            </tr> 
            <tr align ="left">
                <td><b>Fecha inicial: </b><?php echo $fechaini; ?> </td>                
            </tr> 
            <tr align ="left">
                <td><p><b>Fecha final: </b><?php echo $fechafin; ?> </p></td>                
            </tr>
                 
            <tr>
                <?php 
                if ($tipoRep == '1') {
                ?> 
                <td colspan="4" align="center">                   
                    <input type="submit" name="btnImprime" value="Imprimir" onclick=""></input>
                </td>
                
                <?php  } ?>
                          
            </tr>
            <tr>
                <td align="centar">
                    <?php
                        regChecadas();
                    ?>
                </td>
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

        function test (a){
            document.getElementById("profesorid").value=a;
        }
    </script>
    
</body>
</html>
