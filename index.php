<?PHP include("validate.php");?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Control de Asistencias v2.00</title>
    <!-- meta info -->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="keywords" content="Aid wear" />
    <meta name="description" content="App Reloj">
    <meta name="author" content="Reloj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon_uady.ico">
    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-************" crossorigin="anonymous" />
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">



</head>


<body>
    
    
    <div class="global-wrap">


        <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
                <img src="assets/img/logo-uady-blanco.png" alt="Mountain View" class="logoUady">
            <ul class="sidebar-nav">
                
             <!-- Inicio -->
             <li class="FondoNav">
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
                <li>
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
                  <a href="salir.php">
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
