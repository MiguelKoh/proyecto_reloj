<?php
include('funciones_reloj.php');

function obtenerHorario($cve_hora,$tipo_hora,$ent_sal){ 
    $hora = "";
    //busco el horario de inicio en el catalogo de horarios
    $cn = ConectaBD();
    
    $sente2 = "SELECT * FROM catalogo_horarios WHERE id_c_horario = " . $cve_hora . " and id_c_tipo_horario = " . $tipo_hora;
    $result2 = mysql_query($sente2,$cn);
    $row2 = mysql_fetch_array($result2);
    
    if ($row2){
        //valido si estoy solicitando la hora de entrada o la de salida
        if ($ent_sal == 1){
            $hora = $row2['hora_ini'];
        }else{
            $hora = $row2['hora_fin'];
        }
         
    }        
    return $hora;
}

function siguienteHorario($id_c_horario_ant, $id_c_horario,$tipo_hora_ant,$tipo_hora){
    //obtengo horario de salida de la primera clave de horario
    $hora_ant_fin = obtenerHorario($id_c_horario_ant,$tipo_hora_ant,2);
    
    //obtengo horario de entrada de la segunda clave de horario
    $hora_sig_ini = obtenerHorario($id_c_horario,$tipo_hora,1);
    
    $consecutivo = "NO";
    
    //defino si la diferencia entre entrada y salida es menor o igual a 10 minutos lo tomo como igual
    // de lo contrario corto el horario
    
    $minutos_transcurridos = minutos_transcurridos($hora_sig_ini,$hora_ant_fin);
    
    
    /*
    if (($tipo_hora_ant == 1 || $tipo_hora == 2) && ($id_c_horario_ant+1 == $id_c_horario)){
        $consecutivo = "SI";
    }else {
        // falta verificar que ambos id tipo horario sean 1 o 2 y son consecutivos
        if ($minutos_transcurridos <= 10){
            $consecutivo = "SI";
        }else{    
            $consecutivo = "NO";
            }
    }    
    
    */
    
    //independientemente del horario, si la dif entre uno y otro es menor o igual a 10 minutos,
    // es consecutivo.
    
    //esto es para cuando hay cambios de contrato y los horarios son pegados
    if ($minutos_transcurridos <= 10){
        $consecutivo = "SI";
    }else{
        $consecutivo = "NO";
    }
    
    //esto es para que a los profesores les cuenten como consecutivos los horarios de los descansos
    if ($tipo_hora_ant == 1 && $tipo_hora == 1 && $id_c_horario_ant+1 == $id_c_horario){
         $consecutivo = "SI";
    }
    
    return $consecutivo;
    
}

?>
