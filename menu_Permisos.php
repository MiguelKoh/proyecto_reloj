<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
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

    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

</head>


<body>

    <div class="global-wrap">


        <div id="includedHeader"></div>
          
         <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="#">Catálogos</a>
                </li>
                <li>
                    <a href="#">Empleados</a>
                </li>
                <li>
                    <a href="#">Horarios</a>
                </li>
                <li>
                    <a href="menu_Permisos.php">Permisos</a>
                </li>
                <li>
                    <a href="#">Entradas/Salidas</a>
                </li>
                <li>
                    <a href="#">Reportes</a>
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
                <div style="padding-top: 180px; margin-left: -80px">
                    <table width="80%" cellpadding="1" cellspacing="1" border="0">
        
                        <form action ="" method="post"> 
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><h2>ADMINISTRACION DE EMPLEADOS </h2></td>
                            </tr>                                  
                                                     
                            <tr><td><a href="captura_permisos.php"><li><h3>Alta de Permisos </h3></li></a></td></tr>                            
                            <tr><td><a href="reporte_permisos.php"><li><h3>Consultar Permisos </h3></li></a></td></tr>                            
                            <?php
                            // put your code here
                            ?>
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

        window.onload=function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        };
    </script>
    
</body>
</html>

