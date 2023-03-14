<?PHP include("validate.php");?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Control de Asistencias v2.0</title>
    <!-- meta info -->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="keywords" content="Aid wear" />
    <meta name="description" content="App Reloj">
    <meta name="author" content="Reloj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

</head>


<body>
    <style>
        body{
            background-image:url("assets/img/fondoUV.png")
            
        }
    </style>

    <div class="global-wrap">


        <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
                <img src="assets/img/logo-uady-blanco.png" alt="Mountain View" class="logoUady">
            <ul class="sidebar-nav">
                <li class="sidebar-brand FondoNav">
                    <a href="index.php">Inicio</a>
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
                    <table width="100%" cellpadding="1" cellspacing="1" border="0">
                    <!--Menu.php -->
                        <form action="Menu.php" method="post" id="Menu" >
                                <tr>
                                    <td>&nbsp;</td>
                                </tr> 
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>                             
                                <tr>
                                    <td align="center"><h3> Control de Asistencias V2.0 </h3></td>                
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
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
   
    </div >

    <!-- Scripts queries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        $(function(){
            $("#includedHeader").load("templates/header/header.html"); 
            
        });

        window.onload=function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        };
    </script>
    
</body>
</html>
