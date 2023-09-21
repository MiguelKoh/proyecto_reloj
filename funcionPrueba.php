<?php 

function aumenta_o_quita_dias_fecha($fecha,$ndias)
  {
    $separar = explode ('-',$fecha);
    $año = $separar[0];
    $mes = $separar[1];
    $dia = $separar[2];
    
    //queda en formato aaaa-mm-dd
    $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
    $nuevafecha=date("Y-m-d",$nueva);    
    
    //cambiando el formato para que sea dd/mm/aaaa
    $separar = explode("-",$nuevafecha);
    $año = $separar[0];
    $mes = $separar[1];
    $dia = $separar[2];

    $nuevafecha = $dia."/".$mes."/".$año;
    
    return $nuevafecha;
    
  }


$fechaInicio ="2023-09-18";
$ndias = 3;


aumenta_o_quita_dias_fecha($fechaInicio,$ndias);





 ?>