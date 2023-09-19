
<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("idDepto,idEmp,fechaInicio,fechaFin,horaInicio,horaFin,minInicio,minFin,tipoPermiso,descPermiso");
 
    if ($variables[1][2]) {$idDepto    = $variables[1][1];} else {$idDepto = 0;}       
    if ($variables[2][2]) {$idEmp  = $variables[2][1];} else {$idEmp = "";} 
    if ($variables[3][2]) {$fechaInicio    = $variables[3][1];} else {$fechaInicio = "";}       
    if ($variables[4][2]) {$fechaFin  = $variables[4][1];} else {$fechaFin = "";} 
    if ($variables[5][2]) {$horaInicio  = $variables[5][1];} else {$horaInicio = 0;}       
    if ($variables[6][2]) {$horaFin  = $variables[6][1];} else {$horaFin = "";} 
    if ($variables[7][2]) {$minInicio    = $variables[7][1];} else {$minInicio = 0;}       
    if ($variables[8][2]) {$minFin  = $variables[8][1];} else {$minFin = "";}     
    if ($variables[9][2]) {$tipoPermiso    = $variables[9][1];} else {$tipoPermiso = 0;}       
    if ($variables[10][2]) {$descPermiso  = $variables[10][1];} else {$descPermiso = "";}      
    
    global $fechaInicio,$fechaFin;
  //  $Datos = Request("fechaInicio,fechaFin");	 
  //  $fechaInicio=$Datos[1][1];	
  //<  $fechaInicio=$Datos[2][1];    
    
    
    // proceso para grabar la informacion
    if(isset($_POST['grabarPermisos'])){
        //validaciones
        //que la fecha final no sea menor a la de inicio
        if ($fechaInicio > $fechaFin){
            echo '<script>alert ("La fecha final no puede ser menor a la inicial")</script>';
        }else{        
            if (trim($horaInicio) > trim($horaFin)) {
                echo '<script>alert ("La hora final no puede ser menor a la inicial")</script>';
            }else{
        
                //preparo informacion
                if ($minInicio == "0"){
                    $minInicio = "00";
                }
                
                if ($minFin == "0"){
                    $minFin = "00";
                }
                
                $hora_salida = trim($horaInicio).":".trim($minInicio);
                $hora_regreso = trim($horaFin).":".trim($minFin);

                $dias_permiso = dias_transcurridos($fechaInicio,$fechaFin);        
                $mins_permiso =  calcular_tiempo_trasnc($hora_salida, $hora_regreso);

                //echo $dias_permiso . " - " . $mins_permiso;

                for ($iDias = 0; $iDias < $dias_permiso; $iDias++){
                    $nva_fecha = aumenta_o_quita_dias_fecha($fechaInicio,$iDias);
                   // echo $iDias . " % " . $nva_fecha . " - ";

                    //obtengo a que periodo pertenece la fecha
                    $sente = "SELECT idperiodo FROM periodos ".
                             "where '" . $nva_fecha . "' between STR_TO_DATE(fecha_inicio,'%d/%m/%Y') " .
                             "and STR_TO_DATE(fecha_fin,'%d/%m/%Y')";
                    $result = mysqli_query($cn,$sente);
                    if ($row = mysqli_fetch_array($result)){
                        $idperiodo = $row['idperiodo'];
                    }

                    //Obtengo la descripcion del tipo de permiso
                    $sente = "SELECT descripcion FROM tipo_permisos where idtipo_permisos = " . $tipoPermiso;
                    $result = mysqli_query($cn,$sente);
                    if ($row = mysqli_fetch_array($result)){
                        $desc_tipo_permiso = $row['descripcion'];
                    }
                    
                    //formateo la fecha para que quede como dd/mm/aaaa
                    $separar = explode("-",$nva_fecha);
                    $año = $separar[0];
                    $mes = $separar[1];
                    $dia = $separar[2];
                    $nva_fecha = $dia."/".$mes."/".$año;   

                    //grabo informacion de permisos en tabla
                     $sente = "INSERT INTO permisos (idEmp,fechaIni,horaIni,fechaFin,horaFin,tipo,motivo,horaCaptura,minutosDiarios,idperiodo) ".
                            "VALUES (".$idEmp.",'".$nva_fecha."','".$hora_salida."','".$nva_fecha."','".$hora_regreso.
                            "','".$desc_tipo_permiso."','".$descPermiso."','".date("d/m/Y H:i:s")."',".$mins_permiso.",".$idperiodo.")";            
                    $result = mysqli_query($cn,$sente) ;                        

                }
               //echo $hora_salida;
            
                echo '<script>alert ("Los permisos se grabaron correctamente")</script>';

                $idDepto = "";
                $idEmp = "";
                $fechaFin = "";
                $fechaInicio = "";
                $minInicio = "";
                $horaInicio = "";
                $minFin = "";
                $horaFin = "";
                $tipoPermiso = "";
                $descPermiso = "";

                echo "<script>location.href='captura_permisos.php'</script>";
                
                
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
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon_uady.ico">

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    

    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-************" crossorigin="anonymous" />
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">

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
                <li class="FondoNav">
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
        
    
        <table style="text-align:center;background-color: white" width="100%" border="0">

            <tr>
                <td align="center">
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmPermisos" id="frmPermisos" method="post">
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Alta de Permisos de Empleados</b></h3></td>
                            </tr> 
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='captura_permisos.php'><img src='imagen/addpermiso.gif' alt='Agregar Permisos' width='30' height='30' border='0' /></a>
                                    <a href='reporte_permisos.php'><img src='imagen/buscar.gif' alt='Consultar Permisos' width='30' height='30' border='0' /></a>
                                </td>   
                            </tr>                                                                          
                            <tr>
                              <td align="right"><b>Departamento: </b></td>
                                <td> 
                                    
                                    <select name="idDepto" id="idDepto" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM departamento ORDER BY Nombre";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un departamento</option>
                                      
                                      <?php 
                                          
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idDepto == $rsA["idDepto"]) {
                                                  $selected = "selected";
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
                                <td><label>
                                       <?php 
                                            
                                              $SQL = "SELECT idemp, Nombre from empleado WHERE Estatus is NUll AND idDepto = ".$idDepto." ORDER BY Nombre,idemp";
                                              $query = mysqli_query($cn,$SQL);
                                              $frows = mysqli_num_rows($query);
                                              if ($frows > 0) {
                                      ?>
                                    <select name="idEmp" id="idEmp" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idEmp == 0) {echo " selected";}?>>Seleccione un Empleado</option>
                                      <?php while ($rs = mysqli_fetch_array($query)) {
                                                   if ($idEmp == $rs["idemp"]) {$selected = " selected";} else {$selected = "";} ?>
                                      <option value="<?PHP echo $rs["idemp"]; ?>"<?php echo $selected;?>>
                                          <?php 
                                            echo $rs['idemp']." - ". utf8_encode($rs['Nombre']);
                                            $idemp = $rs['idemp'];      
                                          ?>
                                      </option>
                                      <?php } mysqli_free_result($query);?>
                                    </select>
                                  <?php } else { ?>
                                 <p> N/A</p>  
                                  <?php } ?>
                                </td>
                                
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><b> 
                                    <?php
                                    if ($idEmp!=null){
                                        $horario = obtenerHorarioTeorico($idEmp);  
                                        $desc_semestre = $horario[0];
                                        $total = $horario[1];
                                        echo "Horario vigente del ",$desc_semestre;
                                        
                                        for ($a = 2; $a<= $total; $a++){
                                    ?></b>
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
                                <td align="right"><b>Fecha/Hora inicio:</b></td>
                                <td>
                                    <input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, frmPermisos.fechaInicio, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>">                            
                                    <select name="horaInicio" id="horaInicio" onChange="javascript:submit()">
					<option value="0">00</option>
					<?php
					for ($iHora = 1; $iHora <= 23; $iHora++) {
                                            if ($horaInicio == $iHora) {
                                                $selected = " selected";
                                            } 
                                            else {
						$selected = "";
                                            }
                                        ?>
					<option value="
                                            <?php 
                                                if ($iHora < 10){
                                                  $iHora = "0".$iHora;  
                                                }
                                                echo $iHora;
                                            ?>"<?php echo $selected;?>><?php echo $iHora;?></option>
					<?php
					}
					?>
				   </select>
                                    <select name="minInicio" id="minInicio" onChange="javascript:submit()">
					<option value="0">00</option>
					<?php
					for ($iMin = 1; $iMin <= 59; $iMin++) {
                                            if ($minInicio == $iMin) {
                                                $selected = " selected";
                                            } 
                                            else {
						$selected = "";
                                            }
                                        ?>
					<option value="
                                            <?php 
                                                if ($iMin < 10){
                                                  $iMin = "0".$iMin;  
                                                }
                                                echo $iMin;
                                            ?>"<?php echo $selected;?>><?php echo $iMin;?></option>
					<?php
					}
					?>
				   </select>	                                       
                                </td>                              
                            </tr>
                            <tr>
                                <td align="right"><b>Fecha/Hora fin:</b></td>
                                <td>
                                    <input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, frmPermisos.fechaFin, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaFin);?>">                                    
                                    <select name="horaFin" id="horaFin" onChange="javascript:submit()">
					<option value="0">00</option>
					<?php
					for ($iHora = 1; $iHora <= 23; $iHora++) {
                                            if ($horaFin == $iHora) {
                                                $selected = " selected";
                                            } 
                                            else {
						$selected = "";
                                            }
                                        ?>
					<option value="
                                            <?php 
                                                if ($iHora < 10){
                                                  $iHora = "0".$iHora;  
                                                }
                                                echo $iHora;
                                            ?>"<?php echo $selected;?>><?php echo $iHora;?></option>
					<?php
					}
					?>
				   </select>
                                    <select name="minFin" id="minFin" onChange="javascript:submit()">
					<option value="0">00</option>
					<?php
					for ($iMin = 1; $iMin <= 59; $iMin++) {
                                            if ($minFin == $iMin) {
                                                $selected = " selected";
                                            } 
                                            else {
						$selected = "";
                                            }
                                        ?>
					<option value="
                                            <?php 
                                                if ($iMin < 10){
                                                  $iMin = "0".$iMin;  
                                                }
                                                echo $iMin;
                                            ?>"<?php echo $selected;?>><?php echo $iMin;?></option>
					<?php
					}
					?>
				   </select>                                         
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><b>Tipo de Permiso:</b> </td>
                                <td>
                                    <select name="tipoPermiso" id="tipoPermiso" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp = "SELECT idtipo_permisos,descripcion FROM tipo_permisos";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <?php 
                                          while( $rsA = mysqli_fetch_array($queryA) ) { 
                                              if ($tipoPermiso == $rsA["idtipo_permisos"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idtipo_permisos"]; ?>"<?php echo $selected;?>><?php echo $rsA["descripcion"];?></option>
                                      
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><b>Descripcion:</b></td>
                                <td>                                   
                                    <textarea rows="4" cols="50" name="descPermiso" id="descPermiso" size="80" value=""><?php echo $descPermiso;?></textarea> 
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr  align="center">
                                <td>
                                    <input type="submit" name="grabarPermisos" id="grabarPermisos" value="GUARDAR"></td>
                                    <input type="button" id="btnSave" value="save">
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

 


    <script src="script-permisos.js"></script>
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

        
        $("#wrapper").toggleClass("toggled");
        
    </script>
    
</body>
</html>