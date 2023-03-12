<?php
    session_start();
    include('conex.php'); 
    include('funciones_reloj.php');
    $cn = ConectaBD();
    $variables=Request("idEmp");         
    if ($variables[1][2]) {$idEmp  = $variables[1][1];} else {$idEmp = "0";}    
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
        <link href="css/estilos.css"  rel="stylesheet"/>
       
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

        <table style="text-align:center;background-color: white" width="100%" border="0">
            <tr>
                <td align="center">
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">                       
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b> </b></h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='alta_Empleados.php'><img src='imagen/addempleado.png' alt='Alta de Empleados' width='30' height='30' border='0' /></a>
                                    <a href='consultar_empleados.php'><img src='imagen/buscar.gif' alt='Actualizar Empleado' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                                                                           
 
 </table>



        <header>
            <div class="alert alert-info">
            <h2>Consultar Asistencias Empleados</h2>
            </div>
        </header>
        <section>
        <?php             
              $SQL = "SELECT idemp, Nombre,idDepto from empleado WHERE idEmp = ".$idEmp." ORDER BY idemp";
              $query = mysqli_query($cn,$SQL);
              $frows = mysqli_num_rows($query);           
              $rs = mysqli_fetch_array($query);                          
                 
      ?>
        <form method="post" class="form" action="">
        <b>Empleado: </b><?php echo $rs['idemp']." - ". $rs['Nombre'];?><br><br>   
        <input type="date" name="fecha1" value="<?php if(!empty($_POST['fecha1'])) echo $_POST['fecha1'];?>">
        <input type="date" name="fecha2" value="<?php if(!empty($_POST['fecha2'])) echo $_POST['fecha2'];?>">
        <input type="submit" name="generar_reporte">
        </form><br>
    <?php
    if(!empty($_POST['fecha1']) &&  !empty($_POST['fecha2'])){?>
<div style="width: 80%"> <a href="reporte_checadas.php?fecha1=<?php echo $_POST['fecha1'];?>&fecha2=<?php echo $_POST['fecha2'];?>&idEmpleado=<?php echo $idEmp;?>" target="_blank"><img src="imagen/excel.gif" alt="Reprobados"  border="0" align="right"></a></div>
    <?php 
    $SQL="SELECT * FROM checadas_nuevo 
          WHERE fechar BETWEEN '".$_POST['fecha1']."' AND '".$_POST['fecha2']."' 
          AND idEmp='".$idEmp."'";
          $query=mysqli_query($cn,$SQL);
          $conta=0;

    ?>
            <table class="table">

                <tr class="bg-primary">
                    <th style="color:#FFF;">No.Registro</th>                  
                    <th style="color:#FFF;">Fecha</th>
                    <th style="color:#FFF;">Horaini</th>
                    <th style="color:#FFF;">Horafin</th>
                </tr>
                <?php
                while($registroAlumnos=mysqli_fetch_array($query)){
                    $conta++;
                   echo "<tr>
                         <td>".$conta."</td>                        
                         <td>".$registroAlumnos['fecha']."</td>
                         <td>".$registroAlumnos['horaini']."</td>
                         <td>".$registroAlumnos['horafin']."</td>
                         </tr>";
                }
                ?>
                </table>

    <?php }?>
        
        </section>
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