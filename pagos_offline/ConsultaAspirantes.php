<?php
include("conex.php");
$link=Conectarse();
$MiFolio= mysql_real_escape_string($_POST['MiFolio'],$link);
$ConsultaT= mysql_real_escape_string($_POST['searchby'],$link);
$bandera=0;
if ($MiFolio == '' || $ConsultaT == '')
  {
  		$bandera = 1;
	    echo "<script language='JavaScript'>
        alert('Tienes que poner un n�mero de Folio o nombres/apellidos');
        </script>";  
		echo("<script language='JavaScript' type='text/javascript'>");
		echo("location.href='aspirantes.php'");
		echo("</script>");  
	}
else
{
  if($ConsultaT==1)   
   $verifica = mysql_query("Select * from aspirantes where Folio = '".$MiFolio."'",$link);
  if($ConsultaT==2)
  $verifica = mysql_query("Select * from aspirantes where ApePat = '".$MiFolio."'",$link);
  
  
  if ($row = mysql_fetch_array($verifica))
    { 
echo("<script language='JavaScript' type='text/javascript'>");
echo("location.href='aspirantes.php?variable1=".$MiFolio."&variable2=".$ConsultaT."'");
//echo("location.href='Archivos/FichaDePago".$ElFolio.".pdf'");
//echo ("abre('Archivos/FichaDePago".$ElFolio.".pdf')>");
//echo("<a href='../eventos/inscrip2010.php'>");
//echo("newwindow=window.open('Archivos/FichaDePago".$ElFolio.".pdf','','width=315,height=160')");
//echo("newwindow.creator=self");
//echo("location.href='../eventos/inscrip2010.php'"); 
echo("</script>");	
    }
  else
    {      
	  echo "<script language='JavaScript'>
      alert('Folio inexistente');
      </script>";  
 	  echo("<script language='JavaScript' type='text/javascript'>");
	  echo("location.href='aspirantes.php'");
	  echo("</script>"); 	
	}
}


?>