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
                      <td align="center"><p class="Estilo15"> CONSULTA PREINSCRIPCI&Oacute;N DE NUEVO INGRESO A 2&ordm; Y 3&ordm; </p>
	        </td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
		  <tr><td><form action="ConsultaAspirantes.php" name="Abre" method="post"><table width="100%" border="0">
                                  <tr><td><div align="center">
                                 <table border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td class="LetraGris14BoldForm">Buscar:
        <input name="MiFolio" type="text" id="MiFolio">
        </td>
      <td class="LetraGris14BoldForm">por:
        <select name="searchby" size="1" id="searchby">              
              <option value="1">Folio</option>
              <option value="2">Apellido Paterno</option>              
            </select>
      </td>
      <td><label>
        <input type="submit" name="Submit" value="Buscar">
      </label></td>
    </tr>
  </table>
		  </div></td></tr></table>

		  </form>
		  </td></tr>
		     <tr>
              <td align="center">
<?php if(!empty($_GET['variable1']) && !empty($_GET['variable2'])){
if($_GET['variable2']==1)   
	$verifica = mysql_query("Select Folio,convert(cast(convert(Nombres using latin1) as binary) using utf8) AS Nombres,convert(cast(convert(ApePat using latin1) as binary) using utf8) AS ApePat,convert(cast(convert(ApeMat using latin1) as binary) using utf8) AS ApeMat,Curso,Direccion,Correo,Tel1,Tel2,tipo,movimiento from aspirantes where Folio = '".$_GET['variable1']."'",$link);
  if($_GET['variable2']==2)
	$verifica = mysql_query("Select Folio,Nombres,ApePat,ApeMat,Curso,Direccion,Correo,Tel1,Tel2,tipo,movimiento from aspirantes where ApePat = '".$_GET['variable1']."'",$link);
  $rrt=mysql_num_rows($verifica);
  $i=0;
  if($rrt>0){
	?>			 
<table width="80%" border="0" cellpadding="1" cellspacing="1" bgcolor="#000066" style="font-size:14px">
  <tr>
    <td align="center"><p style="color:#FFF;font-weight:bold">No.</p></td>
    <td align="center"><p style="color:#FFF;font-weight:bold">Folio</p></td>
    <td align="center"><p style="color:#FFF;font-weight:bold">Aspirante</p></td>
    <td align="center"><p style="color:#FFF;font-weight:bold">Grado</p></td>
    <td align="center"><p style="color:#FFF;font-weight:bold">Pago</p></td>
    <td align="center"><p style="color:#FFF;font-weight:bold">Acciones</p></td>
  </tr>
  <?php while($t=mysql_fetch_array($verifica)){
	  $i++;
	  $NomCom=$t['ApePat']." ".$t['ApeMat']." ".$t['Nombres'];
	  ?>
  <tr>
    <td width="6%" bgcolor="#FFFFFF" align="center"><?php echo $i;?></td>
    <td width="8%" bgcolor="#FFFFFF" align="center"><?php echo $t['Folio'];?></td>
    <td width="77%" bgcolor="#FFFFFF"><?php echo strtoupper($NomCom);?></td>
    <td width="9%" bgcolor="#FFFFFF" align="center"><?php echo $t['Curso'];?></td>
    <td width="9%" bgcolor="#FFFFFF" align="center"><?php echo $t['movimiento'];?></td>
    <td width="9%" bgcolor="#FFFFFF" ><a href="actualizaraspirante.php?Folio=<?php echo $t['Folio'];?>"><img src="b_edit.png" width="19" height="19" border="0"></a>
   <?php  if($t['movimiento']>0){?>
    <a href="ImprimePaseRap.php?txtNom=<?php echo $NomCom;?>&txtFolio=<?php echo $t['Folio'];?>&txtMov=<?php echo $t['movimiento'];?>"><img src="hoja.png" width="19" height="19" border="0"></a>
    <?php }?></td>
  </tr>
  <?php }?>
</table>
	<img src="b_edit.png" width="19" height="19" border="0">Editar Información
	<img src="hoja.png" alt="" width="19" height="19" border="0">Imprimir Pase de Ingreso
	
	  <?php 
  }
  }?>
    </p></td>
            </tr>									
		</table>	  		  	
  <!--Termina ï¿½rea de contenido -->
  </tr>
</table>
     <!--Se incluye el Pie de la pï¿½gina, de la carpeta adds -->
     <?php include_once("../adds/pie.php");?>
     <!--Termina Pie de Pï¿½gina -->
</body>
</html>
