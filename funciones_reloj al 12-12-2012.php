<?php

//include('conex.php');

function tipoReporte($tipoRep){
    //obtengo el nombre del reporte
    if ($tipoRep == "1"){
       $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
    }else{   
       $nomRep = "Registro completo de asistencias"; 
    }
    return $nomRep;
}

function cambiarPosicionFecha($fecha){
    //cambia de posicion la fecha 
    $fecha_nva = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    return $fecha_nva;
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
    $cn = ConectaBD();
    $sente = "SELECT idTipo FROM empleado where idEmp =". $idemp;
    $result = mysql_query($sente, $cn) ;    
    $row = mysql_fetch_array($result);
    $tipo_empleado = $row['idTipo'];
    
    //mysql_close($cn);
    
    return $tipo_empleado;
}



function obtener_desc_tipo_empleado($tipo_empleado){
    $cn = ConectaBD();
        
    $sente = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
    $result = mysql_query($sente, $cn) ;    
    $row = mysql_fetch_array($result);
    $tipo_empleado_desc = $row['Descripcion'];
    
    //mysql_close($cn);    
    
    return $tipo_empleado_desc;
}

function listaPeriodos()
{
    $cn = ConectaBD();
    $periodo = "";
    
    try {
        $sente = "select idperiodo,fecha_inicio, fecha_fin, reinicio_contador
                         from periodos";
        $result = mysql_query($sente, $cn);

        while ($row = mysql_fetch_array($result)) {
            $texto = $row['fecha_inicio'] . " - " . $row['fecha_fin'];
            $periodo = $periodo . "<option value=\"" . $row['idperiodo'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $periodo = "";
    }
    //mysql_close($cn);

    return $periodo;
    
}

function listaParciales()
{
    $cn = ConectaBD();
    $parcial = "";
    
    try {
        $sente = "select a.idParcial,a.descripcion,b.descripcion as curso_escolar, a.semestre,a.numParcial
                         from parciales a inner join curso_escolar b on a.idcurso=b.idcurso";
        $result = mysql_query($sente, $cn);

        while ($row = mysql_fetch_array($result)) {
            $texto = "Curso: " . $row['curso_escolar'] . "  Semestre: " . $row['semestre'] . "  Parcial: " . $row['numParcial'];
            $parcial = $parcial . "<option value=\"" . $row['idParcial'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $parcial = "";
    }
    //mysql_close($cn);

    return $parcial;
            
}

function listaPeriodo()
{
    $cn = ConectaBD();
    $curso_escolar = "";
    
    try {
        $sente = "select idcurso,descripcion
                         from curso_escolar";
        $result = mysql_query($sente, $cn);

        while ($row = mysql_fetch_array($result)) {
            $texto = $row['descripcion'];
            $periodo = $periodo . "<option value=\"" . $row['idcurso'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $periodo = "";
    }
    //mysql_close($cn);

    return $periodo;
            
}

function diaSemana($anio,$mes,$dia)
{
	// 0->domingo	 | 6->sabado
	$dia= date("w",mktime(0, 0, 0, $mes, $dia, $anio));
		return $dia;
}



function cant_retardos ($idemp,$reinicio_contador,$id_periodo){
    $cn = ConectaBD();
    
    $cant_retardos = 0;
    
    $id_periodo_ant = $id_periodo-1;
    
    //    verifico retardos previos si $reinicio_contador = 0
    if ($id_periodo_ant != 0){
        if ($reinicio_contador == 0){

            //obtengo contador del periodo inmediato anterior
             $sente = sprintf("select cant_retardos from contador_retardos where idemp=%d and idperiodo=%d", $idemp, $id_periodo_ant);
             $result =  mysql_query($sente, $cn) or die(mysql_error()); 
             $row = mysql_fetch_array($result);

             if ($row) {
               $cant_retardos = $row['cant_retardos'];                    
             }                        
        }
    }
    
    //mysql_close($cn);
    
    return $cant_retardos;
}

function hayParcial($fechaini,$fechafin){
    //verifica si la quincena esta en un periodo parcial y aplica consideraciones
    // especiales para maestros que cuidan examenes
    $cn = ConectaBD();
    
    $sente = "select idParcial,fecha_ini,fecha_fin, idcurso, semestre from parciales
                    where STR_TO_DATE(fecha_ini,'%d/%m/%Y') >= '" . $fechaini .                         
                    "' and '" . $fechafin ."' <= STR_TO_DATE(fecha_fin,'%d/%m/%Y')";        

   // echo "<tr><td>".$sente."</td></tr>";
    
    $result = mysql_query($sente, $cn);
    $row = mysql_fetch_array($result);

    $idParcial = 0;
    
    if ($row){
        $idParcial = $row['idParcial'];
    }
    
    $datos[0]= $idParcial;
    $datos[1]= $row['fecha_ini'];
    $datos[2]= $row['fecha_fin'];
    $datos[3]= $row['idcurso'];
    $datos[4]= $row['semestre'];
    
    //mysql_close($cn);
    return $datos;
    
}

function actualiza_num_retardos ($idemp, $id_periodo, $num_retardos){
    $cn = ConectaBD();
    
    //elimino el registro de retardos del periodo seleccionado.
    $sente_a = sprintf("delete from contador_retardos where idemp=%d and idperiodo=%d", $idemp, $id_periodo);
    $result_a = mysql_query($sente_a, $cn); // or die(mysql_error()); 

    // grabo el nuevo registro
    $sente_a = "insert into contador_retardos (idemp, idperiodo, cant_retardos) values(" . $idemp . "," . $id_periodo . "," . $num_retardos . ")";
    $result_a = mysql_query($sente_a, $cn);// or die(mysql_error()); 
    
    //mysql_close($cn);
    
}

function cambiarFormatoFecha($fecha,$caract_orig,$nvo_caract){
    //cambiando formato de fecha de parciales
    if ($fecha>""){
        
        $a = explode( $caract_orig, $fecha ); 
        $dia_f = $a[0];
        $mes_f = $a[1];
        $anio_f = $a[2];
        
        $nva_fecha = $anio_f . $nvo_caract . $mes_f . $nvo_caract . $dia_f;
        
        return $nva_fecha;
    }
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


function imprimir(){
  //  echo "<script>window.open('imprimir_retardos.php?fechaini=$fechaini&fechafin=$fechafin','myNewWinsr','width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no');</script>";
}

function listaTipoPermisos()
{
    $cn = ConectaBD();
    $tipo_permiso = "";
    
    try {
        $sente = "select idTipoPermisos,Descripcion
                         from tipo_permisos";
        $result = mysql_query($sente, $cn);

        while ($row = mysql_fetch_array($result)) {
            $texto = $row['Descripcion'];
            $tipo_permiso = $tipo_permiso . "<option value=\"" . $row['idTipoPermisos'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $tipo_permiso = "";
    }
    //mysql_close($cn);

    return $tipo_permiso;
    
}

function generaDepartamento()
{ 
        $cn = ConectaBD();
        
	$consulta=mysql_query("SELECT iddepto, nombre FROM departamento");
        
	mysql_close($cn);

	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='lstDepto' id='lstDepto' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>TODOS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}
	echo "</select>";
}

function convierte_mins_a_horas($mins,$manejar_dias){

    $dias = 0;
    
    $previo_minutos = $mins % 60 ;
    $minutos = dos_numeros ($previo_minutos); // lo convierte a 2 caracteres
    
    $previo_horas = $mins / 60; //va a incluir los decimales ej. 2.6
    
    $horas = parte_entera($previo_horas); // devuelve la parte entera
    
    if ($horas >= 24 && $manejar_dias == ""){
        $previo_dias = $horas / 24; //dias incluidos los decimales ej. 1.15
        $dias = parte_entera($previo_dias); // devuelve la parte entera de los días
        
        $horas = $horas % 24;
        $final = $dias . "d "; 
    }else{
        $final = "";
    }
    
    $final = $final . $horas . ":" . $minutos;
    
    return $final;           
}

function parte_entera($numero){
    
    $tiene_enteros = strpos($numero,".");
    
    //echo "<script> alert (".$numero.")</script>";
    
    if ($tiene_enteros == true){
        $separar = explode(".",$numero);
        $entero = $separar[0];
        //$decimal = $separar[1];
    } else {
        $entero = $numero;
        //$decimal = $numero;        
    }        
    
    return $entero;
            
}

function dos_numeros ($numero){
    $largo = strlen($numero);
    
    if ($largo == 1){
        $nvo_numero = "0" . $numero;
    } else {
        $nvo_numero = $numero;
    }
    
    return $nvo_numero;
}

function listaServicioSocial()
{
    $cn = ConectaBD();
    $serv_social = "";
    
    try {
        $sente = "select idemp,nombre
                         from empleado where practicante ='S'";
        $result = mysql_query($sente, $cn);
        
        echo "<option value='0'>TODOS</option>";
        
        while ($row = mysql_fetch_array($result)) {
            $texto = $row['nombre'];
            $serv_social = $serv_social . "<option value=\"" . $row['idemp'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $serv_social = "";
    }
    //mysql_close($cn);

    return $serv_social;
            
}


function calcular_tiempo_transcurrido($hora1,$hora2){ 
    //Minutos transcurridos entre $hora1 y hora2 ($hora1-$hora2)
    
    $separar[1]=explode(':',$hora1); 
    $separar[2]=explode(':',$hora2); 

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]; 
    
    if ($total_minutos_trasncurridos<=59) {
        if ($total_minutos_trasncurridos<10){
            $total_minutos_trasncurridos='0'.$total_minutos_trasncurridos;
        }
        return "00:".$total_minutos_trasncurridos; 
        
    }else{        
    
        if($total_minutos_trasncurridos>59){ 
            
                $hora_transcurrida = round($total_minutos_trasncurridos/60); 

                if($hora_transcurrida<=9) {
                    $hora_transcurrida='0'.$hora_transcurrida; 
                }    
                $minutos_transcurridos = $total_minutos_trasncurridos%60; 

                if($minutos_transcurridos<=9) {
                    $minutos_transcurridos='0'.$minutos_transcurridos; 
                }
                
                return $hora_transcurrida.':'.$minutos_transcurridos; 
            } 
    }
} 

?>