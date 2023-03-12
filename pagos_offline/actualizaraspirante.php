<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Escuela Preparatoria Dos - UADY</title>
<link href="../adds/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../adds/lib.js">;</script>
<script language="javascript">
	function botonPresionado(iNum){
		if(iNum == 1){
			document.frmImprimir.submit();
		}else{
			location.href='aspirantes.php';
		}
		
		
	}
</script>

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
if(!empty($_GET['Folio']) && !empty($_GET['Folio'])>0){
$verifica = mysql_query("Select Folio,convert(cast(convert(Nombres using latin1) as binary) using utf8) AS Nombres,convert(cast(convert(ApePat using latin1) as binary) using utf8) AS ApePat,convert(cast(convert(ApeMat using latin1) as binary) using utf8) AS ApeMat,Curso,Direccion,Correo,Tel1,Tel2,tipo,movimiento from aspirantes where Folio = '".$_GET['Folio']."'",$link);
$t=mysql_fetch_array($verifica);
$nombre=$t['Nombres'];
$apPaterno=$t['ApePat'];
$apMaterno=$t['ApeMat'];
$Direccion=$t['Direccion'];
$Curso=$t['Curso'];
$Correo=$t['Correo'];
$Tel1=$t['Tel1'];
$Folio=$t['Folio'];
$movimiento=$t['movimiento'];
}
?>
<table width="731" align="center" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" id="tabla">
  <!--Se incluye la cabecera -->
  <tr> 
      <?php //include_once ("../adds/cabecera.php")?> 
  </tr>
  <!--Termina la cabecera -->

  <!--Se incluye la Fecha -->
  <tr> 
    <td bgcolor="#666666" valign="middle" align="right" height="20"> <font color="#FFFFFF"><strong>Hoy es:</strong>
	 <?php //include("../adds/lib.php"); echo fecha();?> 
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
                      <td align="center"><p class="Estilo15"> ACTUALIZAR ASPIRANTE</p>
	        </td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
		  <tr><td>
		  </td></tr>
		     <tr>
              <td align="center">			 
			  <form action="Actualiza.php?Folio=<?php echo $Folio;?>" name="alta" method="post">
				<table width="100%" border="0">				  
			    <tr>
			      <td width="18%" align="center"><div align="left"><span class="Estilo13">Curso :</span></div></td><td width="29%">
			      <select name="Curso">
			        <option value="2"<?PHP if ($Curso == 2) {echo " selected";} ?>>2&ordm;</option>
			        <option value="3"<?PHP if ($Curso == 3) {echo " selected";} ?>>3&ordm;</option>
	                </select>
			      <span class="Estilo16"> *</span><span class="Estilo13"></td>
		        <td width="13%">
				      <span class="Estilo13">Nombre(s):</span></td>
			      <td width="40%">				  <span class="Estilo14">
		          <input name="Nombres" type="text" size="35" maxlength="40" value="<?php echo $nombre?>">
			      <span class="Estilo16">*</span></span></td>
				</tr></table>
				<table width="100%" border="0">
				<tr><td width="18%"><span class="Estilo13">
				Apellido Paterno:
				</span></td>
				<td width="29%">
				<input name="ApePat" type="text" size="25" maxlength="25"  value="<?php echo $apPaterno?>">
				<span class="Estilo16">*				</span></td>
				<td width="20%"><span class="Estilo13">
				&nbsp;Apellido Materno:
				</span></td>
				<td width="33%">
				<input name="ApeMat" type="text" size="25" maxlength="25"  value="<?php echo $apMaterno?>">
				<span class="Estilo16"> *</span>				</td>
				</tr><tr><td><span class="Estilo13">
				Direcci&oacute;n: 
				</span></td>
				<td><input name="Direccion" type="text" size="25" maxlength="60" value="<?php echo $Direccion?>">
				<span class="Estilo16">*				</span></td><td><span class="Estilo13"> 
				&nbsp;Correo Electr&oacute;nico 
				</span></td>
				<td>
				<input name="Correo" type="text" size="30" maxlength="30" value="<?php echo $Correo?>"> <span class="Estilo16">*</span>				</td>
				</tr><tr><td><span class="Estilo13"> 
				Tel&eacute;fono de Casa: 
				</span></td>
				<td>				  <input name="Tel1" type="text" size="20" maxlength="10"  value="<?php echo $Tel1?>">
				  +</td><td><span class="Estilo13">
				&nbsp;Celular: 
				</span></td>
				<td>
<input name="Tel2" type="text" size="15" maxlength="10" value="<?php echo $Tel2?>">
+</td>
				</tr>
                <tr>
                  <td><span class="Estilo13"> 
				CONSEC: 
				</span></td>
				<td>				  <input name="movimiento" type="text" size="20" maxlength="10"  value="<?php echo $movimiento?>">
				  +</td>
				<td><span class="Estilo13"> &nbsp;Folio: </span></td>
				<td><input name="Folio" type="text" size="15" maxlength="10" value="<?php echo $Folio?>" disabled>
				  +</td>
				</tr>
				</table>
				<table width="100%" border="0">	
				<tr><td colspan="4"><div align="center">
                                            <p class="Estilo13"><strong>+ </strong>Los tel&eacute;fonos van sin guiones, par&eacute;ntesis o espacios en blanco; solamente se pone el n&uacute;mero</p>
				  <p class="Estilo13"><span class="Estilo16">*</span> Campos Obligatorios </p>
				</div>
				  </td></tr>
				<tr><td></td><td>
				<input type="hidden" name="Importe" value="1">
				</td><td><input type="hidden" name="NomEvento" value="Inscripcion">
                <input type="hidden" name="s_idioma" value="01">               
				</td><td>&nbsp;</td></tr>
				<tr><td colspan="4" align="center"><input type="submit" name="Submit" value="Enviar">
				  <input type="button" value="Terminar" onClick="botonPresionado(4)"/></td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></table>
			  </form>			  
			  </td>
            </tr>									
		</table>	  		  	
  <!--Termina �rea de contenido -->
  </tr>
</table>
     <!--Se incluye el Pie de la p�gina, de la carpeta adds -->
     <?php include_once("../adds/pie.php");?>
     <!--Termina Pie de P�gina -->
</body>
</html>
