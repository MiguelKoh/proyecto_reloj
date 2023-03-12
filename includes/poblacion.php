<?PHP include("validate.php"); ?>
<?PHP include("../connections/conn.php"); ?>
<?PHP include("../includes/Funciones.php"); ?>
<?PHP
$data = Request("selEstado");
if ($data[1][2]) {
  $selEstado = $data[1][1];
} else {
  $selEstado = 0;
};
?>
<html>
<head >
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISCAP :: Seleccione Estado y Poblaci&oacute;n</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function seleccionar() {
  var index_estado = document.frmPoblacion.selEstado.options[document.frmPoblacion.selEstado.selectedIndex].value;
  var value_estado = document.frmPoblacion.selEstado.options[document.frmPoblacion.selEstado.selectedIndex].text;
  var index_ciudad = document.frmPoblacion.selCiudad.options[document.frmPoblacion.selCiudad.selectedIndex].value;
  var value_ciudad = document.frmPoblacion.selCiudad.options[document.frmPoblacion.selCiudad.selectedIndex].text;

    top.opener.document.pm.intEstado.value = index_estado;
    top.opener.document.pm.chrEstado.value = value_estado;
    top.opener.document.pm.intCiudad.value = index_ciudad;
    top.opener.document.pm.chrCiudad.value = value_ciudad;
    window.close();
}
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
body {
	background-image: url(../images/Fondo1.gif);
	background-repeat: repeat;
}
-->
</style></head>

<body>
<form name="frmPoblacion" method="post" action="">
<table border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td align="right"><strong>Estado:</strong></td>
    <td align="left">
      <select name="selEstado" id="selEstado" onChange="javascript:submit()">
        <option value="0">Seleccione</option>
<?PHP
$eSQL = "SELECT * FROM estados ORDER By IDestado";
$query = mysql_query($eSQL);
while ($rs = mysql_fetch_array($query)) {
  if (($selEstado <> 0) and ($selEstado == $rs['IDestado'])) {
    $label = " selected";
  } else {
    $label = "";;
  }
?>
        <option value="<?PHP echo $rs['IDestado']; ?>"<?PHP echo $label; ?>><?PHP echo $rs['nombre']; ?></option>
<?PHP } mysql_free_result($query); ?>		      
	  </select>    </td>
  </tr>
  <tr>
    <td align="right"><strong>Ciudad o Poblaci&oacute;n: </strong></td>
    <td align="left"><select name="selCiudad" id="selCiudad">
      <option value="0">Seleccione</option>
<?PHP
$eSQL = "SELECT * FROM localidad WHERE IDestado = ".$selEstado." ORDER By nombre";
$query = mysql_query($eSQL);
while ($rs = mysql_fetch_array($query)) {
?>
        <option value="<?PHP echo $rs['IDlocalidad']; ?>"><?PHP echo $rs['nombre']; ?></option>
<?PHP } mysql_free_result($query); ?>		  
    </select>    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="btnSeleccionar" type="button" onClick="MM_callJS('seleccionar()')" value="Seleccionar"></td>
    </tr>
</table> 
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" valign="top"><input type="button" onClick="window.close();" name="Submit2" value="Cancelar"></td>
  </tr>
</table>
<br>
</form>
</body>
</html>
