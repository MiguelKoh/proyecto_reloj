<?php
session_start();

include("conex.php");
include("funciones_reloj.php");
global $fechaInicio,$fechaFin;
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
                <li>
                    <a href="login.php">Salir</a>
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
                        <form action="acceso.php" name="frmSelect" id="frmSelect" method="post">
                        <input type="hidden" name="profesorid" id="profesorid" value="0">
                            <tr>                                
                            <td>&nbsp;</td>
                                <td style="text-align:center;vertical-align:middle"><h3><b>Reporte de Asistencias </b></h3><br><br></td>
                            </tr>
                                                          
                            <tr>
                                <td style="text-align:right;vertical-align:middle;font-weight: bold;">Departamento:
                                </td> 
                                <td style="vertical-align:middle"><?php generaDepartamento(); ?>
                                </td>                            
                            </tr>                                                      
                            <tr>
                                <td style="text-align:right;vertical-align:middle;font-weight: bold;">Empleado:                                   
                                </td>
                                <td style="vertical-align:middle">
                                    <select name="lstEmpleado" id="lstEmpleado" >
                                        <!--<option value="0">TODOS</option>  disabled="disabled"-->                                                                              
                                    </select>
                                </td>
                            </tr>
                            <tr>
                               <td style="text-align:right;vertical-align:middle;font-weight: bold;">Periodo Quincenal:
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

                            <td style="text-align:center;vertical-align:middle"><b>Elija un reporte: </b></td>
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
                                    <input type="radio" name="chkop" value="3"/>Horas Extras<br>
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

