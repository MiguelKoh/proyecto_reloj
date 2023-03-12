<?php
    
    //proceso para verificar si los registros de faltas o inasistencias realmente es porque no checo el empleado.

    session_start(); // Use session variable on this page. This function must put on the top of page.
    include('conex.php'); 
    $cn = ConectaBD();
    
    //$fechaini = $_SESSION["fechaini"];
    //$fechafin = $_SESSION["fechafin"];
   //$fechaini = "17-08-2012";
   //$fechafin = "31-08-2012";
    
    //cambio posicion de fecha
    //$fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    //$fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;    
    
    $fechaini1 = '2012-10-01';
    $fechafin1 = '2012-10-15';
    
    $sente = "select idRegistros, idemp, fecha, horaIni, horario, horaFin, checadaIni1, checadaFin1, tarde," .
            "temprano, ausente, jornadaTrabajada, attTime ".
            "from registros " .
            "where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . "'";
           // "' and idemp = '8524'";
    
    $result=mysql_query($sente, $cn);   
    //$row = mysql_fetch_array($result);
    
    echo "<tr><td> $sente </td></tr>";
    
    //recorro los registros cargados en tabla Excel del periodo seleccionado
    while ($row = mysql_fetch_array($result)) {       
        $verifica_entrada = "";
        $verifica_salida = "";
        
        //checa si fallo la entrada
        if ($row['checadaIni1'] == ""){
            $verifica_entrada = "SI";
        }
       
        //checa si fallo la salida
        if ($row['checadaFin1'] == ""){
            $verifica_salida = "SI";
        }
        
        //checa si la entrada es igual a la salida
        if ($row['checadaIni1'] == $row['checadaFin1']){
            
            $sente1 = sprintf("Update registros set checadaFin1='' where idRegistros=%d",$row['idRegistros'] );
            mysql_query($sente1, $cn);  
            
            $row['checadaFin1'] = "";
            
            $verifica_salida = "SI";
        }        
                 
        //ubico un registro dentro de tabla checadas que se adapte a la hora en que debio checar el empleado
        $nva_Entrada = "";
        $nva_Salida = "";
        
        if ($verifica_entrada == "SI") {
            $sente1 = "SELECT idChecada, idemp, fecha, hora, tipo, estatus, TIMEDIFF(hora,'" . $row['horaIni'] . 
                    "') as dif_hora FROM checadas " .
                    "where estatus not in ('OK', 'Repeat')" . 
                    "and idemp='" . $row['idemp'] .
                    "' and fecha='" . $row['fecha'] . 
                    "' and ABS(TIMEDIFF(hora,'" . $row['horaIni'] . "'))<4500" .
                    " and aplicado <> 'SI';";            
            $result1=mysql_query($sente1, $cn); 
            $row1 = mysql_fetch_array($result1);
            
            $nva_Entrada = $row1['hora'];
            
            // actualiza el registro en checadas para que no se tome mas adelante
            if ($nva_Entrada > "") {
                $sente1 = sprintf("Update checadas set aplicado='SI' where idChecada=%d",$row1['idChecada'] );   
                mysql_query($sente1, $cn);                
            }
            
            
            echo "<tr><td> $sente1 </td></tr>";
        }
        
        if ($verifica_salida == "SI") {
            $sente1 = "SELECT idChecada, idemp, fecha, hora, tipo, estatus, TIMEDIFF(hora,'" . $row['horaFin'] . 
                    "') as dif_hora FROM checadas " .
                    "where estatus not in ('OK', 'Repeat')" . 
                    "and idemp='". $row['idemp'] .
                    "' and fecha='" . $row['fecha'] . 
                    "' and ABS(TIMEDIFF(hora,'" . $row['horaFin'] . "'))<4500" .
                    " and aplicado <> 'SI';";    
            $result1=mysql_query($sente1, $cn); 
            $row1 = mysql_fetch_array($result1);
            
            $nva_Salida = $row1['hora'];
            
            echo "<tr><td> $sente1 </td></tr>";
            
            // actualiza el registro en checadas para que no se tome mas adelante
            if ($nva_Salida > "") {
                $sente1 = sprintf("Update checadas set aplicado='SI' where idChecada=%d",$row1['idChecada'] );   
                mysql_query($sente1, $cn);                
            }            
        }    
      
        //actualizo la informacion obtenida
        
        //no existe salida
        if (($nva_Entrada != "" ) && ($verifica_entrada == "SI")){
            $sente2 = sprintf("Update registros set checadaIni1='%s', ausente='' where idemp='%s' and fecha='%s' and horario='%s'", $nva_Entrada, $row['idemp'], $row['fecha'], $row['horario']);   
            mysql_query($sente2, $cn);
            
            //graba bitacora para completar horarios
            $sente2 = "insert bitacora_cambio_registros (idChecada, idRegistros, tipoRegistro) values (" . $row1['idChecada'] . "," . $row['idRegistros'] . ",'Entrada');";
            mysql_query($sente2, $cn);            
        }
        
        //no existe entrada
        if (($nva_Salida != "" ) && ($verifica_salida == "SI")){
            $sente2 = sprintf("Update registros set checadaFin1='%s', ausente='' where idemp='%s' and fecha='%s' and horario='%s'", $nva_Salida, $row['idemp'], $row['fecha'], $row['horario']);   
            mysql_query($sente2, $cn);
            
            //graba bitacora para completar horarios
            $sente2 = "insert bitacora_cambio_registros (idChecada, idRegistros, tipoRegistro) values (" . $row1['idChecada'] . "," . $row['idRegistros'] . ",'Salida');";
            mysql_query($sente2, $cn);            
        }            
    }
    
    mysql_close($cn);
    echo '<script>alert("Los registros se actualizaron correctamente en la base de datos.")</script>';
    echo "<script>location.href='Menu.php'</script>";   
?>
