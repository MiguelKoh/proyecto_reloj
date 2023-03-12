<?php

    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("idDepto,idEmp,idCurso,idSemestre");
 
    if ($variables[1][2]) {$idDepto    = $variables[1][1];} else {$idDepto = 0;}       
    if ($variables[2][2]) {$idEmp  = $variables[2][1];} else {$idEmp = "0";} 
    if ($variables[3][2]) {$idCurso    = $variables[3][1];} else {$idCurso = "0";}       
    if ($variables[4][2]) {$idSemestre  = $variables[4][1];} else {$idSemestre = "0";}       
   
    if ((isset($_GET['idPermisos'])) && ($_GET['idPermisos'] != "")) {

            $SQLpermisos="SELECT p.idPermisos,p.idemp,s.idsemestre,s.idcurso 
            FROM permisos p 
            INNER JOIN periodos pr ON p.idperiodo=pr.idperiodo
            INNER JOIN semestre s ON s.idsemestre=pr.id_semestre
            where p.idPermisos = ".$_GET['idPermisos']."";
            $querypermisos=mysqli_query($cn,$SQLpermisos);
            $permi=mysqli_fetch_array($querypermisos);
            $idEmp=$permi['idemp'];
            $idCurso=$permi['idcurso'];
            $idSemestre=$permi['idsemestre'];
            mysqli_free_result($querypermisos);
            $sente = "DELETE FROM permisos WHERE idPermisos = ".$_GET['idPermisos'];
            $result = mysqli_query($cn,$sente);
            
           //echo '<script>alert ("El permiso se borro correctamente")</script>';
           echo '<script>location.href="reporte_permisos_empleados.php?idEmp='.$idEmp.'&idCurso='.$idCurso.'&idSemestre='.$idSemestre.'"</script>';
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
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmPermisos" id="frmPermisos" method="post">
                           
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Consultar Permisos</b></h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='alta_Empleados.php'><img src='imagen/addempleado.png' alt='Alta de Empleados' width='30' height='30' border='0' /></a>
                                    <a href='consultar_empleados.php'><img src='imagen/buscar.gif' alt='Actualizar Empleado' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                                                              
                            
                            <tr>
                                <td align="right"><b>Empleado:</b></td>
                                <td><label>
                                       <?php 
                                            
                                              $SQL = "SELECT idemp, Nombre,idDepto from empleado WHERE idEmp = ".$idEmp." ORDER BY idemp";
                                              $query = mysqli_query($cn,$SQL);
                                              $frows = mysqli_num_rows($query);
                                              if ($frows > 0) {
                                      ?>                                                                    
                                      <?php $rs = mysqli_fetch_array($query);                          
                                            echo $rs['idemp']." - ". $rs['Nombre'];
                                            $idemp = $rs['idemp']; 
                                            $idDepto = $rs['idDepto'];      
                                      ?>
                                     
                                      <?php mysqli_free_result($query);?>                                    
                                  <?php } else { ?>
                                 <p> N/A</p>  
                                  <?php } ?>
                                </td>                                
                            </tr>
                            <tr>
                              <td align="right"><b>Departamento: </b></td>
                                <td> 
                                    
                                      <?php 
                                           $SQLp="SELECT * FROM departamento WHERE idDepto=".$idDepto."";
                                           $queryA = mysqli_query($cn,$SQLp);
                                           $rsA=mysqli_fetch_array($queryA);                                     
                                           echo $rsA["Nombre"];?>                                     
                                      <?php  mysqli_free_result($queryA);?>                                   
                                </td>
                               
                            </tr>           
                            <tr>
                                <td align="right"><b>Curso:</b></td>
                                <td>
                                    <select name="idCurso" id="idCurso" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM curso_escolar";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un Curso Escolar</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idCurso == $rsA["idcurso"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idcurso"]; ?>"<?php echo $selected;?>>
                                          <?php echo $rsA["descripcion"];?>                
                                      </option>
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>                                                                   
                                </td>                              
                            </tr>
                            <tr>
                                <td align="right"><b>Semestre:</b></td>
                                <td>
                                    <select name="idSemestre" id="idSemestre" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM semestre WHERE idcurso=".$idCurso."";
                                           $queryA = mysqli_query($cn,$SQLp);
                                      ?>
                                      <option value="0">Seleccione un Semestre</option>
                                      <?php 
                                          while( $rsA=mysqli_fetch_array($queryA) ) { 
                                              if ($idSemestre == $rsA["idsemestre"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idsemestre"]; ?>"<?php echo $selected;?>>
                                          <?php echo $rsA["descripcion"];?>                
                                      </option>
                                      <?php } mysqli_free_result($queryA);?>
                                    </select>                                                                          
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                   <!-- <input type="submit" name="buscaPermisos" id="buscaPermisos" value="BUSCAR">-->

                                   </td>
                               
                            </tr>

                            <?php
                                //if(isset($_POST['buscaPermisos'])){
                                if(isset($idSemestre) && $idSemestre>0){
                                    //validaciones
                                    //que la fecha final no sea menor a la de inicio
                                    if ($idCurso ==0 || $idSemestre ==0 ){
                                        echo '<script>alert ("Debe seleccionar un curso y semestre")</script>';
                                    }else{    
                                        //obtengo la lista de los permisos que capturados en la fecha seleccionada
                                        $sente = "SELECT p.idPermisos,p.fechaIni,dayofweek(STR_TO_DATE(fechaIni,'%d/%m/%Y')) as dia_semana,p.horaIni,p.horaFin,p.tipo,p.motivo,p.minutosDiarios,pr.id_semestre FROM permisos p INNER JOIN periodos pr ON p.idperiodo=pr.idperiodo where p.idemp = " . $idEmp . " AND pr.id_semestre=" . $idSemestre . " order by STR_TO_DATE(fechaini,'%d/%m/%Y')";            
                                        $result = mysqli_query($cn,$sente);
                                        $numreg = mysqli_num_rows($result );
                                        if($numreg>0){
                                ?>
                                
                                <table width='100%' cellpadding='1' cellspacing='1' border='1'>
                                    <tr style="color: #fff;background-color:#212529;text-align: center;font-weight: bold;">
                                        <td>Fecha</td>
                                        <td>Dia</td>
                                        <td>Hora inicio</td>
                                        <td>Hora Fin</td>
                                        <td>Tipo Permiso</td>
                                        <td>Motivo</td>
                                        <td>Minutos</td>
                                        <td>Eliminar</td>
                                    </tr>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)){
 // ----------cambio formato de fecha del registro----------                    
            $separar =  explode('/',$row['fechaIni']);

            $dia = $separar[0];
            $mes = $separar[1];
            $anio = $separar[2];
            $dia_semana = diaSemana($anio,$mes,$dia);
            $nombre_dia = nombre_dia ($dia_semana);
                                        
                                     echo "                                       
                                        <tr>
                                            <td align='center'>".$row['fechaIni']."</td>
                                            <td align='center'>".$nombre_dia."</td>
                                            <td align='center'>".$row['horaIni']."</td>
                                            <td align='center'>".$row['horaFin']."</td>
                                            <td align='center'>".$row['tipo']."</td>
                                            <td>".utf8_encode($row['motivo'])."</td>
                                            <td align='center'>".$row['minutosDiarios']."</td>  
                                            <td align='center'><a href='reporte_permisos_empleados.php?idPermisos=".$row['idPermisos'].
                "' onclick='return confirm('¿Seguro que desea BORRAR el elemento ?\n\rNo se puede deshacer')' >
  <img src='imagen/deletepermiso.gif' alt='¡PRECAUCIÓN! BORRAR' width='20' height='20' border='0'  /></a></td>
                                        </tr>";
                                    }
                                     ?>
                                </table>
                            <?php
                                }else{
                                  echo "<p align='center'><b>El empleado no tienen permisos capturados.</b></p>";
                                }          
                              }   
                            }                         
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

            $("#wrapper").toggleClass("toggled");
    </script>
    
</body>
</html>