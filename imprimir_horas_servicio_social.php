<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

include('conex.php');
include('funciones_reloj.php');

//$cn = ConectaBD();
$idDepto = $_SESSION["iddepto"];
$idEmp = $_SESSION["idemp"];
$nomEmp = $_SESSION["nomemp"];
$nomDepto = $_SESSION["nomdepto"];

$fechaini = $_SESSION["fechaini"];
$fechafin = $_SESSION["fechafin"];

//unicamente se reinicia el contador cuando el valor de $reinicio_contador=1
$id_periodo = $_SESSION["id_periodo"];
if ($id_periodo==-1){       
        $fechaini=$_SESSION["fechaI"];
        $fechafin=$_SESSION["fechaF"];        
}
//echo $id_periodo."ssss".$_SESSION["fechaI"];
function regChecadas() {

    $cn = ConectaBD();
    $idEmp = $_SESSION["idemp"];
    $nomEmp = $_SESSION["nomemp"];
    $nomDepto = $_SESSION["nomdepto"];
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"];
    
    $id_periodo = $_SESSION["id_periodo"];
    
    $numero_empleados = 0; // empleados seleccionados. 
    
   
       
    if ($id_periodo==-1){
        $fechaI = $_SESSION["fechaI"];
        $fechaF = $_SESSION["fechaF"];
        $fechaini1=$fechaI;
        $fechafin1=$fechaF;        
    } 
    else {
         //cambio posicion de fecha
       $fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
       $fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;  

    }
    //obtener registro de checadas de empleados de servicio social
        if ($nomEmp <> "TODOS"){
            // empleado especifico          
                $sente = "select distinct a.idemp, a.idperiodo, e.nombre, e.idtipo, e.practicante, ".
                            "d.nombre as departamento, a.fecha, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Entrada') as entrada, ".
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Salida') as salida ".
                            "from checadas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                            "where a.idemp = '" . $idEmp .
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .  
                            "' and e.practicante = 'S' ".
                            " order by a.idemp, DATE( STR_TO_DATE(a.fecha, '%d/%m/%Y' ) )";
            
        } else {            
            // todos los empleados   
                $sente = "select distinct a.idemp, a.idperiodo, e.nombre, e.idtipo, e.practicante, ".
                            "d.nombre as departamento, a.fecha, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Entrada') as entrada, ".
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Salida') as salida ".
                            "from checadas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                            "where STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .           
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .  
                            "' and e.practicante = 'S' ".                        
                            " order by a.idemp, DATE( STR_TO_DATE(a.fecha, '%d/%m/%Y' ) )";            
        }
    
   // echo $sente;
    
    $result = mysql_query($sente, $cn) ;

    $linea = 0;
    $Num = 1;
    $idempant = "";
    $nvo = "";
    $registros_x_empleado = 0;
    $clase = ' class="FilaImpar"';
    $tot_hrs_trabajadas = 0;
    $tot_min_trabajados = 0;
    $filas=0; 
    $hojas=1;
    $cfilas=36;
    while ($row = mysql_fetch_array($result)) {          

            //-------------------------------------------------------------------------

            if ($linea == 0) {
                $clase = ' class="FilaPar"';
                $linea = 1;
            } else {
                $clase = ' class="FilaImpar"';
                $linea = 0;
            }


            if ($row['departamento'] == $nomDepto) {
                $marca = " checked ";
            } else {
                $marca = "";
            }


            //determino si ya es nuevo empleado o no
            if ($idempant == "") {
                $idempant = $row['idemp'];
                $nombreant = $row['nombre'];
                $departamentoant = $row['departamento'];
                $nvo = "SI";

                $registros_x_empleado = 0;
                $tot_hrs_trabajadas = 0;
                $tot_min_trabajados = 0;
                                

            } else {
                if ($idempant != $row['idemp']) {     
                    
                    $idempant = $row['idemp'];
                    $nombreant = $row['nombre'];
                    $departamentoant = $row['departamento'];
                    $nvo = "SI";        
                    
                    $tot_hrs_trabajadas = convierte_mins_a_horas($tot_min_trabajados,"NO");
    
                    echo '<tr align="right"  ' . $clase . '>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                                        $tot_hrs_trabajadas . '</b></td>

                    </tr>';

                    echo '</table><table width="595" cellspacing="0" cellpadding="0" id="bordertable"  border="0" align="center" bgcolor="#FFFFFF">';
                    
                    

                    
                }else{
                    $nvo = "";
                }
            }

            if ($nvo == "SI") {

                //obtengo el tipo de empleado
                $sente5 = "SELECT idTipo FROM empleado where idEmp =". $idempant;
                $result5 = mysql_query($sente5, $cn) ;    
                $row5 = mysql_fetch_array($result5);
                $tipo_empleado = $row5['idTipo'];

                $sente6 = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
                $result6 = mysql_query($sente6, $cn) ;    
                $row6 = mysql_fetch_array($result6);
                $tipo_empleado_desc = $row6['Descripcion'];   

                //echo '<td>&nbsp&nbsp;</td>';            
                echo '<table width="595" border="0" cellspacing="0" cellpadding="0" id="bordertable">';
                echo '<tr align "left"><td align="left"><b>' . 
                          $departamentoant . " - " . $idempant . " - " .  $nombreant . "</b></font><br>";
                echo '<b>Fecha inicial: </b>'.$fechaini1.'&nbsp;&nbsp;&nbsp;&nbsp; <b>Fecha final: </b>'.$fechafin1.'</td>';
                echo '</table>';

                echo '<table width="595" border="0">';
                echo '<tr align="right"' . $clase . '>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Fecha registro</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora entrada</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora salida</b></td>                            
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Horas trabajadas</b></td> 
                      </tr>';                         
                $registros_x_empleado = 0;
                $numero_empleados += 1;  

                $tot_hrs_trabajadas = 0;
                $tot_min_trabajados = 0;                
                               
            }        
           
            //obtengo horas trabajadas por dia
            $seg_trabajados = strtotime($row['salida']) - strtotime($row['entrada']);
            $min_trabajados = $seg_trabajados / 60;
            $hrs_trabajadas = convierte_mins_a_horas($min_trabajados,"");
            
            $tot_min_trabajados += $min_trabajados;
            $filas++;            
            if ($filas > $hojas*$cfilas ){
              $hojas++;
              $cfilas=39;
              //echo $hojas;
              for ($i=0;$i<5;$i++){
              echo '<tr align="right"' . $clase . ' >
                  <td align="center" style="font-size:14px;">&nbsp;</td>
                  <td align="center" style="font-size:14px;">&nbsp;</td>
                  <td align="center" style="font-size:14px;">&nbsp;</td>
                  <td align="center" style="font-size:14px;">&nbsp;</td>   
                  </tr>'                 
                    ;
              }
            }

                echo '<tr align="right"' . $clase . ' >
                            <td align="center" style="font-size:14px;">' . $row['fecha'] . '</td>
                            <td align="center" style="font-size:14px;">' . $row['entrada'] . '</td>
                            <td align="center" style="font-size:14px;">' . $row['salida'] . '</td>
                            <td align="center" style="font-size:14px;">' . $hrs_trabajadas . '</td>   
                </tr>';
                               
          
    //    }
    }//fin while
    
    $tot_hrs_trabajadas = convierte_mins_a_horas($tot_min_trabajados,"NO");
       
    echo '<tr align="right"' . $clase . ' border="1">
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td>                      
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td> 
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                 $tot_hrs_trabajadas . '</b></td>         
        </tr><tr><td><p></td></tr>';            
    
                     
     mysql_free_result($result);
     mysql_close($cn);
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Control de asistencias 1.0</title>
        <link href="../adds/estilo.css" rel="stylesheet" type="text/css" />

      
        <style type="text/css" media="print">
        .hide{display:none}

        </style>        
        <style type="text/css">
        .style1 {font-size: 8px}
        H1.SaltoDePagina {PAGE-BREAK-AFTER: always}
        </style>
    </head>
    <!--Comienza area de Contenido -->
    <body>
<!--728-->
       <!-- <table width="95%" border="0" align="center"> -->
     <table width="599"  cellspacing="0" cellpadding="0" id="bordertable"  border="1">
            <form action="" target="_blank" method="post" id="horas_trabajadas" >  

            
            <tr align ="center">
                
                <td align="center" colspan="4"><strong>Universidad Aut&oacute;noma de Yucat&aacute;n - Escuela Preparatoria 2 <br /> 
                        <br>Reporte de horas trabajadas de personal de Servicio Social
                </td>                
            </tr>  
     
     </table> 
      <table width="595"  cellspacing="0" cellpadding="0" id="bordertable"  border="1">        
            <tr>
                <td>
                    <?php
                        regChecadas();
                    ?>
                </td>
            </tr>            

            </form>
       </table>

    </body>
</html>