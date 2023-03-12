<?php
    
    //proceso que acomoda en una tabla los registros de entrada y salida del empleado.

    session_start(); // Use session variable on this page. This function must put on the top of page.
    
    include('conex.php');
    include('funciones_reloj.php');
    
    $cn = ConectaBD();
    
    $id_periodo = $_SESSION["id_periodo"];
    
    //cambio posicion de fecha
   // $fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    //$fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;    
    
   // $fechaini1 = '2013-01-01';
    //$fechafin1 = '2013-01-15';    
    
    //Obtengo los empleados de un periodo determinado.
    $sente ="SELECT distinct idEmp from checadas ".            
            "where idperiodo = " . $id_periodo . ";";
    
    $result=mysql_query($sente, $cn);      
    
    while ($row = mysql_fetch_array($result)) {    
    
        $idemp = $row['idEmp'];
       
        //select para generar todos los registros de entradas
        $sente1 ="SELECT idChecada,fecha,hora,tipo,idperiodo FROM checadas ".            
                "where idemp = " . $idemp . " and tipo in ('Checarse/Entrada','Tiempo Extra Entrada','Entrada') ".
                " and idperiodo = " . $id_periodo .
                " order by idperiodo, fecha, hora;";

        $result1=mysql_query($sente1, $cn);   
        
        //todos los registros de entrada de un empleado en específico
        while ($row1 = mysql_fetch_array($result1)) {  
        
            $sente2 = "SELECT horaIni from checadas_depuradas ".
                    "where idemp = " . $idemp . " and fecha = '".
                    $row1['fecha'] . "' and horaIni = '" . $row1['hora'] . 
                    "' and idperiodo = " . $id_periodo;           
           
            $result2=mysql_query($sente2, $cn); 
            
            //si no encuentro la hora de entrada en la tabla de checadas_depuradas la grabo.
            if (!($row2 = mysql_fetch_array($result2))){
                
                echo "<tr><td>Empleado : ". $idemp . ", fecha: " . $row1['fecha'] . " entrada: " . $row1['hora'] . " reg: " . $row1['idChecada'] . "</td></tr><p>";

                $sente3 = "insert checadas_depuradas (idEmp, fecha,horaIni,idperiodo,idChecada_ini) values (" . $idemp . ",'" . $row1['fecha'] . "','" . $row1['hora'] . "'," . $row1['idperiodo'] . "," . $row1['idChecada'] .");";
                mysql_query($sente3, $cn);                 
            }
        }
         
        
        //select para generar todos los registros de salidas
        $sente1 ="SELECT idChecada,fecha,hora,tipo,idperiodo FROM checadas ".            
                "where idemp = " . $idemp . " and tipo in ('Checarse/Salida','Tiempo Extra Salida','Salida') ".
                " and idperiodo = " . $id_periodo .
                " order by idperiodo, fecha, hora;";

        $result1=mysql_query($sente1, $cn);   
        
        //todos los registros de entrada/salida de un empleado en específico
        while ($row1 = mysql_fetch_array($result1)) {  
        
            $sente2 = "SELECT idChecadaDep, horaIni from checadas_depuradas ".
                    "where idemp = " . $idemp . " and fecha = '".
                    $row1['fecha'] . "' and horaFin is null and idperiodo = " . $id_periodo;           
           
            $result2=mysql_query($sente2, $cn); 
            
            //si no encuentro la hora de entrada en la tabla de checadas_depuradas la grabo.
            while ($row2 = mysql_fetch_array($result2)){
                
                //verifico si la entrada grabada es menor a la salida que tengo
                $hrs_trabajadas = calcular_tiempo_transcurrido($row1['hora'],$row2['horaIni']);                              
                
                echo "<tr><td>Empleado : ". $idemp . ", fecha: " . $row1['fecha'] . " salida: " . $row1['hora'] . " reg: " . $row1['idChecada'] . "</td></tr><p>";
                
                $sente3 = sprintf("update checadas_depuradas set horaFin='%s', idChecada_fin = '%d', horasTrabajadas = '%s' where idChecadaDep = '%d'", $row1['hora'] , $row1['idChecada'], $hrs_trabajadas, $row2['idChecadaDep']);                
                mysql_query($sente3, $cn);
                
                break;
            }
        }
        
    } 
    mysql_close($cn);
    echo '<script>alert("Los registros se actualizaron correctamente en la base de datos.")</script>';
    echo "<script>location.href='Menu.php'</script>";   
?>
