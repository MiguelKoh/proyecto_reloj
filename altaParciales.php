
<?php

    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("fechaInicio,fechaFin,idCurso,idSemestre,idtipoexcepcion");
 
    if ($variables[1][2]) {$fechaInicio    = $variables[1][1];} else {$fechaInicio = "";}       
    if ($variables[2][2]) {$fechaFin  = $variables[2][1];} else {$fechaFin = "";} 
    if ($variables[3][2]) {$idCurso    = $variables[3][1];} else {$idCurso = 0;}  
    if ($variables[4][2]) {$idSemestre    = $variables[4][1];} else {$idSemestre = 0;} 
    if ($variables[5][2]) {$idtipoexcepcion    = $variables[5][1];} else {$idtipoexcepcion = 0;}     
    
    global $fechaInicio,$fechaFin;
  //  $Datos = Request("fechaInicio,fechaFin");	 
  //  $fechaInicio=$Datos[1][1];	
  //<  $fechaInicio=$Datos[2][1];    
    
    
    // proceso para grabar la informacion
    if(isset($_POST['grabarParciales'])){
        //validaciones
        //que la fecha final no sea menor a la de inicio
        if ($fechaInicio > $fechaFin){
            echo '<script>alert ("La fecha final no puede ser menor a la inicial")</script>';
        }else{                
                //preparo informacion

                //formateo la fecha para que quede como dd/mm/aaaa
                $separar = explode("-",$fechaInicio);
                $año = $separar[0];
                $mes = $separar[1];
                $dia = $separar[2];
                $nva_fechaInicio = $dia."/".$mes."/".$año;   

                $separar = explode("-",$fechaFin);
                $año = $separar[0];
                $mes = $separar[1];
                $dia = $separar[2];
                $nva_fechaFin = $dia."/".$mes."/".$año;                 
                
                //obtengo descripcio de tipo_excepcion
                $sente = "SELECT descripcion FROM tipo_excepciones where idtipoexcepcion = " . $idtipoexcepcion;
                $result = mysql_query($sente,$cn);
                if ($row = mysql_fetch_array($result)){
                    $desc_excepcion = $row['descripcion'];
                }
                
                //obtengo descripcion del curso
                $sente = "SELECT descripcion FROM curso_escolar where idcurso = " . $idCurso;
                $result = mysql_query($sente,$cn);
                if ($row = mysql_fetch_array($result)){
                    $desc_curso = $row['descripcion'];
                }
                
                //obtengo descripcion del semestre
                $sente = "SELECT descripcion FROM semestre where idsemestre = " . $idSemestre;
                $result = mysql_query($sente,$cn);
                if ($row = mysql_fetch_array($result)){
                    $desc_semestre = $row['descripcion'];
                }
                
                $descripcion = $desc_excepcion . " curso " . $desc_curso . " " . $desc_semestre;
                
                //grabo informacion de permisos en tabla
                $sente = "INSERT INTO parciales (descripcion,fecha_ini,fecha_fin,idcurso,semestre,idtipoexcepcion) ".
                            "VALUES ('".$descripcion."','".$nva_fechaInicio."','".$nva_fechaFin.
                            "',".$idCurso.",".$idSemestre.",".$idtipoexcepcion.")";            
                $result = mysql_query($sente, $cn) ;                        
                
                //echo $sente;
                echo '<script>alert ("El periodo: '.$descripcion.' se grabo correctamente")</script>';

                $fechaFin = "";
                $fechaInicio = "";
                $idCurso = "";
                $idSemestre = "";
                $idtipoexcepcion = "";

                echo "<script>location.href='OpParciales.php'</script>";
                
                
            
        }
    }
      
  
    
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Control de asistencias 1.0</title> 
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
    <body style="text-align: center">

        <table style="text-align:center;background-color: white" width="100%" border="0">
            <tr>
                <td colspan="3">
                    <img alt="Preparatoria Dos - UADY" src="imagen/logo2.jpg" >

                </td>
            </tr>
            <tr>
                <td align="center">
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmPermisos" id="frmPermisos" method="post">
                            <tr>
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3>Alta de periodo de parciales, ordinarios y revisiones</h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='OpParciales.php'><img src='imagen/regresar2.jpg' alt='Menu parciales ordinarios y revisiones' width='50' height='50' border='0' /></a>
                                    <a href='Menu.php'><img src='imagen/home.png' alt='Menu principal' width='50' height='50' border='0' /></a>
                                </td>      
                            </tr>   
                            <tr>
                                <td  colspan ="4">
                                    <p>
                                        Aquellos empleados cuya categoria sea Profesor o se encuentren en el departamento Profesores,
                                        quedaran justificadas sus salidas antes de horario, en caso de encontrarse dentro de la lista de 
                                        profesores que deben cuidar parciales, asi como su falta, por no ser requerido para cuidar examenes.
                                    </p>
                                    <p>
                                         Los periodos de revision justifican las faltas de los profesores, sin verificar entrada ni salida.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                                <td>Fecha inicio:</td>
                                <td>
                                    <input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, frmPermisos.fechaInicio, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>">                            	                                       
                                </td>                              
                            </tr>
                            <tr>
                                <td>Fecha fin:</td>
                                <td>
                                    <input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, frmPermisos.fechaFin, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaFin);?>">                                                                          
                                </td>
                            </tr>
                            <tr>
                                <td>Curso escolar: </td>
                                <td>
                                    <select name="idCurso" id="idCurso" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idCurso == 0) {echo " selected";}?>>Seleccione un curso</option>
                                      <?php 
                                           $SQLp = "SELECT idcurso,descripcion FROM curso_escolar";
                                           $queryA = mysql_query($SQLp,$cn);
                                      ?>
                                      <?php 
                                          while( $rsA = mysql_fetch_array($queryA) ) { 
                                              if ($idCurso == $rsA["idcurso"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idcurso"]; ?>"<?php echo $selected;?>><?php echo $rsA["descripcion"];?></option>
                                      
                                      <?php } mysql_free_result($queryA);?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Semestre:</td>
                                <td><label>
                                       <?php 
                                            
                                              $SQL = "SELECT idsemestre, descripcion from semestre WHERE idcurso = ".$idCurso." ORDER BY idsemestre";
                                              $query = mysql_query($SQL,$cn);
                                              $frows = mysql_num_rows($query);
                                              if ($frows > 0) {
                                      ?>
                                    <select name="idSemestre" id="idSemestre" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idSemestre == 0) {echo " selected";}?>>Seleccione un semestre</option>
                                      <?php while ($rs = mysql_fetch_array($query)) {
                                                   if ($idSemestre == $rs["idsemestre"]) {$selected = " selected";} else {$selected = "";} ?>
                                      <option value="<?PHP echo $rs["idsemestre"]; ?>"<?php echo $selected;?>>
                                          <?php 
                                            echo $rs['descripcion'];
                                            $idemp = $rs['idsemestre'];      
                                          ?>
                                      </option>
                                      <?php } mysql_free_result($query);?>
                                    </select>
                                  <?php } else { ?>
                                 <p> N/A</p>  
                                  <?php } ?>
                                </td>                                
                            </tr> 
                            <tr>
                                <td>Tipo excepcion: </td>
                                <td>
                                    <select name="idtipoexcepcion" id="idtipoexcepcion" onChange="javascript:submit()">
                                      <option value="0"<?php if ($idtipoexcepcion == 0) {echo " selected";}?>>Seleccione un tipo excepcion</option>
                                      <?php 
                                           $SQLp = "SELECT idtipoexcepcion,descripcion FROM tipo_excepciones";
                                           $queryA = mysql_query($SQLp,$cn);
                                      ?>
                                      <?php 
                                          while( $rsA = mysql_fetch_array($queryA) ) { 
                                              if ($idtipoexcepcion == $rsA["idtipoexcepcion"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idtipoexcepcion"]; ?>"<?php echo $selected;?>><?php echo $rsA["descripcion"];?></option>
                                      
                                      <?php } mysql_free_result($queryA);?>
                                    </select>
                                </td>
                            </tr>                            
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="grabarParciales" id="grabarPermisos" value="GRABAR"></td>
                                </td>
                            </tr>
                                
                        </form>
                    </table>                
                </td>            
            </tr>
        </table>
    </body>
</html>