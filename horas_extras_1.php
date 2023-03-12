<?php

session_start();

include('conex.php');
include('funciones_reloj.php');

//$cn = ConectaBD();
$idDepto = $_SESSION["iddepto"];
$idEmp = $_SESSION["idemp"];
$nomEmp = $_SESSION["nomemp"];
$nomDepto = $_SESSION["nomdepto"];
$op = $_SESSION["op"];

$fechaini = $_SESSION["fechaini"];
$fechafin = $_SESSION["fechafin"];

$tipoRep = $op;
$nomRep = "Horas extras";

function regChecadas() {

    $cn = ConectaBD();
    $idDepto = $_SESSION["iddepto"];
    $idEmp = $_SESSION["idemp"];
    $nomEmp = $_SESSION["nomemp"];
    $nomDepto = $_SESSION["nomdepto"];
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"];
    $op = $_SESSION["op"];
    
    $id_periodo = $_SESSION["id_periodo"];
    
    $numero_empleados = 0; // empleados seleccionados.     
        
    
    //cambio posicion de fecha
    $fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    $fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;       
    
    //nombre del reporte
    $nomRep = "Horas extras";

    //obtener horario de empleados del departamento seleccionado
    if ($nomDepto <> "TODOS") { 
        if ($nomEmp <> "TODOS"){
            // depto y empleado especifico           
                $sente = "select distinct a.idemp, a.idperiodo, e.nombre, e.idtipo, ".
                            "d.nombre as departamento, a.fecha, h.descripcion as horario_teorico, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Entrada') as entrada1, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Salida') as entrada2, ".                        
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Salida') as salida1, ".
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Entrada') as salida2 ".                        
                            "from checadas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                                "inner join horario_teorico as h ".
                                    "on a.idemp = h.idemp and a.fecha = h.fecha ".
                            "where a.idemp = '" . $idEmp .
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .  
                            "' and d.nombre = '".$nomDepto .
                            "' and a.estatus = 'FOT' and a.aplicado not in ('SI','OR') " .
                            "order by a.idemp, a.fecha";
            }else {         
            // todos los empleados de un solo depto           
                $sente = "select distinct a.idemp, a.idperiodo, e.nombre, e.idtipo, ".
                            "d.nombre as departamento, a.fecha, h.descripcion as horario_teorico, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Entrada') as entrada1, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Salida') as entrada2, ".                        
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Salida') as salida1, ".
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Entrada') as salida2 ".           
                            "from checadas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                                "inner join horario_teorico as h ".
                                    "on a.idemp = h.idemp and a.fecha = h.fecha ".                        
                            "where STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .  
                            "' and d.nombre = '".$nomDepto . 
                            "' and a.estatus = 'FOT' and a.aplicado not in ('SI','OR') " .                        
                            "order by a.idemp, a.fecha";

        }
    }else {
            //todos
                $sente = "select distinct a.idemp, a.idperiodo, e.nombre, e.idtipo, ".
                            "d.nombre as departamento, a.fecha, h.descripcion as horario_teorico, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Entrada') as entrada1, ".
                            "(select min(b.hora) from checadas b
                                where a.idemp = b.idemp 
                                and a.fecha = b.fecha
                                and b.tipo = 'Checarse/Salida') as entrada2, ".                        
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Salida') as salida1, ".
                            "(select max(c.hora) from checadas c
                                where a.idemp = c.idemp
                                and a.fecha = c.fecha
                                and c.tipo = 'Checarse/Entrada') as salida2 ".           
                            "from checadas a ".
                                "inner join empleado e ".
                                    "on a.idemp=e.idemp ".
                                "inner join departamento as d ".
                                    "on e.iddepto = d.iddepto ".
                                "inner join horario_teorico as h ".
                                    "on a.idemp = h.idemp and a.fecha = h.fecha ".                        
                            "where STR_TO_DATE(a.fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(a.fecha,'%d/%m/%Y') <= '" . $fechafin1 .  
                            "' and a.estatus = 'FOT' and a.aplicado not in ('SI','OR') " .                        
                            "order by a.idemp, a.fecha";             

    }     
    
    //echo $sente;
    
    $result = mysql_query($sente, $cn) ;

    $linea = 0;
    $Num = 1;
    $idempant = "";
    $nvo = "";
    $registros_x_empleado = 0;
    $clase = ' class="FilaImpar"';
    $tot_hrs_trabajadas = 0;
    $tot_min_trabajados = 0;    

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
                                <td align="center" style="font-size:15px;"></td>                                  
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                                        $tot_hrs_trabajadas . '</b></td>                    
                    </tr>';

                    echo '</table><table width="100%" border="0" align="center" bgcolor="#FFFFFF">';                   
                    
                }else{
                    $nvo = "";
                }
            }

            if ($nvo == "SI") {

                //------------------------------------------------------------------------
                //obtengo el tipo de empleado
                $sente5 = "SELECT idTipo FROM empleado where idEmp =". $idempant;
                $result5 = mysql_query($sente5, $cn) ;    
                $row5 = mysql_fetch_array($result5);
                $tipo_empleado = $row5['idTipo'];

                $sente6 = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
                $result6 = mysql_query($sente6, $cn) ;    
                $row6 = mysql_fetch_array($result6);
                $tipo_empleado_desc = $row6['Descripcion'];                   

                echo '<td>&nbsp&nbsp;</td>';            
                echo '<table width="100%" border="1" align="center" bgcolor="#08088A">';
                echo '<tr align "left"><td align="left"><font color="#FFFFFF" size="3"><b>' . 
                          $departamentoant . " - " . $idempant . " - " .  $nombreant . " -   " . $tipo_empleado_desc . '</b></font></td>';
                echo '</table>';

                echo '<table width="100%" border="0" align="center" bgcolor="#FFFFFF">';
                echo '<tr align="right"' . $clase . '>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Fecha registro</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Horario teorico</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora entrada</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora salida</b></td>                            
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Horas trabajadas</b></td> 
                      </tr>';                         
                $registros_x_empleado = 0;
                $numero_empleados += 1;  

                $tot_hrs_trabajadas = 0;
                $tot_min_trabajados = 0;                                  

            }     
            
            //verifico si la entrada o salida es null
            $entrada = $row['entrada1'];           
            if ($entrada == NULL) {
                $entrada = $row['entrada2'];
            }
            if ($entrada == NULL){
                $entrada = 0;
            }
            
            $salida = $row['salida1'];
            if ($salida == NULL) {
                $salida = $row['salida2'];
            }     
            if ($salida == NULL){
                $salida = 0;
            }
                        
            
            //obtengo horas trabajadas por dia                       
            $seg_trabajados = strtotime($salida) - strtotime($entrada);
            $min_trabajados = $seg_trabajados / 60;
            $hrs_trabajadas = convierte_mins_a_horas($min_trabajados,"");
            
            $tot_min_trabajados += $min_trabajados;                            
                            
            echo '<tr align="right"' . $clase . ' >
                            <td align="center" style="font-size:13px;">' . $row['fecha'] . '</td>
                            <td align="center" style="font-size:13px;">' . $row['horario_teorico'] . '</td>
                            <td align="center" style="font-size:14px;">' . $entrada . '</td>
                            <td align="center" style="font-size:14px;">' . $salida . '</td>
                            <td align="center" style="font-size:14px;">' . $hrs_trabajadas . '</td> 
            </tr>';                        
      
            
    //    }
    }//fin while
    
    $tot_hrs_trabajadas = convierte_mins_a_horas($tot_min_trabajados,"NO");
   
   // echo "<tr><td>".$sente1 ."</td></tr>";
    
    echo '<tr align="right"' . $clase . ' border="1">
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td>  
         <td align="center" style="font-size:15px;"></td>           
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td> 
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                 $tot_hrs_trabajadas . '</b></td>                                
        </tr><tr><td><p></td></tr>';            

                     
     mysql_free_result($result);
   //  mysql_free_result($result1);
     mysql_close($cn);
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Control de asistencias 1.0</title>
        <link href="../adds/estilo.css" rel="stylesheet" type="text/css" />

        <style type="text/css">

            body {
                background-image: url(../imagenes/fondo.gif);
            }
            .Estilo13 {font-family: "Times New Roman", Times, serif; font-size: 16px; }
            .Estilo14 {font-size: 16px}
            .Estilo15 {
                font-size: 18px;
                font-weight: bold;
            }
            .Estilo16 {color: #FF0000}            
        </style>
        <script type="text/javascript" src="../adds/lib.js">
            function regresar(){
                location.href='Menu.php';
            }                        
        </script>
        
    </head>
    <!--Comienza area de Contenido -->
    <body>
<!--728-->
       <!-- <table width="95%" border="0" align="center"> -->
        <table border="0" style="text-align:center;background-color: white" width="100%">
            <form action="imprimir_retardos.php" target="_blank" method="post" id="Menu" >  

            <tr>
                <td colspan="3">
                    <img alt="Preparatoria Dos - UADY" src="imagen/logo2.jpg" />
                </td>
            </tr>            
            <tr align ="left">
                <td><p><b>Departamento: </b><?php echo $nomDepto; ?> </p></td> 
            </tr>
            <tr align ="left">
                <td><p><b>Empleado: </b><?php echo $nomEmp; ?> </p></td>                
            </tr>
            <tr align ="left">
                <td><p><b>Reporte seleccionado: </b><?php echo $nomRep; ?> </p></td>                
            </tr> 
            <tr align ="left">
                <td><p><b>Fecha inicial: </b><?php echo $fechaini; ?> </p></td>                
            </tr> 
            <tr align ="left">
                <td><p><b>Fecha final: </b><?php echo $fechafin; ?> </p></td>                
            </tr>
            <tr>
                <?php 
                if ($tipoRep == '1') {
                ?> 
                <td colspan="4" align="center">                   
                    <input type="submit" name="btnImprime" value="Imprimir" onclick=""></input>
                </td>
                
                <?php
                }
                ?>
                <td>
                    <option><a href="Menu.php">Regresar al Menu</a></option>
                </td>                            
            </tr>
            <tr>
                <td align="centar">
                    <?php
                        regChecadas();
                    ?>
                </td>
            </tr>            

            </form>
       </table>

    </body>
</html>