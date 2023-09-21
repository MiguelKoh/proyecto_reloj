<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
    
    $idDepto = $_GET['idDepto'];
    $idEmp =$_GET['idEmp'];
    $fechaInicio = $_GET['fechaInicio'];
    $fechaFin = $_GET['fechaFin'];
    $horaInicio = $_GET['horaInicio'];
    $horaFin =$_GET['horaFin'];
    $minInicio = $_GET['minInicio'];
    $minFin= $_GET['minFin'];
    $tipoPermiso =$_GET['tipoPermiso'];
    $descPermiso =$_GET['descPermiso'];

    
/*
    $idDepto = 28;
    $idEmp = 9280;
    $fechaInicio ="2023-09-18";
    $fechaFin ="2023-09-20";
    $horaInicio ="08";
    $horaFin ="16";
    $minInicio ="0";
    $minFin="0";
    $tipoPermiso ="1";
    $descPermiso ="prueba";
    */

                if ($minInicio == "0"){
                    $minInicio = "00";
                }
                
                if ($minFin == "0") {
                    $minFin = "00";
                }
                
                $hora_salida = trim($horaInicio).":".trim($minInicio);
                $hora_regreso = trim($horaFin).":".trim($minFin);

                $dias_permiso = dias_transcurridos($fechaInicio,$fechaFin);        
                $mins_permiso =  calcular_tiempo_trasnc($hora_salida, $hora_regreso);

                //echo $dias_permiso;
             
                $idperiodo = 0;
             
                
               
                for ($iDias = 0; $iDias < $dias_permiso; $iDias++) {
                    $nva_fecha = aumenta_o_quita_dias_fecha($fechaInicio,$iDias);
                  
                    //obtengo a que periodo pertenece la fecha
                    $sente = "SELECT idperiodo FROM periodos ".
                             "where '" . $nva_fecha . "' between STR_TO_DATE(fecha_inicio,'%d/%m/%Y') " .
                             "and STR_TO_DATE(fecha_fin,'%d/%m/%Y')";
                    $result = mysqli_query($cn,$sente);
                    
                    if ($row = mysqli_fetch_array($result)){
                        $idperiodo = $row['idperiodo'];
                    }

                    //Obtengo la descripcion del tipo de permiso
                    $sente = "SELECT descripcion FROM tipo_permisos where idtipo_permisos = " . $tipoPermiso;
                    $result = mysqli_query($cn,$sente);
                    if ($row = mysqli_fetch_array($result)){
                        $desc_tipo_permiso = $row['descripcion'];
                    }
                    
                    //formateo la fecha para que quede como dd/mm/aaaa
                    $separar = explode("-",$nva_fecha);
                    $año = $separar[0];
                    $mes = $separar[1];
                    $dia = $separar[2];
                    $nva_fecha = $dia."/".$mes."/".$año;   

                    date_default_timezone_set('America/Mexico_City');
                   
                    //grabo informacion de permisos en tabla
                     $sente = "INSERT INTO permisos (idEmp,fechaIni,horaIni,fechaFin,horaFin,tipo,motivo,horaCaptura,minutosDiarios,idperiodo) ".
                            "VALUES (".$idEmp.",'".$nva_fecha."','".$hora_salida."','".$nva_fecha."','".$hora_regreso.
                            "','".$desc_tipo_permiso."','".$descPermiso."','".date("d/m/Y H:i:s")."',".$mins_permiso.",".$idperiodo.")";            
                    $result = mysqli_query($cn,$sente) ;                        
                    
                }
 
                
                $sente = "SELECT idPermisos,fechaIni,".
                                                 "dayofweek(STR_TO_DATE(fechaIni,'%d/%m/%Y')) as dia_semana,".
                                                 "horaIni,horaFin,tipo,motivo,minutosDiarios FROM permisos where STR_TO_DATE(fechaini,'%d/%m/%Y') ".
                                                 "between '" . $fechaInicio . "' and '" . $fechaFin . "' and ".
                                                 " idemp = " . $idEmp . " order by STR_TO_DATE(fechaini,'%d/%m/%Y')";
                                        $result = mysqli_query($cn,$sente);
                                        $numreg = mysqli_num_rows($result);
                                        //print_r($row = mysqli_fetch_array($result));

                    

                    if ($numreg > 0) {
                                   $resultados = array();
                                   $row = mysqli_fetch_array($result);
                                   $separar = explode('/', $row['fechaIni']);
                                   $dia = $separar[0];
                                   $mes = $separar[1];
                                   $anio = $separar[2];
                                   $dia_semana = diaSemana($anio, $mes, $dia);
                                   $nombre_dia = nombre_dia($dia_semana);

                                   $item = array(
                                       'fecha' => $row['fechaIni'],
                                       'dia' => $nombre_dia,
                                       'hora_inicio' => $row['horaIni'],
                                       'hora_fin' => $row['horaFin'],
                                       'tipo_permiso' => $row['tipo'],
                                       'motivo' => utf8_encode($row['motivo']),
                                       'Minutos' => $row['minutosDiarios']
                                   );

                                   $resultados[] = $item;
                               

    echo json_encode($resultados);
} 





else {
   
    echo json_encode(array());
}


?>

