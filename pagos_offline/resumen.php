<?php
	include("conex.php");
    $fechaSistema=date("d/m/Y");
	$fechaSistema2=date("Ymd");
	$link=Conectarse();

	$Folio = mysql_real_escape_string($_REQUEST["variable1"],$link);
	//$Folio = $_REQUEST['variable1'];

	$Nombres = "";
	$APaterno = "";
	$AMaterno = "";
	$NomComp = "";				

	$res = mysql_query("Select * from aspirantes where Folio = " . $Folio ,$link);
	//echo $res;
	if ($row = mysql_fetch_array($res)){
		$Nombres = $row['Nombres'];
		$APaterno = $row['ApePat'];
		$AMaterno = $row['ApeMat'];
		$Mov = $row['movimiento'];
		$CursoI = $row['Curso'];
		$NomComp = $Nombres . " " . $APaterno . " " . $AMaterno;
		/*echo "<script>alert('" . $NomComp . "');</script>";*/
	}				
?>
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
			location.href='index.php';
		}
		
		
	}
</script>
<SCRIPT LANGUAGE="Javascript">
function ventanamarco(){
window.open('ejemplo.png','ventana','height=100,wid th=420');
}
</SCRIPT>

<style type="text/css">
<!--
body {
	background-image: url(../imagenes/fondo.gif);
}
.Estilo5 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style></head>

<table width="731" align="center" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" id="tabla">
  <!--Se incluye la cabecera -->
  <tr> 
      <?php include_once ("../adds/cabecera.php")?>
  </tr>
  <!--Termina la cabecera -->

  <!--Se incluye la Fecha -->
  <tr> 
    <td bgcolor="#666666" valign="middle" align="right" height="20"> <font color="#FFFFFF">&nbsp;<strong>Hoy es:</strong>
	 <?php include("../adds/lib.php"); echo fecha();?>
      </font></td>
  </tr>
  <!--Termina la Fecha -->
</table>
<table width="731" align="center" bgcolor="#FFFFFF" id="tabla" border="0">
  <tr>
   <!--Se incluye el menu lateral,de la carpeta ../adds -->
      
   </table></td>
   <!--Fin Menu Lateral -->
   
   <!--Comienza area de Contenido -->
    <td valign="top"><b><font size="5" color="#0000FF"></font></b><b><font size="5" color="#0000FF">
      </font></b>
          <table width = "731" border="0" align="center" bgcolor="#FFFFFF">
            <tr>              
              <td><div align="center" class="Estilo5">
                <p><strong> PROCESO DE SELECCION DE NUEVO INGRESO AL SEGUNDO O TERCER CURSO</strong></p>
                <p><strong> CICLO ESCOLAR 2015-2016 </strong></p>
              </div></td>
              <td><div align="center"></div></td>
            </tr>
			<tr><td>&nbsp;</td></tr>
            <tr>
            	<td colspan="2">
                Te has inscrito exitosamente. <br/><br/>Tu n&uacute;mero de folio es: <strong><?php echo $Folio; ?></strong> <br/>
                    Curso a ingresar: <strong><?php echo $CursoI; ?> año </strong> </td>
            </tr>
                        <tr>
            	<td colspan="2">
			Folio registrado a nombre de: <strong>
			<?php 
				echo $NomComp;				
			   
			?> </strong> </td>
             </tr>
                           <?php //if(!empty($Mov)){?>
            <tr>
            	<td colspan="2">
			<?php
			if (!empty($Mov)){
			?>
			No. CONSEC registrado es: <strong>
			<?php 
				echo $Mov;			      
			}
			?>                </strong></td>
             </tr> 
             <?php// }?>
      <tr>
            	<td colspan="2">
            <?php if(empty($Mov) && ($fechaSistema2>='20150414' && $fechaSistema2 <= '20150522')){ ?>*  			
			IMPRIME TU FICHA DE PAGO 
             <?php }?>        </td>
             </tr>
		  <?php
		  
		  if(empty($Mov) && ($fechaSistema2>='20150414' && $fechaSistema2 <= '20150522')){?>
        <tr>
      
		<form action="ImprimeFichaRap.php" name="frmImprimir" id="frmImprimir" method="get">
			<td>
                <input type="hidden" id="txtNom" name="txtNom" value="<?php echo $NomComp; ?>" />
                <input type="hidden" id="txtFolio" name="txtFolio" value="<?php echo $Folio; ?>" />
                <input type="button" value="Imprimir Ficha de Pago" onClick="botonPresionado(1)"/>
            </td>
		</form>
        </tr>
        <?php }?>
			<tr><td>&nbsp;</td></tr>
			<tr>
            	<td colspan="2">
					<?php if(empty($Mov) && ($fechaSistema2>='20150414' && $fechaSistema2 <= '20150522')){?>
					* DESPUÉS DE PAGAR, INGRESA NUEVAMENTE PARA AGREGAR TU NÚMERO DE PAGO BANCARIO <a href="imagenFicha.php" target="_blank" onClick="window.open(this.href, this.target, 'width=225,height=349'); return false;">ver ejemplo</a><br>
					 <?php }
						/*  if(empty($Mov) && ($fechaSistema2>='20150525' && $fechaSistema2 <= '20150528')){
							echo "<br><strong> *** No registraste el folio de pago en el periodo establecido</strong>, por lo que no podr&aacute;s imprimir tu Pase de Ingreso.<br>";
						  }*/
					 ?>
					 
					 <?php if(!empty($Mov) && ($fechaSistema2>='20150414' && $fechaSistema2 <= '20150522')){?> 
					Puedes imprimir tu <strong>Pase de Ingreso</strong> del 25 al 28 de mayo de 2015, ingresando con tu <strong>FOLIO</strong><?php }?>
					<?php 							
					if(!empty($Mov)){
						if ($fechaSistema2<'20150525'){
							//echo "La fecha de impresión de pases de ingreso estará disponible del 25 al 28 de mayo del 2015";
						}
						if ($fechaSistema2>'20150528'){
							echo "De acuerdo a la convocatoria la fecha de impresión de pases de ingreso ha concluido";
						}
					 }?>
				</td>
            </tr>
			<tr><td>&nbsp;</td></tr>
           <?php if(empty($Mov) && ($fechaSistema2>='20150414' && $fechaSistema2 <= '20150528')){?>
            <tr>
				<form action="registraMovimiento.php" name="frmMovimiento" id="frmMovimiento" method="get">
					<td colspan="2">
						<strong>No. CONSEC:</strong>
						<input name="movimiento" type="text" size="10" maxlength="7">
						<input type="hidden" id="folio" name="folio" value="<?php echo $Folio; ?>" />
						<input type="submit" name="Submit" value="Registrar">                
					</td>
				</form> 
            </tr>
        <?php }?>
        <?php if(!empty($Mov)){?>		
			<tr>
				<form action="ImprimePaseRap.php" name="frmImprimir" id="frmImprimir" method="get">
					<td>
						<input type="hidden" id="txtNom" name="txtNom" value="<?php echo $NomComp; ?>" />
						<input type="hidden" id="txtFolio" name="txtFolio" value="<?php echo $Folio; ?>" />
						<input type="hidden" id="txtMov" name="txtMov" value="<?php echo $Mov; ?>" />
						<?php 							
							if(!empty($Mov) && ($fechaSistema2>='20150522' && $fechaSistema2 <= '20150528')){?>
							
							<input type="button" value="Imprimir Pase de Ingreso" onClick="botonPresionado(1)"/>
						<?php }?>
						<input type="button" value="Terminar" onClick="botonPresionado(2)"/>            
					</td>
				</form>
			</tr>
		<?php }?>
		<?php if(empty($Mov)){?>
			<tr>      
				<form action="" name="frmImprimir1" id="frmImprimir1" method="get">
					<td>                         
						<input type="button" value="Terminar" onClick="botonPresionado(4)"/>       	  
					</td>
				</form>
			</tr>
        <?php }?>
      </table>
  </tr>
</table>
     <!--Se incluye el Pie de la pï¿½gina, de la carpeta ../adds -->
     <?php include_once("../adds/pie.php");?>
     <!--Termina Pie de Pï¿½gina -->
</body>
</html>
