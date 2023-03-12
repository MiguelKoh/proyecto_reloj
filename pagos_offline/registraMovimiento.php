<?php
include("conex.php");
//include("ImprimeFichaRap.php");
$link=Conectarse();
$Movimiento=$_GET['movimiento'];
$Folio=$_GET['folio'];
$Sigue = 0;
$bandera = 0;

if ($Movimiento == '' || $Folio == '' )
  {
  		$bandera = 1;
	    echo "<script language='JavaScript'>
        alert('Est\u00e1s dejando vac\u00edo alguno de los campos obligatorios');
        </script>";  
		echo("<script language='JavaScript' type='text/javascript'>");
		echo("location.href='resumen.php?variable1=".$Folio."'");
		echo("</script>");  
}else{ // pregunto si alguien tiene ese servicio
	$verificaExiste = mysql_query("select * from aspirantes where movimiento='$Movimiento'",$link);
	$rowExiste = mysql_fetch_array($verificaExiste);
	if ($rowExiste){
		$bandera = 1;
	    echo "<script language='JavaScript'>
        alert('Ya existe el No. de Servicio: " . $Movimiento . "');
        </script>";  
		echo("<script language='JavaScript' type='text/javascript'>");
		echo("location.href='resumen.php?variable1=".$Folio."'");
		echo("</script>");  
	}
	
}

if ($Sigue == 0 and $bandera == 0)
	{
		try{
			mysql_query("update aspirantes set movimiento='$Movimiento' where Folio='$Folio'",$link);
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			mysql_close($link);
			echo "<script>alert('No se pudo completar el registro. Int\u00e9ntalo nuevamente.')</script>";
			echo "<script>location.href='../admision/inscrip2010.php'</script>";
		}
		mysql_close($link);
		//include("ImprimeFichaRap.php");
		//crear1_pdf($Nombres,$ApePat,$ApeMat,$ElFolio);


		//echo "<script language='JavaScript'>
		//    alert('Te has inscrito exitosamente, tu n�mero de folio es: ".$ElFolio."');
		/*</script>";*/

		echo ("<script language='JavaScript' type='text/javascript'>
       		location.href='resumen.php?variable1=".$Folio."'</script>");


		//echo("location.href='Archivos/FichaDePago".$ElFolio.".pdf'");
		//echo"abre('Archivos/FichaDePago".$ElFolio.".pdf')>");
		//echo("newwindow=window.open('Archivos/FichaDePago".$ElFolio.".pdf','','width=315,height=160')");
		//echo("newwindow.creator=self");
		//echo("location.href='../eventos/inscrip2010.php'"); 


}
	
?>