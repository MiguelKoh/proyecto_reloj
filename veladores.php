<?php
session_start();

include("conex.php");
include("funciones_reloj.php");
global $fechaInicio,$fechaFin;

$cn = ConectaBD();
$variables=Request("idDepto,idEmp,fechaInicio,fechaFin,chkop");

if ($variables[1][2]) {$idDepto    = $variables[1][1];} else {$idDepto = 0;}       
if ($variables[2][2]) {$idEmp  = $variables[2][1];} else {$idEmp = "0";} 
if ($variables[3][2]) {$fechaInicio    = $variables[3][1];} else {$fechaInicio = "";}    
if ($variables[4][2]) {$fechaFin  = $variables[4][1];} else {$fechaFin = "";}      
if ($variables[5][2]) {$op   = $variables[5][1];} else {$op  = "0";} 
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
      
       <!-- <link href="css/estilos.css" rel="stylesheet" type="text/css"/> -->
        <link rel="stylesheet" type="text/css" href="select_dependientes.css">
        <link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />            
        <link rel="stylesheet" type="text/css" href="lib/thickbox.css" />
        <link rel="stylesheet" href="date_input.css" type="text/css"> 
        
        <script language="javascript" type="text/javascript" src="select_dependientes.js"></script>
        <script type="text/javascript">
            function validarSelect(){
                  document.frmSelect.submit();
                }
        </script>
        <script type="text/javascript">
            $(function() {
                $("#dFechaInicio").date_input();
                $("#dFechaFin").date_input();
            });
        </script> 
        <script language='javascript' src="js/popcalendar.js"></script>
        <script language="javascript">

        function validate(){
        var fecha = document.getElementById("fechaInicio").value;
        var fechaF = document.getElementById("fechaFin").value;
                if( fecha == null || fecha.length == 0 || /^\s+$/.test(fecha) ) {
                        alert("Debes proporcionar la fecha de inicio"); 
                        return false;
                }   

                        if( fechaF == null || fechaF.length == 0 || /^\s+$/.test(fechaF) ) {
                        alert("Debes proporcionar la fecha final"); 
                        return false;
                }


                return true;
        }
        </script>    
        <script src="js/jquery.validationEngine-en.js" type="text/javascript"></script>
        <script src="js/jquery.validationEngine.js" type="text/javascript"></script>
        <script src="js/jquery.hotkeys-0.7.9.js"></script>        
    </head>
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

        <table border="0" style="text-align:center;background-color: white" width="100%">
            <tr>
                <td style="width:10%">

                </td>
                <td align="left">
                    <table width="80%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmSelect" id="frmSelect" method="post">
                        <input type="hidden" name="profesorid" id="profesorid" value="0">
                            <tr>                                
                            <td>&nbsp;</td>
                                <td style="text-align:center;vertical-align:middle"><h3><b>Reporte de Asistencias Veladores, Intendentes de Noche y Personal Fin de Semana </b></h3><br><br></td>
                            </tr>
                                                          
                           <tr>
                                <td align="right"><b>Departamento: </b></td>   
                                <td> 
                                    <select name="idDepto" id="idDepto" onChange="javascript:submit()">
                                      <?php $idept=0;
                                           $SQLp="SELECT * FROM departamento WHERE idDepto IN(15,17) ";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un Departamento</option>
                                       <option value="TODOS">TODOS</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idDepto == $rsA["idDepto"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idDepto"]; ?>"<?php echo $selected;?>>
                                          <?php echo utf8_encode($rsA["Nombre"]);?>                
                                      </option>
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>
                                </td>                                
                            </tr>                                                 
                            <tr>
                                <td align="right"><b>Empleado:</b></td>
                                <td>
                                    <select name="idEmp" id="idEmp">
                                      <?php 
                                            $concatena="";
                                            if($idDepto==15)
                                                $concatena=" AND idEmp IN(6664,4223,2867,1601) ";
                                           $SQLp="SELECT idEmp, Nombre FROM empleado WHERE Estatus is Null AND  iddepto='".$idDepto."'".$concatena." ORDER BY Nombre";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un Empleado</option>
                                      <option value="TODOS">TODOS</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idEmp == $rsA["idEmp"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idEmp"]; ?>"<?php echo $selected;?>>
                                          <?php echo$rsA["idEmp"]." - ".utf8_encode($rsA["Nombre"]);?>                
                                      </option>
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>                                                                          
                                </td>
                            </tr>
                            <tr>
                               <td style="text-align:right;vertical-align:middle;font-weight: bold;">Quincena:
                               </td>
                                <td style="vertical-align:middle">
                                <input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, frmSelect.fechaInicio, 'dd/mm/yyyy');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>"> al <input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, frmSelect.fechaFin, 'dd/mm/yyyy');" size="10" value="<?php echo html_entity_decode($fechaFin);?>">
                                  <!--  <select name="lstPeriodo" id="lstPeriodo">                                        
                                        <?php
                                        //$resultado = //listaPeriodos();
                                       // echo $resultado;

                                        ?>
                                    </select>-->
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>

                            <td style="text-align:center;vertical-align:middle"><b>Reportes: </b></td>
                            <td>&nbsp;</td>
                            </tr>  
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                            <td>&nbsp;</td>
                                <td style="vertical-align:middle">  
                                    <input type="radio" name="chkop" value="1" checked/>Faltas, Retardos y Salidas Antes de Horario <br>
                                    <input type="radio" name="chkop" value="2"/>Todos los Registros<br>
                                   
                                </td>
                            </tr>                            
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                                <td style="text-align:center;vertical-align:middle">
                                    <!--<input type="hidden" name="idemp" value="9530" />-->
                                    <input type="submit" value="Consultar" id="btnRegis" name="btnProceso" onclick="validate()"/>
                                </td>
                            </tr>
                          
                        </form>
                    </table>  

                </td>
            </tr>
        </table>
         <?php
                                //if(isset($_POST['buscaPermisos'])){
                                if(isset($idDepto) && $idEmp>0 ){
                                    //validaciones
                                    //que la fecha final no sea menor a la de inicio
                                    if ($fechaInicio =="" || $fechaFin =="" ){
                                        echo '<script>alert ("Debe seleccionar un curso y semestre")</script>';
                                    }else{    
                                        //obtengo la lista de los permisos que capturados en la fecha seleccionada
                                         $tipoRep = $op;
    if ($op == "1") {
        $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
    }else{
        $nomRep = "Registro completo de checadas";
    }

    $SQLp="SELECT * FROM departamento WHERE idDepto =".$idDepto;
    $queryA = mysqli_query($cn,$SQLp);
    $rsA=mysqli_fetch_array($queryA);
    $nomDepto=$rsA['Nombre'];

    $BuscoPeriodo="SELECT idperiodo,id_semestre FROM periodos WHERE fecha_inicio='".$fechaInicio."' 
    AND fecha_fin='".$fechaFin."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $busca=mysqli_fetch_array($queryBusca);
    $idPeriodo=$busca['idperiodo'];

    //obtener horario de empleados del departamento seleccionado
    if ($idDepto <> "TODOS") { 
        if ($idEmp <> "TODOS"){
            // depto y empleado especifico
            if ($tipoRep == "1"){ 
                      
                 $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,
                                horaIni,horaFin,checadaini1,checadafin1,
                                ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida,
                                TIMEDIFF(checadafin1,checadaini1) as horas_totales,                            
                                TIMEDIFF(CASE
                                WHEN checadafin1='' THEN 0
                                WHEN checadafin1<horaFin THEN checadafin1
                                WHEN checadafin1>horaFin THEN horaFin
                                ELSE horaFin
                                END,CASE
                                WHEN checadaini1='' THEN 0
                                WHEN checadaini1<horaIni THEN horaIni
                                WHEN checadaini1>horaIni THEN checadaini1
                                ELSE horaIni
                                END) as horas_segun_horario,                       excepcion, jornadaTrabajada, attTime as jornadaReal FROM registros  where departamento = '" . $nomDepto . 
                            "' and idemp ='" . $idEmp . 
                            "' and idperiodo='" . $idPeriodo . 
                            "' ORDER BY `registros`.`fecha` ASC ";

            }else{
                if ($tipoRep == "2"){
                     $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                                "horaIni,horaFin,checadaini1,checadafin1," .
                                "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                                "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .                            
                                "TIMEDIFF(CASE
                                WHEN checadafin1='' THEN 0
                                WHEN checadafin1<horaFin THEN checadafin1
                                WHEN checadafin1>horaFin THEN horaFin
                                ELSE horaFin
                                END,CASE
                                WHEN checadaini1='' THEN 0
                                WHEN checadaini1<horaIni THEN horaIni
                                WHEN checadaini1>horaIni THEN checadaini1
                                ELSE horaIni
                                END) as horas_segun_horario," . 
                                "excepcion, jornadaTrabajada, attTime as jornadaReal " .                            
                                " from registros where departamento = '" . $nomDepto . 
                                "' and idemp ='" . $idEmp .                             
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaInicio . 
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechaFin . 
                                "' order by nombre,fecha";                
                }
            }
        } else {            
            // todos los empleados de un solo depto
            if ($tipoRep == "1"){           
                $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                            "horaIni,horaFin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .  
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horaFin THEN checadafin1
                            WHEN checadafin1>horaFin THEN horaFin
                            ELSE horaFin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaIni THEN horaIni
                            WHEN checadaini1>horaIni THEN checadaini1
                            ELSE horaIni
                            END) as horas_segun_horario," .     
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .                        
                            " from registros where departamento = '" . $nomDepto .                            
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaInicio .                         
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechaFin . 
                            //"' and ((TIMEDIFF(horaIni,checadaini1)<0 or TIMEDIFF(horaFin,checadafin1)>0 or ausente='Y')) " .
                            "' order by nombre,idemp, fecha";
            }else{
                if ($tipoRep == "2"){
                    $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                                "horaIni,horaFin,checadaini1,checadafin1," .
                                "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                                "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .      
                                "TIMEDIFF(CASE
                                WHEN checadafin1='' THEN 0
                                WHEN checadafin1<horaFin THEN checadafin1
                                WHEN checadafin1>horaFin THEN horaFin
                                ELSE horaFin
                                END,CASE
                                WHEN checadaini1='' THEN 0
                                WHEN checadaini1<horaIni THEN horaIni
                                WHEN checadaini1>horaIni THEN checadaini1
                                ELSE horaIni
                                END) as horas_segun_horario," .  
                                "excepcion, jornadaTrabajada, attTime as jornadaReal " .                            
                                " from registros where departamento = '" . $nomDepto .  
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaInicio . 
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechaFin . 
                                "' order by nombre,idemp, fecha";       //and idemp = 9604           
                }
            }
        }
    }else {
        //todos los deptos
        if ($tipoRep == "1"){
             $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                        "horaIni,horaFin,checadaini1,checadafin1," .
                        "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                        "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .   
                        "TIMEDIFF(CASE
                        WHEN checadafin1='' THEN 0
                        WHEN checadafin1<horaFin THEN checadafin1
                        WHEN checadafin1>horaFin THEN horaFin
                        ELSE horaFin
                        END,CASE
                        WHEN checadaini1='' THEN 0
                        WHEN checadaini1<horaIni THEN horaIni
                        WHEN checadaini1>horaIni THEN checadaini1
                        ELSE horaIni
                        END) as horas_segun_horario," .   
                        "excepcion, jornadaTrabajada, attTime as jornadaReal " .                    
                        " from registros where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaFin . 
                        "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechaInicio . 
                        "' and ((TIMEDIFF(horaIni,CASE WHEN checadaini1='' THEN 0 ELSE checadaini1 END)<=0 
or TIMEDIFF(horaFin,CASE WHEN checadafin1='' THEN 0 ELSE checadafin1 END)>=0 or ausente='Y')) order by nombre,departamento, idemp, fecha";               
        }else{
            if ($tipoRep == "2"){
                $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                            "horaIni,horaFin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " . 
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horaFin THEN checadafin1
                            WHEN checadafin1>horaFin THEN horaFin
                            ELSE horaFin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaIni THEN horaIni
                            WHEN checadaini1>horaIni THEN checadaini1
                            ELSE horaIni
                            END) as horas_segun_horario," . 
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .                        
                            " from registros where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaInicio .                    
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechaFin . 
                            "' order by nombre,departamento, idemp, fecha";                   
            }
        }     
    }
   //echo $sente;
    
    $result = mysqli_query($cn,$sente);
                                        $numreg = mysqli_num_rows($result);
                                        if($numreg>0){
                                ?>
                                
                                <table width='100%' cellpadding='1' cellspacing='1' border='1'>
                                    <tr style="color: #fff;background-color:#212529;text-align: center;font-weight: bold;">
                                        <td>Fecha</td>
                                        <td>Dia</td>
                                        <td>Hora inicio</td>
                                        <td>Hora Fin</td>
                                        <td>Checada Inicial</td>
                                        <td>Checada Final</td>
                                        <td>Minutos Entrada</td>
                                        <td>Minutos Salida</td>
                                        <td>Descuento</td>
                                    </tr>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)){

                                       
 // ----------cambio formato de fecha del registro----------                    
            $separar =  explode('-',$row['fecha']);

            $dia = $separar[2];
            $mes = $separar[1];
            $anio = $separar[0];
            $dia_semana = diaSemana($anio,$mes,$dia);
            $nombre_dia = nombre_dia ($dia_semana);
             $fecha_reg = $dia . "/" . $mes . "/" . $anio;   

                $SQLhorarioWeb="SELECT * FROM horarios_semestre 
                WHERE idEmp='".$idEmp."' AND id_dia='".$dia_semana."' 
                AND id_semestre='".$busca['id_semestre']."'";
                $queryD=mysqli_query($cn,$SQLhorarioWeb);
                $rowdia = mysqli_fetch_array($queryD);

            $separar = explode (" ", $rowdia['horario']);
            $horaInicial = $separar[0];
            $horaFinal = $separar[2];
 
                      $muestra="";
                      $sente1 = "select idemp, fechaini as fechaini1, fechafin as fechafin1," .
                      " horaIni, horaFin, tipo, motivo, horaCaptura, minutosDiarios" .    
                      " from permisos where fechaini = '" . $fecha_reg .
                      "' and fechafin = '" . $fecha_reg .
                      "' and ((horaIni >= '" . $row['horaIni'] . "' and horaFin <= '" . $row['horaFin'] . 
                      "') or (horaIni<='" . $row['horaIni'] . "' and horaFin <= '" . $row['horaFin'] . 
                      "') or (horaIni <= '" . $row['horaIni'] . "' and horaFin >= '" . $row['horaFin'] .
                      "') or (horaIni >= '" . $row['horaIni'] . "' and horaIni <= '" . $row['horaFin'] . "'))" .                    
                      " and idemp = " . $row['idemp'] ;              
                     $result1 = mysqli_query($cn,$sente1);  
                     $nume=mysqli_num_rows($result1);
                     $permiso=mysqli_fetch_array($result1);
                     if($nume>0)
                         $muestra="Permiso";

                      if($row['ausente'] == "Y"){
                        $total_minutos_ausente=0;
                        $minutos_x_descontar_formato_hora=0;
                        $minutos_x_descontar_formato_hora = date("H:i", strtotime("00:00") + strtotime($horaFinal) - strtotime($horaInicial) );
                        $horas = 0;
                        $minutos = 0;            
                        $row['horas_totales'] = "00:00:00";

                        $separar = explode(':',$minutos_x_descontar_formato_hora);
                        $horas = $separar[0];
                        $minutos = $separar[1]; 
                        $total_minutos_ausente = ($horas*60)+$minutos;
                        $muestra= $total_minutos_ausente;

                }


                                        
                                     echo "                                       
                                        <tr>
                                            <td align='center'>".cambiafnormal($row['fecha'])."</td>
                                            <td align='center'>".$nombre_dia."</td>
                                            <td align='center'>".$rowdia['hora_ini']."</td>
                                            <td align='center'>".$rowdia['hora_fin']."</td>
                                            <td align='center'>".$row['checadaini1']."</td>
                                            <td>".utf8_encode($row['checadafin1'])."</td>
                                            <td align='center'>".$row['ausente']."</td>  
                                             <td align='center'>".$row['ausente']."</td>
                                             <td align='center'>". $muestra."</td>
                                        </tr>";
                                    }
                                     ?>
                                </table>
                            <?php
                                }else{
                                  echo "<p align='center'><b>El empleado no tienen permisos capturados.</b></p>";
                                }          
                              }   
                            }                         
                            ?>     
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

