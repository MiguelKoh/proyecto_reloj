
<?php 

function aumenta_o_quita_dias_fecha($fecha, $ndias)
{
    $separar = explode('-', $fecha);
    $año = $separar[0];
    $mes = $separar[1];
    $dia = $separar[2];

    // Crea un nuevo timestamp agregando los días deseados
    $nueva = mktime(0, 0, 0, $mes, $dia + $ndias, $año);
    
    // Formatea el nuevo timestamp como una fecha en formato "Y-m-d"
    $nuevafecha = date("Y-m-d", $nueva);

    return $nuevafecha;
}

$fecha = "2023-09-18";
$ndias = 3;

$prueba = aumenta_o_quita_dias_fecha($fecha, $ndias);
echo $prueba;


?>