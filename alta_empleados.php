
<?php

    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("idEmp,nombres,apellidos,nvoidDepto,nvotipoEmpleado");
 
    if ($variables[1][2]) {$idEmp  = $variables[1][1];} else {$idEmp = "";} 
    if ($variables[2][2]) {$nombres  = $variables[2][1];} else {$nombres = "";} 
    if ($variables[3][2]) {$apellidos  = $variables[3][1];} else {$apellidos = "";}     
    if ($variables[4][2]) {$nvoidDepto    = $variables[4][1];} else {$nvoidDepto = 0;}       
    if ($variables[5][2]) {$nvotipoEmpleado  = $variables[5][1];} else {$nvotipoEmpleado = 0;}         
    
    // proceso para grabar la informacion
    
    if(isset($_POST['grabarEmpleado'])){
        
        //que no falte por llenar algun dato
        if ($idEmp == "" || $apellidos == "" || $nombres == "" || $nvotipoEmpleado == 0 || $nvoidDepto == 0){
            echo '<script>alert ("Faltan datos por llenar")</script>';
          
        }else{    
            // si es practicamente o servicio social que el codigo de empleado sea mayor a 11000
            if ($nvotipoEmpleado == 4 && $idEmp < 11000){
                
                echo '<script>alert ("El personal practicante y de servicio social debe tener asignado un codigo de empleado mayor a 11000")</script>';
            }else{

                // que no este ya dado de alta el empleado en la base de datos.
                $sente = "SELECT Nombre,idDepto FROM empleado WHERE idEmp = " . $idEmp;
                $result = mysqli_query($cn,$sente);
                
                if ($row = mysqli_fetch_array($result)){
                    $x_Nombre = $row['Nombre'];
                    $x_departamento = $row['idDepto'];
                    
                    $sente1 = "SELECT Nombre FROM departamento WHERE idDepto = " . $x_departamento;
                    $result1 = mysqli_query($cn,$sente1);
                    $row1 = mysqli_fetch_array($result1);
                    $x_departamento_nombre = $row1['Nombre'];
                                        
                    echo '<script>alert ("La clave ' . $idEmp . ' pertenece al \nEmpleado: ' . $x_Nombre .'\nDepartamento: ' . $x_departamento_nombre . '.\nNo se dara de alta.")</script>'; 
                    
                }else{
                    //grabo informacion
                    //Verifico si es servicio social ya que ellos por default quedan como administrativos
                    //sin embargo al no tener horario no se les aplican reglas de descuento
                    $practicante = "";
                    
                    if ($nvotipoEmpleado == 4){
                        $practicante = "S";
                        $nvotipoEmpleado = 3;
                    }       
                    
                    //poniendo el nombre en una sola variable
                    $nombre_completo = strtoupper(trim($apellidos)) . " " . strtoupper(trim($nombres));
                    
                    $sente = "INSERT INTO empleado (idEmp,Nombre,idDepto,idTipo,Practicante) " .
                            "VALUES (" . $idEmp . ",'" . $nombre_completo . "'," . $nvoidDepto . "," . $nvotipoEmpleado . ",'" . $practicante . "')";
                    $result = mysqli_query($cn,$sente);
                    
                    //echo $sente;
                    echo '<script>alert ("El empleado se grabo correctamente en la Base de Datos")</script>';
                }
                
            }
            
        }
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
                    <table align="center" width="8%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmAltaEmpleado" id="frmAltaEmpleado" method="post">
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle;"><h3><b>Alta de Empleados </b></h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='alta_Empleados.php'><img src='imagen/addempleado.png' alt='Alta de Empleados' width='30' height='30' border='0' /></a>
                                    <a href='consultar_empleados.php'><img src='imagen/buscar.gif' alt='Actualizar Empleado' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>  
                            <tr>
                                <td colspan="4"><p>***Recuerda que para dar de alta a un empleado de Servicio social / Practicante, la
                                        clave de empleado debe ser mayor a 11000.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                                <td align="right"><b>Clave:</b></td>
                                <td><input name="idEmp" type="text" id="idEmp" size="5" value="<?php echo $idEmp;?>"></td>
                            </tr>
                            <tr>
                                <td align="right"><b>Nombres:</b></td>
                                <td><input name="nombres" type="text" id="nombres" size="75" value="<?php echo $nombres;?>"></td>
                            </tr>
                            <tr>
                                <td align="right"><b>Apellidos:</b></td>
                                <td><input name="apellidos" type="text" id="apellidos" size="75" value="<?php echo $apellidos;?>"></td>
                            </tr>                              
                            <tr>
                                <td align="right"><b>Departamento: </b></td>   
                                <td> 
                                    <select name="nvoidDepto" id="nvoidDepto" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM departamento";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un Departamento</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($nvoidDepto == $rsA["idDepto"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idDepto"]; ?>"<?php echo $selected;?>>
                                          <?php echo $rsA["Nombre"];?>                
                                      </option>
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>
                                </td>                                
                            </tr>
                            <tr>
                                <td align="right"><b>Tipo: </b></td>
                                <td> 
                                    <select name="nvotipoEmpleado" id="nvotipoEmpleado" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM tipoempleado";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un tipo de Empleado</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($nvotipoEmpleado == $rsA["idTipo"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idTipo"]; ?>"<?php echo $selected;?>>
                                          <?php echo utf8_encode($rsA["Descripcion"]);?>                
                                      </option>
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>
                                </td>                                
                            </tr>                           
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="grabarEmpleado" id="grabarEmpleado" value="GUARDAR"></td>
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

        window.onload=function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        };
    </script>
    
</body>
</html>
