<?php

    session_start(); // Use session variable on this page. This function must put on the top of page.
    include('conex.php'); 
    include('funciones_reloj.php');
    
    $cn = ConectaBD();
    
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"];    
    
    error_reporting (E_ALL ^ E_NOTICE);
    
    $sente = "select distinct e.idemp, e.nombre, r.departamento, t.descripcion as tipoEmp from retardos_temp as r inner join empleado as e " .
              "on r.idemp = e.idemp inner join tipoEmpleado as t on e.idtipo=t.idtipo ".
              "where (e.estatus is null || e.estatus ='') and (e.practicante is null || e.practicante='') order by t.idtipo,e.nombre";
    $result = mysqli_query($cn,$sente) ;
    

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html";  charset="utf-8" />

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
    
    while ($row = mysqli_fetch_array($result)) {
        
        $veces ++;
        
        $se_imprime = esPar($veces);       
            
        $clave  = $row['idemp'];
        $nombre = $row['nombre'];
        
        $departamento = $row['departamento'];
        $tipoEmp = $row['tipoEmp'];
        
        $encabezado = $departamento . " - " . $clave . " - " . $nombre  ;    
        
        //primer regisro
        if ( $veces == 0){
         //   echo "<tr><td></p></td></tr>";
        }
  ?>        
    <table width="595"  cellspacing="0" cellpadding="0" id="bordertable"  border="1">
      <tr>           
          <td align="center"><strong>Escuela Preparatoria Dos - UADY <br /> 
                  Reporte de Retardos <br/>
                    </strong>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 13px">                
            <tr>            
                <td colspan="3" height="20px">
                <?php
                 
                    $clave = $row['idemp'];
                    $nombre =  utf8_encode($row['nombre']);
                    $departamento = utf8_encode($row['departamento']);
                    $encabezado = " [" . $clave  . "] ".$nombre ." [" . $departamento."]" ;                

                    echo $encabezado;
                ?> 
                </td>
            </tr>
            <tr>
                <td valign="top"><b>Periodo:</b>                 
                     <?php
                        echo $fechaini . " <b>al</b> " . $fechafin;
                    ?> <br/>
                 </td>
                <td align="left"><b>Tipo Empleado:</b> 
                    <?php
                        echo utf8_encode($tipoEmp);
                    ?>
                </td>
                <td align="left"><b>Impreso:</b> 
                    <?php
                        echo date(d."/".m."/".Y);
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
            
       //iam29Ago17--  echo "<td width='20%' bgcolor='#CCCCCC' align='center'><strong>Fecha</strong></td>";
       //          echo "<td width='20%' bgcolor='#CCCCCC' align='center' ><strong>Horario</strong></td>";
       //          echo "<td width='12%' bgcolor='#CCCCCC' align='center'><strong>Entrada</strong></td>";
       //          echo "<td width='13%' bgcolor='#CCCCCC' align='center'><strong>Salida</strong></td>";
       //          echo "<td width='12%' bgcolor='#CCCCCC' align='center'><strong>H:m. Permiso</strong></td>";
       //          echo "<td width='15%' bgcolor='#CCCCCC' align='center'><strong>Ausente</strong></td>";           
       //          echo "<td width='15%' bgcolor='#CCCCCC' align='right'><strong>H:m. Desc</strong></td>"; 
			    // echo "<td width='15%' bgcolor='#CCCCCC' align='right'><strong>Obs.</strong></td>";

            echo "<td width='15%' bgcolor='#CCCCCC' align='center'><strong>Fecha</strong></td>";
            echo "<td width='15%' bgcolor='#CCCCCC' align='center' ><strong>Horario</strong></td>";
            echo "<td width='10%' bgcolor='#CCCCCC' align='center'><strong>Entrada</strong></td>";
            echo "<td width='5%' bgcolor='#CCCCCC' align='center'><strong>Salida</strong></td>";
            echo "<td width='18%' bgcolor='#CCCCCC' align='center'><strong>Permiso</strong></td>";
            echo "<td width='5%' bgcolor='#CCCCCC' align='center'><strong>Ausente</strong></td>";           
            echo "<td width='12%' bgcolor='#CCCCCC' align='center'><strong>Descuento</strong></td>"; 
            echo "<td width='20%' bgcolor='#CCCCCC' align='center'><strong>Obs.</strong></td>";
            ?>
          </tr>              
            <?php     
                $sente1 = "select fecha, horario, checadaini1, checadafin1, ausente, mindescuento, totminsdesc, " .
                    "minPermiso, observaciones from retardos_temp " .
                     "where  minDescuento>0 AND idemp=" . $clave ;
                $result1 = mysqli_query($cn,$sente1) ;
                 
                $tot_min_descuento = 0;
                $salto_linea = 7; // # lineas del encabezado y titulaes
                
                while ($row1 = mysqli_fetch_array($result1)) {
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
					         $observaciones = $row1['observaciones'];                  
                    
                    //Pasa minutos a formato hh:mm
                    
                    $permiso_formato = convierte_mins_a_horas($permiso,"");
                    $minDescuento_formato = convierte_mins_a_horas($minDescuento,"");
                    $tot_min_descuento_formato = convierte_mins_a_horas($tot_min_descuento,"N");
                    //se agrega para cambiar formato de fecha 
                
                    list($dia, $mes, $año) = explode('/', cambiafnormal($fecha)); 
                    $fechaMuestra=$dia."-".mes($mes)."-".$año; 
                 

                    echo "<td width='17%'>".saber_dia($año."-".$mes."-".$dia).", ". $fechaMuestra ."</td>";
                    echo "<td width='15%' align='center'>" . $horario . "</td>";
                    echo "<td width='10%' align='center'>" . $checadaIni1 . "</td>";
                    echo "<td width='5%' align='center'>" . $checadaFin1 . "</td>";
                    echo "<td width='18%' align='center'>" . $permiso_formato . "</td>";
                    echo "<td width='5%' align='center'>" . $ausente . "</td>";
                    echo "<td width='12%' align='center'>" . $minDescuento_formato . "</td>";
                    echo "<td width='20%' align='center'>" . $observaciones . "</td>";
                    echo "<td width='5%' align='center'>&nbsp;</td>";                    
                    
                    $salto_linea ++; // # lineas del encabezado y titulaes
                 ?>
           </tr>
              <?php
                }
                while ($salto_linea < 23){
                    echo "<tr><td></br></td></tr>";
                    $salto_linea ++;                    
                }
              ?>     
         </table>
        </td>
      </tr>
      <tr>
        <td>
            <table align="right" width="45%" border="0" cellspacing="0" cellpadding="0" style="font-size: 13px">
              <tr>  
                  <td align='right'><b>Total de Horas a Descontar: &nbsp;</b> </td>
                  <td align='right'>&nbsp;</td>
                 <?php
                    
                 
                    echo "<td align='right'>" . $tot_min_descuento_formato . "</td>";     
                 ?> 
                  <td>&nbsp;</br></td> 
              </tr>            
            </table> 
        </td>
      </tr>        
      <tr>
        <td>Firma del Empleado&nbsp;</br></br>&nbsp;</td>    
      </tr>
        <?php
            if ($veces == 2){
               echo "<tr><td></br></td></tr>";
            }        
        ?>
    </table>
    <tr>
          <td>&nbsp;</br></td>
    </tr>      
    <?php
        
       /*
        $salto_linea += 4; 
        while ($salto_linea < 22) {
            echo "<tr><td></br></td></tr>";
            $salto_linea ++;
        }  
        * 
        */      
        if ($se_imprime == TRUE){
    ?>
      
     <tr>
         <td><H1 class="SaltoDePagina"> </H1></td> 
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
