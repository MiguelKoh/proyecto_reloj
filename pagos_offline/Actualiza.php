<?php
include("conex.php");
//include("ImprimeFichaRap.php");
$link=Conectarse();
$Nombres=mysql_real_escape_string($_POST['Nombres'],$link);
$ApePat=mysql_real_escape_string($_POST['ApePat'],$link);
$ApeMat=mysql_real_escape_string($_POST['ApeMat'],$link);
$Direccion=mysql_real_escape_string($_POST['Direccion'],$link);
$Correo=mysql_real_escape_string($_POST['Correo'],$link);
$Tel1=mysql_real_escape_string($_POST['Tel1'],$link);
$Tel2=mysql_real_escape_string($_POST['Tel2'],$link);
$movimiento=mysql_real_escape_string($_POST['movimiento'],$link);
$Folio=mysql_real_escape_string($_GET['Folio'],$link);
$Curso=$_POST['Curso'];
if($Folio>0){
	$SQL="UPDATE aspirantes SET Nombres='".$Nombres."',ApePat='".$ApePat."',ApeMat='".$ApeMat."',Curso='".$Curso."',Direccion='".$Direccion."',Correo='".$Correo."',Tel1='".$Tel1."',Tel2='".$Tel2."',movimiento='".$movimiento."' WHERE Folio='".$Folio."'";
	$query=mysql_query($SQL);
	echo ("<script language='JavaScript' type='text/javascript'>
       		location.href='aspirantes.php?variable1=".$Folio."&variable2=1'</script>");
	
}


	
?>