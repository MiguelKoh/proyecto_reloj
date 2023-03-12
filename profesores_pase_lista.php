<?php  require_once("validate.php"); ?>
<?php include_once("../connections/conn.php"); ?>
<?PHP include_once("../includes/Funciones.php"); ?>
<?php
//$idProfesor=4;
$dias=array(1=>"lunes","martes","miercoles","jueves","viernes");
$variables=Request("idAsignatura,anio");
if ($variables[1][2])  {$idAsignatura = $variables[1][1];} else {$idAsignatura = "";} 
if ($variables[2][2])  {$anio = $variables[2][1];} else {$anio = "0";} 
?>
<html><!-- InstanceBegin template="/Templates/siscap2Profesor.dwt" codeOutsideHTMLIsLocked="false" -->
<link href="../css/estilos.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../js/ajax.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<script language="Javascript">
function myOpen(sURL) {
    window.open(sURL,"_blank","scrollbars=yes, width=690,height=600,left=1,top=1");
	}
function myOpen2(sURL) {
    window.open(sURL,"_self");
	}
function openw(URL){
	window.open(URL,1,"width=690,height=600,top=30,left=30,scrollbars=yes,resizable=0,status=1");
}
</script>
<title>SISCAP</title>
<!-- InstanceEndEditable -->
<link href="../css/estilos_iframe.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.TituloMenuPrincipal {
	
	font-weight: bold;
	font-size: 12px;
}
.SubMenuOpcion {
	font-size: 11px;
	color: #333;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->   
</head>
 <body>
 <table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="120"><?php include("../banner.php");?><?php include("../infoProfesores.php");?></td>
  </tr>
  <tr>
    <td height="4" align="left"></td>
  </tr>
  <tr>
    <td valign="top"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="191" valign="top" background="../newbody_images/left_fondo.jpg"><?php include("../menuvProfesores.php");?> </td>
        <td valign="top" bgcolor="#99CCCD"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="13" height="8"><img src="../newbody_images/content_top_left.jpg" width="13" height="8"></td>
            <td height="8" background="../newbody_images/conent_top_large.jpg"><img src="../newbody_images/conent_top_large.jpg" width="2" height="8"></td>
          </tr>
          <tr>
            <td width="13" background="../newbody_images/conent_left_large.jpg"><img src="../newbody_images/conent_left_large.jpg" width="13" height="2"></td>
            <td valign="top" bgcolor="#D0E3E7">
             <!-- InstanceBeginEditable name="contenidoPrincipal" -->
		<p class="TituloPrincipal">Listas De Profesores </p>
		
		<table width="14%" height="33" border="0" cellpadding="3" cellspacing="1">
          <tr>
            <td width="27%"><input type="button" name="Submit2" value="Listas Asignaturas" onClick="javascript:myOpen2('profesores_pase_lista.php')" class="FondoBoton"></td>
         
            <td width="27%"><input type="button" name="Submit2" value="Listas Deportivo" onClick="javascript:myOpen2('profesores_pase_lista_deportivo.php')" class="FondoBoton"></td>
          </tr>
        </table>
		<hr>		
		<form name="form1" method="post" action="">
		<input name="anio" type="hidden"  value="<?PHP echo $anio; ?>"/>
		<input name="idAsignatura" type="hidden"  value="<?PHP echo $idAsignatura; ?>"/>
		  <table width="100%" border="0" cellspacing="1" cellpadding="2">
            <tr>
              <td><strong>AÑO:</strong></td>
              <td>
                  <select name="anio" id="select" onChange="javascript:submit()">
                    <option value="0"<?php if ($anio == 0) {echo " selected";}?>>Seleccione</option>
					<option value="1"<?php if ($anio == 1) {echo " selected";}?>>PRIMERO</option>
					<option value="2"<?php if ($anio == 2) {echo " selected";}?>>SEGUNDO</option>
					<option value="3"<?php if ($anio == 3) {echo " selected";}?>>TERCERO</option>
                  </select></td>
            </tr>
            <tr>
              <td width="13%"><strong>ASIGNATURAS:</strong></td>
              <td width="87%"><?php 
$SQL = "SELECT distinct(au.numAula),m.nombre,a.idAsignatura,idAula FROM cargas_academicas c 
INNER JOIN profesores p ON p.idProfesor=c.idProfesor
INNER JOIN asignaturas a ON c.idAsignatura=a.idAsignatura
INNER JOIN materias m ON m.idMateria=a.idMateria
INNER JOIN horarios h ON h.idCargaAcademica=c.idCarga
INNER JOIN aulas au ON au.idAula=h.idAulaCarga
WHERE a.idGrado='".$anio."' AND p.idProfesor='".$idProfesor."' AND c.idPeriodo=3";
//echo $SQL;
$query = mysql_query($SQL);
$frows = mysql_num_rows($query);
if ($frows > 0) {
?>
                  <select name="idAsignatura" id="select2" onChange="javascript:submit()">
                    <option value="0"<?php if ($idAsignatura == 0) {echo " selected";}?>>Seleccione una Asignatura</option>
                    <?PHP while ($rs = mysql_fetch_array($query)) {
if ($idAsignatura == $rs["idAsignatura"]) {$selected = " selected";} else {$selected = "";} ?>
                    <option value="<?PHP echo $rs["idAula"]."-".$rs["idAsignatura"]; ?>"<?PHP echo $selected;?>><?PHP echo $rs['nombre']." --- ".$rs['numAula']; ?></option>
                    <?PHP } 
mysql_free_result($query);
?>
                  </select>
                  <?php } else { ?>
                N/A
                <?php } ?>
              </td>
            </tr>
          </table>
</form>
<?php if ($idAsignatura >0 && $anio>0) { 

list($idAula,$idMateria) = explode( "-",$idAsignatura);
?>
El listado siguiente son los alumnos inscritos en en esta sección <br>
<?php 
$Semestre= $anio;
$SQLM="select nombre from asignaturas a INNER JOIN materias m ON m.idMateria=a.idMateria
WHERE a.idAsignatura='".$idMateria."'";
$queryM=mysql_query($SQLM);
$mate=mysql_fetch_array($queryM);
$Materia=$mate['nombre'];

$SQLAlumnos="select a.apPaterno,a.apMaterno,a.nombres,a.matricula,a.folioCeneval FROM alumnos a
INNER JOIN alumno_cicloescolar ac ON a.idAlumno=ac.idAlumno
WHERE idPeriodo=3 AND ac.idAula='".$idAula."' order by a.apPaterno,a.apMaterno,a.nombres asc";
$queryAlumnos=mysql_query($SQLAlumnos);

$SQLP = "SELECT * FROM profesores WHERE idProfesor = '".$idProfesor."'";
$queryP = mysql_query($SQLP);
$frows = mysql_fetch_array($queryP);
$Profesor=	$frows['tituloAbrev']." ".$frows['apellidos']." ".$frows['nombres'];

$SQLA = "SELECT * FROM aulas WHERE idAula = '".$idAula."'";
$queryA = mysql_query($SQLA);
$frowsA = mysql_fetch_array($queryA);
$salones=	$frowsA['numAula'];

	
?>
<hr size="1">
<table width="448" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="121">&nbsp;</td>
    <td width="141"><a href="reporte_anios_excel.php?anio=<?php echo$anio?>&idMateria=<?php echo $idMateria?>&idAula=<?php echo $idAula?>"><img src="../imagenes/excel.gif" width="50" height="50" border="0"></a></td>
    <td width="99">&nbsp;</td>
    <td width="87"><input type="button" name="Submit" value="Imprimir" onClick="javascript:openw('pase_lista_impresion.php?anio=<?php echo$anio?>&idMateria=<?php echo $idMateria?>&idAula=<?php echo $idAula?>')">&nbsp;</td>
  </tr>
</table>

<p class="Titulo">LISTA DE ASISTENCIA</p>
<table width="800" border="0" cellpadding="2" cellspacing="1" bgcolor="#9AB1CB">
  <tr>
    <td bgcolor="#CED7E0"><strong>Semestre</strong>: <?php echo $Semestre; ?></td>
    <td align="right" bgcolor="#CED7E0"><strong>Profesor</strong>: <?php echo $Profesor; ?> </td>
  </tr>
  <tr>
    <td bgcolor="#CED7E0"><strong>Asignatura</strong>: <?php echo $Materia; ?> </td>
    <td align="right" bgcolor="#CED7E0"><strong>Salon</strong>: <?php echo $salones; ?></td>
  </tr>
</table>
<br>
<table width="800" border="0" cellpadding="1" cellspacing="1" bgcolor="#333333">
  <tr>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF"><strong>Matricula</strong></td>    
    <td bgcolor="#FFFFFF"><strong>Nombre</strong></td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="15" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
<?php
$counter=0;
 while ($rs = mysql_fetch_array($queryAlumnos)) { 
$counter = $counter + 1;
?>
  <tr>
    <td bgcolor="#FFFFFF"><?php echo $counter; ?></td>
	<td bgcolor="#FFFFFF"><?php 
	if($rs['matricula']!="")
	echo $rs['matricula'];
	else
	echo $rs['folioCeneval'];?>&nbsp;</td> 
    <td bgcolor="#FFFFFF"><?php echo $rs['apPaterno']." ".$rs['apMaterno']." ".$rs['nombres']; ?></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
<?php } ?>
  <tr>
    <td height="55" colspan="19" valign="top" bgcolor="#FFFFFF">Observaciones:</td>
    </tr>
</table>


<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
      </td>
  </tr>
</table>
<?php
} else {
?>
<p>SELECCIONE UNA ASIGNATURA</p>
<?php
} // END DEL NUM RPWS 
 // END DLE IF showresults
?>

<!-- InstanceEndEditable --></td></tr>
        </table></td>
      </tr>
      <tr>
        <td height="9" valign="top" bgcolor="#99CCCD"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="9" background="../newbody_images/bottom_left_large.jpg"><img src="../newbody_images/bottom_left_large.jpg" width="2" height="9"></td>
            <td width="16" height="9"><img src="../newbody_images/bottom_left_corner.jpg" width="16" height="9"></td>
          </tr>
        </table></td>
        <td valign="top" bgcolor="#99CCCD"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="13" height="8"><img src="../newbody_images/conent_bottom_corner.jpg" width="13" height="8"></td>
            <td height="8" background="../newbody_images/conent_bottom_large.jpg"><img src="../newbody_images/conent_bottom_large.jpg" width="2" height="8"></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#99CCCD"><?php include("../footer.php");?></td>
  </tr>
</table>
    </body>
<!-- InstanceEnd --></html>
