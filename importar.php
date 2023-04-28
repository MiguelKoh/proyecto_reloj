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
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon_uady.ico">

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-************" crossorigin="anonymous" />
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
        <script type="text/javascript" src="js/ajax.js"></script>
       <script type="text/javascript">
            $(function() {
                $("#dFechaInicio").date_input();
                $("#dFechaFin").date_input();
            });
        </script> 
        <script language='javascript' src="js/popcalendar.js"></script>
        <script type="text/javascript">
            function validarSelect(){
                  document.importa.submit();
                }
        </script>
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
                <img src="assets/img/logo-uady-blanco.png" alt="Mountain View" class="logoUady">
            <ul class="sidebar-nav">
                
             <!-- Inicio -->
             <li>
                <div>
                  <a href="index.php">
                  <div>
                    <span><i class="fas fa-house-user"></i></i></span>
                    <span>Inicio</span>
                  </div>  
                   </a>
                </div>
                </li>
                
               
                <!-- Catalogos -->
                <li>
                <div>
                  <a href="listar_catalogos.php">
                  <div>
                    <span><i class="fas fa-th-list"></i></i></span>
                    <span>Catalogos</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Empleados -->
                <li>
                <div>
                  <a href="consultar_empleados.php">
                  <div>
                    <span><i class="fas fa-users"></i></span>
                    <span>Empleados</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                
                
                <!-- Horarios -->
                <li>
                <div>
                  <a href="reporte_horarios.php">
                  <div>
                    <span><i class="fas fa-clock"></i></span>
                    <span>Horarios</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Permisos -->
                <li>
                <div>
                  <a href="captura_permisos.php">
                  <div>
                    <span><i class="fas fa-key"></i></span>
                    <span>Permisos</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Entradas/Salidas -->
                <li  class="FondoNav">
                <div>
                  <a href="importar.php">
                  <div>
                    <span><i class="fas fa-sign-in-alt"></i></span>
                    <span>Entradas/Salidas</span>
                  </div>  
                   </a>
                </div>
                </li>

                <!-- Reportes -->
                <li>
                <div>
                  <a href="main.php">
                  <div>
                    <span><i class="fas fa-check-square"></i></span>
                    <span>Reportes</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Salir -->
                <li>
                <div>
                  <a href="login.php">
                  <div>
                    <span><i class="icon fa fa-sign-out fa-fw" aria-hidden="true"></i></span>
                    <span>Salir</span>
                  </div>  
                   </a>
                </div> 
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
                        <form name="importa" method="post" enctype="multipart/form-data" action="subirInfoCsv.php" >
                            <tr><td style="text-align:center;vertical-align:middle"><h3><b>Cargar Registro de Asistencias </b></h3></td></tr>
                            <tr>
                                <td align="right">
                                    <a href='importar.php'><img src='imagen/horario.gif' alt='Cargar Asistencia' width='30' height='30' border='0' /></a>
                                    <a href='importarPermisos.php'><img src='imagen/permiso.gif' alt='Cargar Permisos' width='35' height='35' border='0' /></a>
                                    <a href='importarChecadas.php'><img src='imagen/borrar.png' alt='Cargar Checadas' width='25' height='25' border='0' /></a>
                                    <a href='Borrar_registros.php'><img src='imagen/eliminar.png' alt='Borrar Registros' width='30' height='30' border='0' /></a>
                                    <a href='Borrar_permisos.php'><img src='imagen/deletepermiso.gif' alt='Borrar Permisos' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                             
                            <tr>
                                <td>Seleccione el periodo quincenal de la informaci&oacute;n que importar&aacute;</td>
                            </tr>
                            <tr>
                                <td style="text-align:letf;vertical-align:middle">
                                <input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, importa.fechaInicio, 'dd/mm/yyyy');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>"> al <input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, importa.fechaFin, 'dd/mm/yyyy');" size="10" value="<?php echo html_entity_decode($fechaFin);?>">
                                   <!--  <select name="lstPeriodo" id="lstPeriodo">                                        
                                       <?php
                                        //$resultado = listaPeriodos();
                                        //echo $resultado;

                                        ?>
                                    </select>-->
                                </td>
                            </tr>                            
                            <tr><td>&nbsp;</td></tr> 
                            <tr>
                                <td>Seleccione el archivo con extensi&oacute;n CSV que importar&aacute;</td>
                            </tr>                                                                                      
                            <tr>
                                <td><input type="file" name="excel" id="excel"/> </td>
                            </tr> 
                            <tr><td>&nbsp;</td></tr>                                 
                            <tr>
                                <td><input type="submit" name="enviar"  value="Importar"  onclick="validate()"/></td>
                            </tr>                            
                            <tr><td><input type="hidden" value="upload" name="action" onclick="validate()"/></td></tr>
                        </form>
                        <!-- CARGA LA MISMA PAGINA MANDANDO LA VARIABLE upload -->
    
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
            
        });

      
            $("#wrapper").toggleClass("toggled");
       
    </script>
    
</body>
</html>
