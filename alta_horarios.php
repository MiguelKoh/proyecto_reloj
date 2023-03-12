
<?php

    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("idDepto,idEmp,idTipoEmpleado,idCurso,idSemestre,fechaInicio,fechaFin,horaInicio,horaFin,minInicio,minFin,idDia");
 
    if ($variables[1][2]) {$idDepto    = $variables[1][1];} else {$idDepto = 0;}       
    if ($variables[2][2]) {$idEmp  = $variables[2][1];} else {$idEmp = "";} 
    if ($variables[3][2]) {$idTipoEmpleado    = $variables[3][1];} else {$idTipoEmpleado = 0;}
    if ($variables[4][2]) {$idCurso    = $variables[4][1];} else {$idCurso = 0;}  
    if ($variables[5][2]) {$idSemestre    = $variables[5][1];} else {$idSemestre = 0;} 
    if ($variables[6][2]) {$fechaInicio    = $variables[6][1];} else {$fechaInicio = "";} 
    if ($variables[7][2]) {$fechaFin    = $variables[7][1];} else {$fechaFin = "";} 
    if ($variables[8][2]) {$horaInicio  = $variables[8][1];} else {$horaInicio = 0;}       
    if ($variables[9][2]) {$horaFin  = $variables[9][1];} else {$horaFin = "";} 
    if ($variables[10][2]) {$minInicio    = $variables[10][1];} else {$minInicio = 0;}       
    if ($variables[11][2]) {$minFin  = $variables[11][1];} else {$minFin = "";}     
    if ($variables[12][2]) {$idDia    = $variables[12][1];} else {$idDia = 0;} 

    
    global $fechaInicio,$fechaFin;
  //  $Datos = Request("fechaInicio,fechaFin");	 
  //  $fechaInicio=$Datos[1][1];	
  //<  $fechaInicio=$Datos[2][1];    
    
    
    // proceso para grabar la informacion
    if(isset($_POST['grabarHorarios'])){
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
                
                $hora_inicio = trim($horaInicio).":".trim($minInicio);
                $hora_fin = trim($horaFin).":".trim($minFin);

                //echo $dias_permiso . " - " . $mins_permiso;
                //determinando que días se seleccionaron
                $contador=count($idDia);
                if( $contador > 0){ 
                    if($idDia) 
                         foreach ($idDia as $value) {
                            $idDia = $value;             
               
                            //formateo la fecha para que quede como dd/mm/aaaa
                            $separar = explode("-",$fechaInicio);
                            $año = $separar[0];
                            $mes = $separar[1];
                            $dia = $separar[2];
                            $nva_fecha_Inicio = $dia."/".$mes."/".$año;   

                            //formateo la fecha para que quede como dd/mm/aaaa
                            $separar = explode("-",$fechaFin);
                            $año = $separar[0];
                            $mes = $separar[1];
                            $dia = $separar[2];
                            $nva_fecha_Fin = $dia."/".$mes."/".$año;                      

                            //grabo informacion de permisos en tabla
                            $sente = "INSERT INTO horarios_semestre (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,".
                                    "id_semestre,semana_descarga,fecha_ini,fecha_fin,id_tipo_empleado,debe_checar) ".  
                                    "VALUES (".$idEmp.",".$idDia.",'".$hora_inicio." A ".$hora_fin."','".$hora_inicio.
                                    "','".$hora_fin."',".$idCurso.",".$idSemestre.",'','".$nva_fecha_Inicio."','".$nva_fecha_Fin.
                                    "',1,'')";
                            $result = mysqli_query($cn,$sente); 
                           // echo $sente;
                         }
                         echo '<script>alert ("Los horarios se grabaron correctamente")</script>';
                }
                $idDepto = "";
                $idEmp = "";
                $fechaFin = "";
                $fechaInicio = "";
                $minInicio = "";
                $horaInicio = "";
                $minFin = "";
                $horaFin = "";
                $idSemestre = "";
                $idTipoEmpleado = "";

                echo "<script>location.href='alta_horarios.php'</script>";                               
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
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Alta de Horarios de Empleados </b></h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='alta_horarios.php'><img src='imagen/horario.gif' alt='Alta de Horarios' width='25' height='25' border='0' /></a>
                                    <a href='reporte_horarios.php'><img src='imagen/buscar.gif' alt='Actualizar Horarios' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                                                                            
                            <tr>
                              <td  align="right"><b>Departamento: </b></td>
                                <td> 
                                    <select name="idDepto" id="idDepto" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM departamento";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un Departamento</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idDepto == $rsA["idDepto"]) {
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
                                <td align="right"><b>Empleado:</b></td>
                                <td><label>
                                       <?php 
                                            
                                              $SQL = "SELECT idemp, Nombre from empleado WHERE Estatus is Null AND idDepto = ".$idDepto." ORDER BY Nombre,idemp";
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
                                <td align="right"><b>Tipo de contrato para el horario: </b></td>
                                <td>
                                    <select name="idTipoEmpleado" id="idTipoEmpleado" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idTipoEmpleado == 0) {echo " selected";}?>>Seleccione tipo de contrato</option>
                                      <?php 
                                           $SQLp = "SELECT idTipo,descripcion FROM tipoempleado WHERE esEmpleado = 'S'";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <?php 
                                          while( $rsA = mysqli_fetch_array($queryA) ) { 
                                              if ($idTipoEmpleado == $rsA["idTipo"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idTipo"]; ?>"<?php echo $selected;?>><?php echo utf8_encode($rsA["descripcion"]);?></option>
                                      
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>
                                </td>
                            </tr>                                
                            <tr>
                                <td align="right"><b>Curso escolar: </b></td>
                                <td>
                                    <select name="idCurso" id="idCurso" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idCurso == 0) {echo " selected";}?>>Seleccione un Curso</option>
                                      <?php 
                                           $SQLp = "SELECT idcurso,descripcion FROM curso_escolar";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <?php 
                                          while( $rsA = mysqli_fetch_array($queryA) ) { 
                                              if ($idCurso == $rsA["idcurso"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idcurso"]; ?>"<?php echo $selected;?>><?php echo utf8_encode($rsA["descripcion"]);?></option>
                                      
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><b>Semestre:</b></td>
                                <td><label>
                                       <?php 
                                            
                                              $SQL = "SELECT idsemestre, descripcion from semestre WHERE idcurso = ".$idCurso." ORDER BY idsemestre";
                                              $query = mysqli_query($cn,$SQL);
                                              $frows = mysqli_num_rows($query);
                                              if ($frows > 0) {
                                      ?>
                                    <select name="idSemestre" id="idSemestre" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idSemestre == 0) {echo " selected";}?>>Seleccione un semestre</option>
                                      <?php while ($rs = mysqli_fetch_array($query)) {
                                                   if ($idSemestre == $rs["idsemestre"]) {$selected = " selected";} else {$selected = "";} ?>
                                      <option value="<?PHP echo $rs["idsemestre"]; ?>"<?php echo $selected;?>>
                                          <?php 
                                            echo $rs['descripcion'];
                                            $idemp = $rs['idsemestre'];      
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
                                <td align="right"><b>Fecha inicio de semestre para el empleado</b></td>
                                <td><input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, frmPermisos.fechaInicio, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>"></td>
                            </tr>
                            <tr>
                                <td align="right"><b>Fecha fin de semestre para el empleado</b></td>
                                <td><input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, frmPermisos.fechaFin, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaFin);?>"></td>
                            </tr>                                                    
                            <tr>
                                <td align="right"><b>Hora inicio:</b></td>
                                <td>                                    
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
                                <td align="right"><b>Hora fin:</b></td>
                                <td>                                                                        
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
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                                <td align="right"><b>Dias:</b></td>
                                <td colspan ="3">
                                    <?php 
                                        for ($qDia = 1; $qDia <= 7; $qDia++){    
                                            $nombre_dia = nombre_dia($qDia);                                                                            
                                    ?>
                                    <input name="idDia[]" type="checkbox" value="<?php echo $qDia; ?>"><b><?php echo $nombre_dia; ?> </b>                                                                                                                                                
                                    <?php
                                        }
                                    ?>
                                </td>                              
                            </tr> 
                        
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="grabarHorarios" id="grabarHorarios" value="GUARDAR"></td>
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
       
    </script>
    
</body>
</html>