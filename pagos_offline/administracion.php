<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Escuela Preparatoria Dos - UADY</title>
<link href="../adds/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../adds/lib.js">;</script>

<style type="text/css">
<!--
body {
	background-image: url(../imagenes/fondo.gif);
}
.Estilo13 {font-family: "Times New Roman", Times, serif; font-size: 16px; }
.Estilo14 {font-size: 16px}
.Estilo15 {
	font-size: 18px;
	font-weight: bold;
}
.Estilo16 {color: #FF0000}
-->
</style></head>

<?php
include("conex.php"); 
$link=Conectarse(); 
?>
<table width="731" align="center" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" id="tabla">
  <!--Se incluye la cabecera -->
  <tr> 
      <?php include_once ("../adds/cabecera.php")?> 
  </tr>
  <!--Termina la cabecera -->

  <!--Se incluye la Fecha -->
  <tr> 
    <td bgcolor="#666666" valign="middle" align="right" height="20"> <font color="#FFFFFF"><strong>Hoy es:</strong>
	 <?php include("../adds/lib.php"); echo fecha();?> 
      </font></td>
  </tr>
  <!--Termina la Fecha -->
</table>
<table width="731" align="center" bgcolor="#FFFFFF" id="tabla" border="0">
  <tr>
   <!--Se incluye el menu lateral,de la carpeta adds -->
   <!-- ?php include_once("../adds/menulateral.php");?> -->
   </table></td>
   <!--Fin Menu Lateral -->
   
   <!--Comienza area de Contenido -->
      <td width="728"valign="top">
          <table width="728" border="0" align="center" bgcolor="#FFFFFF">
		  <tr><td>&nbsp;</td></tr>
		  <tr>
                      <td align="center"><p class="Estilo15"> Men&uacute; de administraci&oacute;n </p>
	        </td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
                  <tr><td><a href='aspirantes.php'>B&uacute;squeda y actualizaci&oacute;n de aspirantes</a></td></tr>
                  <tr><td><a href='alumnos_registrados.php'>Listado de aspirantes</a></td></tr>								
		</table>	  		  	
  <!--Termina �rea de contenido -->
  </tr>
</table>
     <!--Se incluye el Pie de la p�gina, de la carpeta adds -->
     <?php include_once("../adds/pie.php");?>
     <!--Termina Pie de P�gina -->
</body>
</html>
