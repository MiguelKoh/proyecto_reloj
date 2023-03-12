<?PHP include("validate.php"); ?>
<?PHP include_once("../connections/conn.php"); ?>
<?PHP include_once("../includes/Funciones.php");?>
<?php
//$idProfesor=4;
global $hgrupo,$hprofesor,$hmateria,$IDhorario,$IDcarga,$IDciclo,$IDprofesor,$descarga,$selec1,$IDperiodo,$nb,$img,$s;

$dias=array(1=>"Lunes","Martes","Miercoles","Jueves","Viernes","Sábado","Domingo");
$horarios=array(1=>"7:00 - 7:40","7:40 - 8:20","8:30 - 9:10","9:10 - 9:50","10:10 - 10:50","10:50 - 11:30","11:40 - 12:20","12:20 - 13:00","14:00 - 14:40","14:40 - 15:20","15:30 - 16:10","16:10 - 16:50","17:10 - 17:50","17:50 - 18:30","18:40 - 19:20","19:20 - 20:00",);
$ndias=5; //numero de dias en la semana (lunes a...)
$bg = "#F4F6F7";
$variables=Request("idAula,idMateria,anio");
if ($variables[1][2]) {$idAula = $variables[1][1];} else {$idAula = 0;}  
if ($variables[2][2]) {$idMateria = $variables[2][1];} else {$idMateria = "";}
if ($variables[3][2]) {$anio    = $variables[3][1];} else {$anio = 0;}  

?>
<html><!-- InstanceBegin template="/Templates/Printable.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script language="javascript" type="text/javascript">
function imprimir(elemento) {
  document.getElementById(elemento).style.display="none";
  print();
  window.close();
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>SISCAP2::LISTAS PROFESORES</title>
<!-- InstanceEndEditable -->

<!-- InstanceBeginEditable name="head" -->
<link href="../css/estilos.css" rel="stylesheet" type="text/css">
<!-- InstanceEndEditable -->
<link href="../css/estilos_print.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="620" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="bottom" ><table width="110%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="../images/lineas.gif" width="789" height="45"></td>
            </tr>
            <tr>
              <td height="50" align="center" valign="bottom"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><!-- InstanceBeginEditable name="Titulo" -->
				  <span class="Estilo1">
				  </span>
				  
				  
				  <!-- InstanceEndEditable --></td>
                </tr>
              </table></td>
            </tr>
        </table></td>
        <td width="215"><img src="../images/membrete.jpg" width="237" height="116"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
	<!-- InstanceBeginEditable name="Contenido" -->


  <br>
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

  <br/>
   
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border-collapse:collapse;">
  <tr>
    <td height="26" bgcolor="#CED7E0" class="LetraGris12BoldBlack"><strong>Semestre</strong>: <?php echo $Semestre; ?></td>
    <td align="right" bgcolor="#CED7E0" class="LetraGris12BoldBlack"><strong>Profesor</strong>: <?php echo $Profesor; ?> </td>
  </tr>
  <tr>
    <td height="27" bgcolor="#CED7E0" class="LetraGris12BoldBlack"><strong>Asignatura</strong>: <?php echo $Materia; ?> </td>
    <td align="right" bgcolor="#CED7E0" class="LetraGris12BoldBlack">Sección: <?php echo $salones; ?></td>
  </tr>
</table>
<br>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border-collapse:collapse;">
  <tr>
    <td width="27" height="20" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="126" bgcolor="#FFFFFF" class="LetraGris12BoldBlack"><CENTER>MATRICULA</CENTER></td>    
    <td width="415" bgcolor="#FFFFFF" class="LetraGris12BoldBlack"><strong>
      <center>NOMBRE</center></strong></td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="19" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="28" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
<?php
$counter=0;
 while ($rs = mysql_fetch_array($queryAlumnos)) { 
$counter = $counter + 1;
?>
  <tr>
    <td height="21" bgcolor="#FFFFFF" class="LetraGris12BoldBlack"><?php echo $counter; ?></td>
	<td bgcolor="#FFFFFF" class="LetraGris12BoldBlack"><?php 
	if($rs['matricula']!="")
	echo $rs['matricula'];
	else
	echo $rs['folioCeneval'];?>&nbsp;</td> 
    <td bgcolor="#FFFFFF" class="LetraGris12BoldBlack"><?php echo $rs['apPaterno']." ".$rs['apMaterno']." ".$rs['nombres']; ?></td>
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
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    
  </tr>
<?php } ?>  
</table>
<br/>

<?PHP include("botones.php"); ?>	
	<!-- InstanceEndEditable --></td>
  </tr>
</table>
</body>
<!-- InstanceEnd --></html>
