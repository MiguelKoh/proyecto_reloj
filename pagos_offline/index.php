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
$fechaSistema2=date("Ymd");
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
                      <td align="center"><p class="Estilo15"> FORMULARIO DE PREINSCRIPCI&Oacute;N DE NUEVO INGRESO A 2&ordm; Y 3&ordm; </p>
	        </td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
                  <tr align="right"><td><a href='login.php'>Administraci&oacute;n</a></td></tr>
		  <tr><td><form action="Abre.php" name="Abre" method="post"><table width="100%" border="0">
                                  <tr><td><div align="center"><p>Si ya cuentas con un n&uacute;mero de Folio ponlo aqui:
                &nbsp;&nbsp;<input name="MiFolio" type="text" checked="checked" onKeyPress='if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;' size="10" maxlength="6">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 <input type="submit" name="Submit2" value="Enviar"></p>
		  </div></td></tr></table>

		  </form>
		  </td></tr>
		     <tr>
              <td align="center">			 
			  <form action="Inscribe.php" name="alta" method="post">
				<table width="100%" border="0">	 
				  <tr><td colspan="4"><div align="center">
                                              <p>Si a&uacute;n no cuentas con un n&uacute;mero de Folio llena los siguientes datos para obtenerlo:</p>
				  	<p>(Recuerda anotarlo en caso de requerirlo para futuras consultas) </p>
					</div></td></tr>
			    <tr><td width="15%">&nbsp;</td>
			    <td width="31%">&nbsp;</td>
			    <td width="19%">&nbsp;</td>
			    <td width="35%">&nbsp;</td>
			    </tr><tr><td>
				</table><table width="100%" border="0">				  
			    <tr>
			      <td width="18%" align="center"><div align="left"><span class="Estilo13">Curso :</span></div></td><td width="29%">
			      <select name="Curso">
			        <option value="2" selected="selected">2&ordm;</option>
			        <option value="3">3&ordm;</option>
	                </select>
			      <span class="Estilo16"> *</span><span class="Estilo13"></td>
		        <td width="13%">
				      <span class="Estilo13">Nombre(s):</span></td>
			      <td width="40%">				  <span class="Estilo14">
		          <input name="Nombres" type="text" size="35" maxlength="40">
			      <span class="Estilo16">*</span></span></td>
				</tr></table>
				<table width="100%" border="0">
				<tr><td width="18%"><span class="Estilo13">
				Apellido Paterno:
				</span></td>
				<td width="29%">
				<input name="ApePat" type="text" size="25" maxlength="25">
				<span class="Estilo16">*				</span></td>
				<td width="20%"><span class="Estilo13">
				&nbsp;Apellido Materno:
				</span></td>
				<td width="33%">
				<input name="ApeMat" type="text" size="25" maxlength="25">
				<span class="Estilo16"> *</span>				</td>
				</tr><tr><td><span class="Estilo13">
				Direcci&oacute;n: 
				</span></td>
				<td><input name="Direccion" type="text" size="25" maxlength="60">
				<span class="Estilo16">*				</span></td><td><span class="Estilo13"> 
				&nbsp;Correo Electr&oacute;nico 
				</span></td>
				<td>
				<input name="Correo" type="text" size="40" maxlength="40"> <span class="Estilo16">*</span>				</td>
				</tr><tr><td><span class="Estilo13"> 
				Tel&eacute;fono de Casa: 
				</span></td>
				<td>				  <input name="Tel1" type="text" size="20" maxlength="10" onKeyPress='if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;'>
				  +</td><td><span class="Estilo13">
				&nbsp;Celular: 
				</span></td>
				<td>
<input name="Tel2" type="text" size="15" maxlength="10" onKeyPress='if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;'>
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
				</td><td><input type="hidden" name="NomEvento" value="Inscripcion"><input type="hidden" name="s_idioma" value="01">
				</td><td>&nbsp;</td></tr>
				<?php if ($fechaSistema2>= '20150414' && $fechaSistema2 <= '20150522'){ ?>
				<tr><td colspan="4" align="center"><input type="submit" name="Submit" value="Enviar"></td>
				</tr>
				<?php }
				?>
				<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
				</table>				
				<?php
				 if ($fechaSistema2< '20150414'){
					echo "El periodo de inscripci&oacute;n estar&aacute; disponible del 15 de abril al 22 de mayo de 2015";
				 }
				 
				 if ($fechaSistema2> '20150522'){
					echo "El periodo de inscripci&oacute;n ya se cerr&oacute;. </br></br><strong>Tienes hasta el 28 de mayo de 2015 para ingresar el folio de tu ficha de pago e imprimir tu pase de ingreso.</strong>";
				 }				
				?>				 			
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
