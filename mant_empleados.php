
<?php

    session_start();
    include('conex.php'); 
    include('funciones_reloj.php');
    $cn = ConectaBD();
  
    $variables=Request("idDepto,idEmp,nvoidDepto,nvotipoEmpleado,nvoEstatus,nvoNombre,nvoClave");
 
    if ($variables[1][2]) {$idDepto    = $variables[1][1];} else {$idDepto = 0;}       
    if ($variables[2][2]) {$idEmp  = $variables[2][1];} else {$idEmp = "";} 
    if ($variables[3][2]) {$nvoidDepto    = $variables[3][1];} else {$nvoidDepto = 0;}       
    if ($variables[4][2]) {$nvotipoEmpleado  = $variables[4][1];} else {$nvotipoEmpleado = 0;}       
    if ($variables[5][2]) {$nvoEstatus  = $variables[5][1];} else {$nvoEstatus = "";}   
      if ($variables[6][2]) {$nvoNombre    = $variables[6][1];} else {$nvoNombre = 0;}       
           
    if ($variables[7][2]) {$nvoClave  = $variables[7][1];} else {$nvoClave = "";}  
    // proceso para grabar la informacion
    if(isset($_POST['grabarInfoEmp'])){
        //validaciones
        //que la fecha final no sea menor a la de inicio
        if ($nvoidDepto == "0" && $nvotipoEmpleado == "0" && $nvoEstatus == "0"){
            echo '<script>alert ("No se ha seleccionado ninguna cambio en la informacion")</script>';
        }else{    
                //verifico que información se grabara
                //Cambio de departamento
                if ($nvoidDepto != $idDepto && $nvoidDepto != 0 ){
                    $sente = "UPDATE empleado SET iddepto = " . $nvoidDepto . " WHERE idemp = " . $idEmp;
                    $result = mysqli_query($cn,$sente);
                                        
                }
                 
                if ($nvoNombre != ""){
                    $sente = "UPDATE empleado SET Nombre  = '" . $nvoNombre . "' WHERE idemp = " . $idEmp;
                    $result = mysqli_query($cn,$sente);
                    
                }


                //Cambio de tipo empleado
                $idtipo = $_POST['idtipo'] ;
                if ($nvotipoEmpleado != $idtipo  && $nvotipoEmpleado != 0){
                    $sente = "UPDATE empleado SET idtipo  = " . $nvotipoEmpleado . " WHERE idemp = " . $idEmp;
                    $result = mysqli_query($cn,$sente);
                    
                }
                
                //Cambio de estatus
                $estatus = $_POST['estatus'];
                if ($nvoEstatus != $estatus && $nvoEstatus != 0){
                    if ($nvoEstatus == "2"){
                $nvoEst = "B";
                $sente = "UPDATE empleado SET Estatus  = '" . $nvoEst . "' WHERE idemp = " . $idEmp;
                    }else{
                $nvoEst = "null";
                $sente = "UPDATE empleado SET Estatus  = null WHERE idemp = " . $idEmp;
                    }
                    
            //$sente = "UPDATE empleado SET Estatus  = '" . $nvoEst . "' WHERE idemp = " . $idEmp;
            $result = mysqli_query($cn,$sente);
                    
                }

                echo '<script>alert ("La informacion del empleado se actualizo correctamente")</script>';

                $idDepto = "0";
                //$idEmp = "0";
                $nvoidDepto = "0";
                $nvotipoEmpleado = "0";
                $nvoEstatus = "0";

             //   echo "<script>location.href='captura_permisos.php'</script>";
                
                
            
        }
    }
      
  
    
?>
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

        <table style="text-align:center;background-color: white" width="100%" border="0">
            <tr>
                <td align="center">
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmPermisos" id="frmPermisos" method="post">
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Actualizar Empleados </b></h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='alta_Empleados.php'><img src='imagen/addempleado.png' alt='Alta de Empleados' width='30' height='30' border='0' /></a>
                                    <a href='consultar_empleados.php'><img src='imagen/buscar.gif' alt='Actualizar Empleado' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                                                                   
                            
                            <tr>
                                <td align="right"><b>Empleado:</b></td>
                                <td>
                                       <?php 
                                            
                                             $SQL = "SELECT idemp, Nombre,idDepto,estatus from empleado WHERE idEmp = ".$idEmp." ORDER BY idemp";
                                              $query = mysqli_query($cn,$SQL);
                                              $frows = mysqli_num_rows($query);
                                              if ($frows > 0) {
                                      ?>                                   
                                      <?php $rs = mysqli_fetch_array($query);?>
                                       <?php 
                                            echo $rs['idemp']." - ". $rs['Nombre'];
                                            $idemp = $rs['idemp']; 
                                            $idept = $rs['idDepto'];
                                            $nombres = $rs['Nombre'];
                                            $estatus = $rs['estatus'];
                                            if($estatus=='')
                                                $nvoEstatus=1;
                                            else
                                                $nvoEstatus=2;
                                          ?>
                                    <?php mysqli_free_result($query);?>
                                   
                                  <?php } else { ?>
                                 <p> N/A</p>  
                                  <?php } ?>
                                </td>
                                
                            </tr>
                            <tr>
                              <td align="right"><b>Departamento:</b> </td>
                                <td> 
                                    
                                      <?php 
                                           $SQLp="SELECT Nombre FROM departamento WHERE idDepto=".$idept."";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>                                     
                                      <?php $rsA=mysqli_fetch_array($queryA);?>
                                      <?php echo utf8_encode($rsA["Nombre"]);?>                                              
                                      <?php  mysqli_free_result($queryA);?>
                                    
                                </td>
                               
                            </tr>
    
                            <tr>
                                <td align="right"><strong>Tipo de empleado:  </strong></td>
                                <td><?php 
                                        if ($idEmp > 0){
                                            $sente = "select idtipo,practicante from empleado where idemp = " . $idEmp;
                                            $result = mysqli_query($cn,$sente);
                                            if ($row = mysqli_fetch_array($result)){
                                                $idtipo = $row['idtipo'];
                                                $practicante = $row['practicante'];
                                            }
                                            
                                            //descripcion tipo empleado
                                           // if ($practicante == "S") {
                                           //     $desc_tipo = "Servicio Social";
                                           // }else{
                                                $sente = "select Descripcion from tipoempleado where idtipo = " . $idtipo;
                                                $result = mysqli_query($cn,$sente);
                                                if ($row = mysqli_fetch_array($result)){
                                                    $desc_tipo = $row['Descripcion'];                                                     
                                                }
                                            //}
                                            echo utf8_encode($desc_tipo);
                                        ?>
                                        <tr><td><input type="hidden" value="<?php $idtipo; ?>" name="idtipo" /></td></tr>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"S><strong>Estatus:  </strong></td>
                                <td><?php 
                                        if ($idEmp > 0){
                                            $sente = "select estatus from empleado where idemp = " . $idEmp;
                                            $result = mysqli_query($cn,$sente);
                                            if ($row = mysqli_fetch_array($result)){
                                                $estatus = $row['estatus'];
                                            }

                                            if ($estatus == "B"){
                                                $desc_estatus = "Baja";
                                            }else {
                                                $desc_estatus = "Activo";
                                            }
                                            echo $desc_estatus;
                                         ?>
                                            <tr><td><input type="hidden" value="<?php $estatus; ?>" name="estatus" /></td></tr>
                                    <?php
                                        }
                                    ?>
                                </td>
                              
                            </tr>                              
                            <tr>
                                <td align="right"><strong>Fecha del primer registro de asistencia:  </strong></td>
                                <td><?php 
                                        
                                        if ($idEmp > 0){
                                            $sente = "select min(STR_TO_DATE(fecha,'%d/%m/%Y')) as fecha_registro from checadas " .
                                                "where idemp = " . $idEmp;
                                            $result = mysqli_query($cn,$sente);
                                            $existeregistro=mysqli_num_rows($result);
                                            if ($row = mysqli_fetch_array($result)){
                                                $fecha_registro = $row['fecha_registro'];
                                            }
                                            //echo $fecha_registro."sdsdf";
                                            if($existeregistro>0 && $fecha_registro!='')
                                            echo cambiafnormal($fecha_registro);
                                        else
                                            echo "SIN REGISTROS";
                                         ?>
                                    <?php
                                        }
                                    ?>
                                </td>                              
                            </tr> 
                            <tr>
                                <td align="right"><strong>Observaciones: </strong></td>
                                <td><?php                                 
                                        if ($idEmp > 0){
                                            $practicante = "";
                                            
                                            $sente = "select Practicante from empleado " .
                                                "where idemp = " . $idEmp;
                                            $result = mysqli_query($cn,$sente);
                                            if ($row = mysqli_fetch_array($result)){
                                                $practicante = $row['Practicante'];
                                            }
                                            
                                            if($practicante == "S"){
                                                $practicante = "Servicio Social";
                                            }
                                            echo $practicante;
                                         ?>
                                    <?php
                                        }
                                    ?>
                                </td>                              
                            </tr>                             
                                                     
                            <tr>
                                <td>&nbsp;</td>
                                <td>Horario te&oacute;rico vigente  
                                    <?php
                                    if (isset($idEmp)){
                                        $horario = obtenerHorarioTeorico($idEmp);  
                                        $desc_semestre = $horario[0];
                                        $total = $horario[1];
                                        echo $desc_semestre;
                                        
                                        for ($a = 2; $a<= $total; $a++){
                                    ?>
                                </td>
                            </tr>
                            <tr style="font-style: italic">
                                <td>&nbsp;</td>
                                <td><li>
                                    <?php
                                        echo $horario[$a];
                                    ?></li>
                                </td>
                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>             
                            
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:left;vertical-align:middle;text-decoration: underline;"><strong>ACTUALIZAR INFORMACI&Oacute;N</strong></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="right"><b>Clave:</b></td>
                                <td><input name="NvoClave" type="text" id="idEmp" size="5" value="<?php echo $idEmp;?>" disabled></td>
                            </tr>
                            <tr>
                                <td align="right"><b>Nombres:</b></td>
                                <td><input name="nvoNombre" type="text" id="nombres" size="45" value="<?php echo $nombres;?>"></td>
                            </tr>
    
                            <tr>
                                <td align="right"><b>Nuevo Departamento: </b></td>   
                                <td> 
                                    <select name="nvoidDepto" id="nvoidDepto">
                                      <?php 
                                           $SQLp="SELECT * FROM departamento ";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un Departamento</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idept == $rsA["idDepto"]) {
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
                                <td align="right"><b>Tipo de Empleado: </b></td>
                                <td> 
                                    <select name="nvotipoEmpleado" id="nvotipoEmpleado">
                                      <?php 
                                           $SQLp="SELECT * FROM tipoempleado";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un tipo de Empleado</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idtipo == $rsA["idTipo"]) {
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
                                <td align="right"><b>Estatus: </b></td>
                                <td>
                                    <select name="nvoEstatus" id="nvoEstatus">
					<option value="0">Selecciona un estatus</option>
					<?php
					for ($i = 1; $i <= 2; $i++) {
                                            if ( $i == 1 ){ 
                                                $itexto = "Activo"; //1                                                
                                            } else {
                                                $itexto = "Baja";   //2                                             
                                            }    
                                            if ($nvoEstatus == $i) {
                                                $selected = " selected";
                                            } 
                                            else {
						$selected = "";
                                            }
                                        ?>
					<option value="
                                            <?php 
                                                echo $i;
                                            ?>"<?php echo $selected;?>><?php echo $itexto;?></option>
					<?php
					}
					?>
				   </select>  
                                </td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="grabarInfoEmp" id="grabarPermisos" value="ACTUALIZAR"></td>
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
