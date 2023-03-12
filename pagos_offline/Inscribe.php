<?php
include("conex.php");
//include("ImprimeFichaRap.php");
$link=Conectarse();
$Nombres=strtoupper(mysql_real_escape_string($_POST['Nombres'],$link));
$ApePat=strtoupper(mysql_real_escape_string($_POST['ApePat'],$link));
$ApeMat=strtoupper(mysql_real_escape_string($_POST['ApeMat'],$link));
$Direccion=strtoupper(mysql_real_escape_string($_POST['Direccion'],$link));
$Correo=mysql_real_escape_string($_POST['Correo'],$link);
$Tel1=mysql_real_escape_string($_POST['Tel1'],$link);
$Tel2=mysql_real_escape_string($_POST['Tel2'],$link);
$Importe=mysql_real_escape_string($_POST['Importe'],$link);
$NomEvento=mysql_real_escape_string($_POST['NomEvento'],$link);
$TipoPago='0';
$movimiento='';
//$s_desc=$_POST['NomEvento'];
//$s_idioma=$_POST['s_idioma'];
$Curso=$_POST['Curso'];
$Sigue = 0;
$bandera = 0;

if ($Nombres == '' || $ApePat == '' || $ApeMat == '' || $Direccion == '' || $Correo == '')
  {
  		$bandera = 1;
	    echo "<script language='JavaScript'>
        alert('Est\u00e1s dejando vac\u00edo alguno de los campos obligatorios');
        </script>";  
		echo("<script language='JavaScript' type='text/javascript'>");
		echo("location.href='index.php'");
		echo("</script>");  
	}

    $mail_correcto = 0; 
    //compruebo unas cosas primeras 
    if ((strlen($Correo) >= 6) && (substr_count($Correo,"@") == 1) && (substr($Correo,0,1) != "@") && (substr($Correo,strlen($Correo)-1,1) != "@")){ 
       if ((!strstr($Correo,"'")) && (!strstr($Correo,"\"")) && (!strstr($Correo,"\\")) && (!strstr($Correo,"\$")) && (!strstr($Correo," "))) { 
          //miro si tiene caracter . 
          if (substr_count($Correo,".")>= 1){ 
             //obtengo la terminacion del dominio 
             $term_dom = substr(strrchr ($Correo, '.'),1); 
             //compruebo que la terminaci�n del dominio sea correcta 
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
                //compruebo que lo de antes del dominio sea correcto 
                $antes_dom = substr($Correo,0,strlen($Correo) - strlen($term_dom) - 1); 
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
                if ($caracter_ult != "@" && $caracter_ult != "."){ 
                   $mail_correcto = 1; 
                } 
             } 
          } 
       } 
    } 
    if ($mail_correcto == 0) 
	  {
        $bandera = 1; 
		echo "<script language='JavaScript'>
        alert('El correo electrónico que estás proporcionando no tiene un formato válido');
        </script>";  
		echo("<script language='JavaScript' type='text/javascript'>");
		echo("location.href='index.php'");
		echo("</script>");  
	  }	



//echo "Select Folio from aspirantes where Nombres = '".$Nombres."' and ApePat = '".$ApePat."' and ApeMat = '".$ApeMat."' ";
$verifica = mysql_query("Select Folio from aspirantes where Nombres = '".$Nombres."' and ApePat = '".$ApePat."' and ApeMat = '".$ApeMat."' ",$link);

//if ($row = mysql_fetch_array($verifica))
$row = mysql_fetch_array($verifica);
if ($row)    
{ 
    $Sigue = 1;
    $ElFolio = $row['Folio'];
    echo "<script language='JavaScript'>
    alert('Ya cuentas con un número de Folio: " . $ElFolio . "');
    </script>";
	
    echo ("<script language='JavaScript' type='text/javascript'>
    location.href='resumen.php?variable1=".$ElFolio."'</script>");

	
}
//echo $Sigue;
$bOK = true; 
mysql_query("BEGIN"); 
try{
	$valido = mysql_query("SELECT Folio FROM aspirantes order by Folio desc limit 1",$link);
}catch(Exception $e){
	$bOK = false;
	mysql_query("ROLLBACK");
	mysql_close($link);
	echo "<script>alert('Ocurrió un error desconocido.Inténtalo nuevamente.')</script>";
	echo "<script>location.href='../admision/inscrip2010.php'</script>";
} 
if($bOK ){

	//if ($row1 = mysql_fetch_array($valido))
       $row1 = mysql_fetch_array($valido); 
       if ($row1)
	{
		$ElFolio = (int)$row1['Folio'] + 1;
	}
	else 
	{
		$ElFolio = 1000;
	}

	if ($Sigue == 0 and $bandera == 0)
	{
		try{
			mysql_query("insert into aspirantes(Folio,Nombres,ApePat,ApeMat,Curso,Direccion,Correo,Tel1,Tel2,tipo,movimiento) values('$ElFolio','$Nombres','$ApePat','$ApeMat','$Curso','$Direccion','$Correo','$Tel1','$Tel2','$TipoPago','$movimiento')",$link);
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			mysql_close($link);
			echo "<script>alert('No se pudo completar el registro. Inténtalo nuevamente.')</script>";
			echo "<script>location.href='../admision/inscrip2010.php'</script>";
		}
		mysql_close($link);
		//include("ImprimeFichaRap.php");
		//crear1_pdf($Nombres,$ApePat,$ApeMat,$ElFolio);


		//echo "<script language='JavaScript'>
		//    alert('Te has inscrito exitosamente, tu n�mero de folio es: ".$ElFolio."');
		/*</script>";*/

		echo ("<script language='JavaScript' type='text/javascript'>
       		location.href='resumen.php?variable1=".$ElFolio."'</script>");


		//echo("location.href='Archivos/FichaDePago".$ElFolio.".pdf'");
		//echo"abre('Archivos/FichaDePago".$ElFolio.".pdf')>");
		//echo("newwindow=window.open('Archivos/FichaDePago".$ElFolio.".pdf','','width=315,height=160')");
		//echo("newwindow.creator=self");
		//echo("location.href='../eventos/inscrip2010.php'"); 

	}
}
	
?>