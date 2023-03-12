<?php

    session_start(); // Use session variable on this page. This function must put on the top of page.
    include('conex.php'); 
    $cn = ConectaBD();
    
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"];    
    
    error_reporting (E_ALL ^ E_NOTICE);
    
    $sente = "select distinct e.idemp, e.nombre, r.departamento, t.descripcion as tipoEmp from retardos_temp as r inner join empleado as e " .
              "on r.idemp = e.idemp inner join tipoEmpleado as t on e.idtipo=t.idtipo ".
              "where e.estatus is null";
    $result = mysql_query($sente, $cn) ;
    
    function esPar($numero){ 
       $resto = $numero%2; 
       if (($resto==0) && ($numero!=0)) { 
            return true; 
       }else{ 
            return false; 
       }  
    }    

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<style type="text/css" media="print">
.hide{display:none}

</style>
<script type="text/javascript">
function printpage() {
document.getElementById('printButton').style.visibility="hidden";
window.print();
document.getElementById('printButton').style.visibility="visible";  
}
</script>
<style type="text/css">
.style1 {font-size: 8px}
H1.SaltoDePagina {PAGE-BREAK-AFTER: always}
</style>

</head>

<body>
        
<form action="menu.php" method="post" id="Menu" >     
<!--<input name="print" type="button" value="Imprimir" id="printButton" onClick="printpage()">
<input name="regresar" type="submit" value="Regresar" id="regresarButton" onClick=""> -->

<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
  <!--  <td align="center" valign="top">-->
  <?php
    $veces = 0;   
    
    while ($row = mysql_fetch_array($result)) {
        
        $veces ++;
        
        $se_imprime = esPar($veces);       
            
        $clave  = $row['idemp'];
        $nombre = $row['nombre'];
        
        $departamento = $row['departamento'];
        $tipoEmp = $row['tipoEmp'];
        
        $encabezado = $departamento . " - " . $clave . " - " . $nombre  ;    
  ?>        
    <table width="595"  cellspacing="0" cellpadding="0" id="bordertable"  border="1">
      <tr>           
          <td align="center"><strong>Universidad Aut&oacute;noma de Yucat&aacute;n <br /> 
                  Escuela Preparatoria 2 <br/>
                  Reporte de retardos <br/>
                    </strong>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 13px">                
            <tr>            
                <td colspan="2">
                <?php
                 
                    $clave = $row['idemp'];
                    $nombre =  $row['nombre'];
                    $departamento = $row['departamento'];
                    $encabezado = $departamento . " - " . $clave . " - " . $nombre  ;                

                    echo $encabezado;
                ?> 
                </td>
            </tr>
            <tr>
                <td valign="top">Periodo:                 
                     <?php
                        echo $fechaini . " al " . $fechafin;
                    ?> <br/>
                 </td>
                <td align="left">Tipo empleado: 
                    <?php
                        echo $tipoEmp;
                    ?>
                </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 13px">
          <tr>
            <?php
            
                echo "<td width='20%' bgcolor='#CCCCCC' align='center'><strong>Fecha</strong></td>";
                echo "<td width='20%' bgcolor='#CCCCCC' align='center' ><strong>Horario</strong></td>";
                echo "<td width='12%' bgcolor='#CCCCCC' align='center'><strong>Entrada</strong></td>";
                echo "<td width='13%' bgcolor='#CCCCCC' align='center'><strong>Salida</strong></td>";
                echo "<td width='12%' bgcolor='#CCCCCC' align='center'><strong>Min. Permiso</strong></td>";
                echo "<td width='15%' bgcolor='#CCCCCC' align='center'><strong>Ausente</strong></td>";           
                echo "<td width='15%' bgcolor='#CCCCCC' align='right'><strong>Min. Desc</strong></td>";                           
            ?>
          </tr>              
            <?php     
                $sente1 = "select fecha, horario, checadaini1, checadafin1, ausente, mindescuento, totminsdesc, " .
                    "minPermiso from retardos_temp " .
                     "where idemp=" . $clave;
                $result1 = mysql_query($sente1, $cn) ;
                 
                $tot_min_descuento = 0;
                $salto_linea = 7; // # lineas del encabezado y titulaes
                
                while ($row1 = mysql_fetch_array($result1)) {
                    ?>
           <tr>
                   <?php  

                    $horario = $row1['horario'];
                    $fecha = $row1['fecha'];
                    $checadaIni1 = $row1['checadaini1'];
                    $checadaFin1 = $row1['checadafin1'];
                    $permiso = $row1['minPermiso'];
                    $ausente = $row1['ausente'];
                    $minDescuento = $row1['mindescuento'];
                    $tot_min_descuento += $minDescuento;

                    echo "<td width='20%' align='center'>". $fecha ."</td>";
                    echo "<td width='20%' align='center'>" . $horario . "</td>";
                    echo "<td width='12%' align='center'>" . $checadaIni1 . "</td>";
                    echo "<td width='13%' align='center'>" . $checadaFin1 . "</td>";
                    echo "<td width='12%' align='center'>" . $permiso . "</td>";
                    echo "<td width='15%' align='center'>" . $ausente . "</td>";
                    echo "<td width='15%' align='right'>" . $minDescuento . "</td>";
                    echo "<td width='15%' align='right'>&nbsp;</td>";
                    
                    $salto_linea ++; // # lineas del encabezado y titulaes
                 ?>
           </tr>
              <?php
                }  
              ?>     
         </table>
        </td>
      </tr>
      <tr>
        <td>
            <table align="right" width="45%" border="0" cellspacing="0" cellpadding="0" style="font-size: 13px">
              <tr>  
                  <td align='right'>Total de minutos por descontar: &nbsp; </td>
                  <td align='right'>&nbsp;</td>
                 <?php
                    echo "<td align='right'>" . $tot_min_descuento . "</td>";     
                 ?> 
                  <td>&nbsp;</p></td> 
              </tr>            
            </table> 
        </td>
      </tr>        
      <tr>
        <td>Firma del empleado&nbsp;</p>&nbsp;</td>    
      </tr>
    </table>
    <tr>
          <td>&nbsp;</td>
    </tr>      
    <?php
        $salto_linea += 5;
        
        while ($salto_linea < 22) {
            echo "<tr><td></br></td></tr>";
            $salto_linea ++;
        }        
        if ($se_imprime == TRUE){
    ?>
     <tr>
         <td>
             <H1 class="SaltoDePagina"> </H1>
         </td>
     </tr>
    <?php
        }
    }
    ?>    
        
 <!--  </td>-->
  </tr>
</table>

</form> 
</body>
</html>
