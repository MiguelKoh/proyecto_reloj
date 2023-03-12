<?php
    
    //proceso para verificar si los registros de faltas o inasistencias realmente es porque no checo el empleado.

    session_start(); // Use session variable on this page. This function must put on the top of page.
    include('conex.php'); 
    $cn = ConectaBD();
    
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"];
    
    //cambio posicion de fecha
    $fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    $fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;    
    
    //$fechaini1 = '2014-03-16';
    //$fechafin1 = '2014-03-31';    
    
     $sente = "select idRegistros, idemp, fecha, horaIni, horario, horaFin, checadaIni1, checadaFin1, tarde," .
            "temprano, ausente, jornadaTrabajada, attTime ".
            "from registros " .
            "where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
            "';";
    
    $result=mysqli_query($cn,$sente);   
    //$row = mysql_fetch_array($result);
    
    //echo "<tr><td> $sente </td></tr>";
    
    $marcar_horario_ent = 0;
    $marcar_horario_sal = 0;
	
    //recorro los registros cargados en tabla Excel del periodo seleccionado
    while ($row = mysqli_fetch_array($result)) {       
        $verifica_entrada = "";
        $verifica_salida = "";

		echo "<tr><td>Verificando registro: ".$row['idRegistros']."</td></tr><p>";
    
        //checa si fallo la entrada
        if ($row['checadaIni1'] == ""){
            $verifica_entrada = "SI";
            $marcar_horario_ent = "";
        }else{
            $marcar_horario_ent = "SI";            
        }        
        
       
        //checa si fallo la salida
        if ($row['checadaFin1'] == ""){
            $verifica_salida = "SI";
            $marcar_horario_sal = "";
        }else{
            $marcar_horario_sal = "SI";
        } 
        
        
        //checa si la entrada es igual a la salida
        if ($row['checadaIni1'] == $row['checadaFin1']){
            
            $sente1 = sprintf("Update registros set checadaFin1='' where idRegistros=%d",$row['idRegistros'] );
            mysqli_query($cn,$sente1);  
            
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
            $result1=mysqli_query($cn,$sente1); 
            $row1 = mysqli_fetch_array($result1);
            
            $nva_Entrada = $row1['hora'];
            
            // actualiza el registro en checadas para que no se tome mas adelante
            if ($nva_Entrada > "") {
                $sente1 = sprintf("Update checadas set aplicado='SI' where idChecada=%d",$row1['idChecada'] );   
                mysqli_query($cn,$sente1);                
            }
            
            
            //echo "<tr><td> $sente1 </td></tr>";
        }
        
        if ($verifica_salida == "SI") {
            $sente1 = "SELECT idChecada, idemp, fecha, hora, tipo, estatus, TIMEDIFF(hora,'" . $row['horaFin'] . 
                    "') as dif_hora FROM checadas " .
                    "where estatus not in ('OK', 'Repeat')" . 
                    "and idemp='". $row['idemp'] .
                    "' and fecha='" . $row['fecha'] . 
                    "' and ABS(TIMEDIFF(hora,'" . $row['horaFin'] . "'))<4500" .
                    " and aplicado <> 'SI';";    
            $result1=mysqli_query($cn,$sente1); 
            $row1 = mysqli_fetch_array($result1);
            
            $nva_Salida = $row1['hora'];
            
            //echo "<tr><td> $sente1 </td></tr>";
            
            // actualiza el registro en checadas para que no se tome mas adelante
            if ($nva_Salida > "") {
                $sente1 = sprintf("Update checadas set aplicado='SI' where idChecada=%d",$row1['idChecada'] );   
                
                mysqli_query($cn,$sente1);                
            }            
        }    
      
        //actualizo la informacion obtenida
        
        //no existe salida
        if (($nva_Entrada != "" ) && ($verifica_entrada == "SI")){
            $sente2 = sprintf("Update registros set checadaIni1='%s', ausente='' where idemp='%s' and fecha='%s' and horario='%s'", $nva_Entrada, $row['idemp'], $row['fecha'], $row['horario']);   
            mysqli_query($cn,$sente2);
            
            //graba bitacora para completar horarios
            $sente2 = "insert bitacora_cambio_registros (idChecada, idRegistros, tipoRegistro) values (" . $row1['idChecada'] . "," . $row['idRegistros'] . ",'Entrada');";
            mysqli_query($cn,$sente2);            
        }
        
        //no existe entrada
        if (($nva_Salida != "" ) && ($verifica_salida == "SI")){
            $sente2 = sprintf("Update registros set checadaFin1='%s', ausente='' where idemp='%s' and fecha='%s' and horario='%s'", $nva_Salida, $row['idemp'], $row['fecha'], $row['horario']);   
            mysqli_query($cn,$sente2);
            
            //graba bitacora para completar horarios
            $sente2 = "insert bitacora_cambio_registros (idChecada, idRegistros, tipoRegistro) values (" . $row1['idChecada'] . "," . $row['idRegistros'] . ",'Salida');";
            mysqli_query($cn,$sente2);            
        }                
     
        //marca los horarios normales
        //entrada
        if ($marcar_horario_ent == "SI"){
            $sente1 = "SELECT idChecada, idemp, fecha, hora, tipo, estatus FROM checadas " .
                    "where idemp='". $row['idemp'] .
                    "' and fecha='" . $row['fecha'] . 
                    "' and hora = '" . $row['checadaIni1'] .
                    "' and aplicado not in ('SI','OR');";    
            
            $result1=mysqli_query($cn,$sente1); 
            $row1 = mysqli_fetch_array($result1);
            //echo "<tr><td> $sente1 </td></tr>";
            if ($row1){
                $sente1 = sprintf("Update checadas set aplicado='OR' where idChecada=%d",$row1['idChecada'] );                   
                mysqli_query($cn,$sente1);            
            }                                        
        }
        //salida
        if ($marcar_horario_sal == "SI"){
            $sente1 = "SELECT idChecada, idemp, fecha, hora, tipo, estatus FROM checadas " .
                    "where idemp='". $row['idemp'] .
                    "' and fecha='" . $row['fecha'] . 
                    "' and hora = '" . $row['checadaFin1'] .
                    "' and aplicado not in ('SI','OR');";   
            
            $result1=mysqli_query($cn,$sente1); 
            $row1 = mysqli_fetch_array($result1);
           // echo "<tr><td> $sente1 </td></tr>";
            if ($row1){
                $sente1 = sprintf("Update checadas set aplicado='OR' where idChecada=%d",$row1['idChecada'] );                   
                mysqli_query($cn,$sente1);            
            }                
                        
        }        
    }
    
    mysqli_close($cn);
    echo '<script>alert("Los registros se actualizaron correctamente en la base de datos.")</script>';
    echo "<script>location.href='importarChecadas.php'</script>";  
?>
