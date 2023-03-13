
<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("idDepto,idEmp,idCurso,idSemestre");
 
    if ($variables[1][2]) {$idDepto    = $variables[1][1];} else {$idDepto = 0;}       
    if ($variables[2][2]) {$idEmp      = $variables[2][1];} else {$idEmp = "";} 
    if ($variables[3][2]) {$idCurso    = $variables[3][1];} else {$idCurso = 0;}  
    if ($variables[4][2]) {$idSemestre = $variables[4][1];} else {$idSemestre = 0;}     
    
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
                <li class="FondoNav">
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

        <table style="text-align:center;background-color: white" width="100%" border="0">

            <tr>
                <td align="center">
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmPermisos" id="frmPermisos" method="post">
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Consultar / Eliminar Horarios de Empleado </b></h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='alta_horarios.php'><img src='imagen/horario.gif' alt='Alta de Horarios' width='25' height='25' border='0' /></a>
                                    <a href='reporte_horarios.php'><img src='imagen/buscar.gif' alt='Actualizar Horarios' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                                                                           
                            <tr>
                              <td align="right"><b>Departamento: </b></td>
                                <td> 
                                    <select name="idDepto" id="idDepto" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM departamento";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un departamento</option>
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
                                <td align="right"><b>Curso escolar: </b></td>
                                <td>
                                    <select name="idCurso" id="idCurso" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idCurso == 0) {echo " selected";}?>>Seleccione un curso</option>
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
                                      <option value="<?php echo $rsA["idcurso"]; ?>"<?php echo $selected;?>><?php echo $rsA["descripcion"];?></option>
                                      
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
                                <td>&nbsp;</td>
                            </tr>
 
                            <?php
                                if(isset($_POST['buscaHorarios']) || (isset($idSemestre) && $idSemestre>0)){
                                    //* validaciones    
                                        //*obtengo la lista de los permisos que capturados en la fecha seleccionada*
                                     /*   $sente = "SELECT idhorario,idemp,date(fecha_ini) as fecha_ini,date(fecha_fin) as fecha_fin,".
                                                 "dia_semana,hora_ini,hora_fin,idsemestre ".
                                                 "FROM horarios_semestre where idsemestre = " . $idSemestre .
                                                 " and idemp = " . $idEmp . 
                                                 " order by dia_semana, hora_ini";      */
                                        $sente = "SELECT a.id as idhorario,a.idEmp,a.fecha_ini,a.fecha_fin,a.id_dia as dia_semana,".
                                                "a.hora_ini,a.hora_fin,".
                                                "a.id_semestre as idsemestre,a.semana_descarga,a.id_tipo_empleado," .
                                                "b.descripcion as nombre_tipo_empleado from horarios_semestre as a ".
                                                "inner join tipoempleado as b on a.id_tipo_empleado = b.idTipo " .
                                                " where a.id_semestre = " . $idSemestre . " and a.idEmp = " . $idEmp .
                                                " order by a.fecha_ini,a.id_dia, a.hora_ini";
                                        $result = mysqli_query($cn,$sente);
                                        $numR=mysqli_num_rows( $result);
                                        if($numR>0){
                            ?>
                                <table width='90%' cellpadding='1' cellspacing='1' border='1'>
                                    <tr style="color: #fff;background-color:#212529;text-align: center;font-weight: bold;">
                                        <td>Fecha Inicio</td>
                                        <td>Fecha Fin</td>
                                        <td>Dia Semana</td>
                                        <td>Hora inicio</td>
                                        <td>Hora Fin</td>
                                        <td>Tipo Empleado Segun Horario</td>
                                        <td>Eliminar</td>
                                    </tr>
                                    <?php
                                    $fecha_inicio = "";
                                    $fecha_final = "";
                                    while ($row = mysqli_fetch_array($result)){
                                        $dia_semana = $row['dia_semana'];
                                        $nombre_dia = nombre_dia($dia_semana);
                                     echo "                                       
                                        <tr>
                                            <td align='center'>".$row['fecha_ini']."</td>
                                            <td align='center'>".$row['fecha_fin']."</td>
                                            <td align='center'>".$nombre_dia."</td>
                                            <td align='center'>".$row['hora_ini']."</td>
                                            <td align='center'>".$row['hora_fin']."</td>  
                                            
                                            <td align='center'>".utf8_encode($row['nombre_tipo_empleado'])."</td>
                                            <th><a href='eliminar_horarios.php?idhorario=".$row['idhorario'].
                "' onclick='return confirm('¿Seguro que desea BORRAR el elemento ?\n\rNo se puede deshacer')' >
  <img src='imagen/eliminar.png' alt='¡PRECAUCIÓN! BORRAR' width='20' height='20' border='0' /></a>
                                        </tr>";
                                    }
                                     ?>
                                </table>
                            <?php
                                   }      
                              }                            
                            ?>                
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

        window.onload=function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        };
    </script>
    
</body>
</html>

