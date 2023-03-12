<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");        
     global $fechaInicio,$fechaFin; 
?>

<!-- http://ProgramarEnPHP.wordpress.com -->
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
        <script type="text/javascript" src="js/ajax.js"></script>
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
    <!-- FORMULARIO PARA SOICITAR LA CARGA DEL EXCEL -->

        <table border="0" style="text-align:center;background-color: white" width="100%">
            <tr>
                <td style="width:10%">

                </td>
                <td align="left">
                    <table width="80%" cellpadding="1" cellspacing="1" border="0">
                        <form form action="" method="post" name="Borrar_registros" >
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><h3><b>Eliminar Registros de Asistencia y Bit&aacute;cora</b></h3></td>
                            </tr>   
                            <tr>
                            <td align="right">
                                    <a href='importar.php'><img src='imagen/horario.gif' alt='Cargar Asistencia' width='30' height='30' border='0' /></a>
                                    <a href='importarPermisos.php'><img src='imagen/permiso.gif' alt='Cargar Permisos' width='35' height='35' border='0' /></a>
                                    <a href='importarChecadas.php'><img src='imagen/borrar.png' alt='Cargar Checadas' width='25' height='25' border='0' /></a>
                                    <a href='Borrar_registros.php'><img src='imagen/eliminar.png' alt='Borrar Registros' width='30' height='30' border='0' /></a>
                                    <a href='Borrar_permisos.php'><img src='imagen/deletepermiso.gif' alt='Borrar Permisos' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                             
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>Se eliminar&aacute; la informaci&oacute;n importada de los reportes con extensi&oacute;n CSV de registros y bit&aacute;cora de asistencias</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>Seleccione el periodo quincenal de la informaci&oacute;n que desea eliminar</td></tr>
                            <tr>
                                <td style="text-align:letf;vertical-align:middle">
                                <input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, Borrar_registros.fechaInicio, 'dd/mm/yyyy');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>"> al <input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, Borrar_registros.fechaFin, 'dd/mm/yyyy');" size="10" value="<?php echo html_entity_decode($fechaFin);?>">
                                   <!--  <select name="lstPeriodo" id="lstPeriodo">                                        
                                       <?php
                                        //$resultado = listaPeriodos();
                                        //echo $resultado;

                                        ?>
                                    </select>-->
                                </td>
                            </tr>                            
                            <tr><td>&nbsp;</td></tr> 
                            <tr><td><input type="submit" name="borrar"  value="Borrar"  onclick="validate()"/></td></tr>   
                            <tr><td>&nbsp;</td></tr>
                            <?php
                                if (isset($_POST['borrar']) && !empty($_POST["fechaInicio"]) && !empty($_POST["fechaFin"])){
                                    $cn = ConectaBD();

                                    //rescatamos el periodo
                                    //-----------------------------------------------------------------
                                   // $id_periodo = mysqli_real_escape_string($cn,trim($_POST["lstPeriodo"]));

                                    $fechacai = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
                                    $fechacaf = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
                                    $BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechacai."' AND fecha_fin='".$fechacaf."'";
                                    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
                                    $exist=mysqli_num_rows($queryBusca);
                                    if($exist>0){
                                    $busca=mysqli_fetch_array($queryBusca);
                                    $id_periodo=$busca['idperiodo'];


                                    //obtengo fecha inicial y final del periodo para verificacion
                                    $sente = "select fecha_inicio, fecha_fin from periodos where idperiodo =" . $id_periodo;
                                    $result =  mysqli_query($cn,$sente);
                                    $row = mysqli_fetch_array($result);

                                    $fecha_ini = $row['fecha_inicio'];
                                    $fecha_fin = $row['fecha_fin'];
                                    //-----------------------------------------------------------------                        

                                    //borrando registros de bitacora
                                    $sente = "select  count(*) as cantidad from checadas where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    $row = mysqli_fetch_array($result);
                                    $cant_registros = $row['cantidad'];

                                    $sente = "DELETE FROM checadas where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    echo "<tr><td>Se borraron " . $cant_registros . " en tabla bitacora del periodo " . $id_periodo . "</td></tr>";

                                    //borrando registros de checadas depuradas
                                    $sente = "select  count(*) as cantidad from checadas_depuradas where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    $row = mysqli_fetch_array($result);
                                    $cant_registros = $row['cantidad'];

                                    $sente = "DELETE FROM checadas_depuradas where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    echo "<tr><td>Se borraron " . $cant_registros . " en tabla checadas_depuradas del periodo " . $id_periodo . "</td></tr>";

                                    //borrando registros de entradas y salidas
                                    $sente = "select  count(*) as cantidad from registros where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    $row = mysqli_fetch_array($result);
                                    $cant_registros = $row['cantidad'];

                                    $sente = "DELETE FROM registros where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    echo "<tr><td>Se borraron " . $cant_registros . " en tabla registros del periodo " . $id_periodo . "</td></tr>";                       

                                    //borrando registros de horario_teorico
                                    $sente = "select  count(*) as cantidad from horario_teorico where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    $row = mysqli_fetch_array($result);
                                    $cant_registros = $row['cantidad'];                        

                                    $sente = "DELETE FROM horario_teorico where idperiodo = " . $id_periodo;
                                    $result = mysqli_query($cn,$sente);
                                    echo "<tr><td>Se borraron " . $cant_registros . " en tabla horario_teorico del periodo " . $id_periodo . "</td></tr>";                        

                                    echo '<script>alert ("La bitacora y registros se borraron correctamente")</script>';
                                    }else{
                                        echo '<script>alert ("El rango de fechas seleccionado no coincide con ninguna quincena.")</script>';
                                    }
                                    //echo "<script>location.href='OpBorrar.php'</script>";
                                }
                            ?>                                                                                 
                        </form>
                    </table>
                </td>
            </tr>
            

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