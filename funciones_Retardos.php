<?php

include('conex.php');

function tipoReporte($tipoRep){
    //obtengo el nombre del reporte
    if ($tipoRep == "1"){
       $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
    }else{   
       $nomRep = "Registro completo de asistencias"; 
    }
    return $nomRep;
}

function esPar($numero){ 
    //determino si el numero es par o impar
   $resto = $numero%2; 
   if (($resto==0) && ($numero!=0)) { 
        return true; 
   }else{ 
        return false; 
   }  
}

function calcular_tiempo_trasnc($hora1,$hora2){ 
    //Determino tiempo transcurrido entre una hora y otra
    $separar[1]=explode(':',$hora1); 
    $separar[2]=explode(':',$hora2); 

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]; 
    
    if($total_minutos_trasncurridos<=59) 
        return($total_minutos_trasncurridos.' Minutos'); 
    
    elseif($total_minutos_trasncurridos>59){ 
        $hora_transcurrida = round($total_minutos_trasncurridos/60); 
        
        if($hora_transcurrida<=9) 
            $hora_transcurrida='0'.$hora_transcurrida; 
            $minutos_transcurridos = $total_minutos_trasncurridos%60; 
        
        if($minutos_transcurridos<=9) 
            $minutos_transcurridos='0'.$minutos_transcurridos; 
        return ($hora_transcurrida.':'.$minutos_transcurridos.' Horas'); 

    }
}


function cambiar_pos_fecha($fecha){
    //cambio posicion de fecha
    $fecha1 = implode( '-', array_reverse( explode( '/', $fecha ) ) ) ;
    return $fecha1;
}

function obtener_tipo_empleado($idemp){
    $cn1 = ConectaBD();
    $sente = "SELECT idTipo FROM empleado where idEmp =". $idemp;
    $result = mysql_query($sente, $cn1) ;    
    $row = mysql_fetch_array($result);
    $tipo_empleado = $row['idTipo'];
    
    mysql_close($cn1);
    
    return $tipo_empleado;
}

function obtener_desc_tipo_empleado($tipo_empleado){
    $cn1 = ConectaBD();
        
    $sente = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
    $result = mysql_query($sente, $cn1) ;    
    $row = mysql_fetch_array($result);
    $tipo_empleado_desc = $row['Descripcion'];
    
    mysql_close($cn1);    
    
    return $tipo_empleado_desc;
}

?>
