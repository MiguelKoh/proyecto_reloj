
<?PHP 

session_start();
include("conex.php"); 
include("funciones_reloj.php");    

global $mensajes, $fechaInicio,$fechaFin;
$Datos = Request("fechaInicio,fechaFin");	 
$fechaInicio=$Datos[1][1];	
$fechaInicio=$Datos[2][1];	

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/ajax.js"></script>
<title>Sistema de Control de Cargas Académicas de la Preparatoria 2 UADY</title>
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
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <form action="" method="post" name="form1" id="form1" enctype="multipart/form-data">
    <tr>
        <td><strong>FECHA DE PERMISO:</strong>
            <input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, form1.fechaInicio, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>">                            
            <input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, form1.fechaFin, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaFin);?>">                            
        </td>
    </tr>

 </form>
</table>

</body>
</html>
