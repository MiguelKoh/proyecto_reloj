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
function cambiarPosicionFecha1($fecha){
    //cambia de posicion la fecha 
    $fecha_nva = implode( '-', array_reverse( explode( '/', $fecha ) ) ) ;
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
        return abs(($total_minutos_trasncurridos.' Minutos')); 
    
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
    $result = mysqli_query($cn,$sente) ;    
    $row = mysql_fetch_array($result);
    $tipo_empleado = $row['idTipo'];
    
    //mysql_close($cn);
    
    return $tipo_empleado;
}

function velador_debe_trabajar($idemp, $fecha){
    $cn = ConectaBD();
    $debe_trabajar = "";
    
    $sente = "SELECT fechasTrabajo FROM veladores where idEmp =". $idemp . " and fechasTrabajo ='" . $fecha . "'";
    $result = mysqli_query($cn,$sente) ;    
    $row = mysqli_fetch_array($result);
    
    if ($row){
        $debe_trabajar = "SI";
    }
    return $debe_trabajar;
}



function obtener_desc_tipo_empleado($tipo_empleado){
    $cn = ConectaBD();
        
    $sente = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
    $result = mysqli_query($cn,$sente) ;    
    $row = mysqli_fetch_array($result);
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
        $result = mysqli_query($cn, $sente);

        while ($row = mysqli_fetch_array($result)) {
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
    
    //obtengo semestre vigente
    $hoy = date("Y-m-d");
    $datos_semestre = obtener_semestre ($hoy);
    $id_semestre = $datos_semestre [1];
	    
    try {
        $sente = "select idParcial,descripcion
                         from parciales where semestre=" . $id_semestre;
        $result = mysqli_query($cn,$sente);

        $parcial = "<option value=0>Selecciona el examen o retroalimentacion correspondiente </option>";
        while ($row = mysqli_fetch_array($result)) {
            $texto = $row['descripcion'];
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
        $result = mysqli_query($cn,$sente);

        while ($row = mysqlI_fetch_array($result)) {
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
        
        //cambio el 0 del domingo a 7
        if ($dia == 0){
            $dia = 7;
        }
        
        return $dia;
}



function cant_retardos ($idemp,$reinicio_contador,$id_periodo){
    $cn = ConectaBD();
    
    $cant_retardos = 0;
    
    if($id_periodo%2==0){
            $id_periodo_ant = $id_periodo-1;
        }else{
            $id_periodo_ant = $id_periodo;
        }
    
    //    verifico retardos previos si $reinicio_contador = 0
    if ($id_periodo_ant != 0){
        if ($reinicio_contador == 0){

            //obtengo contador del periodo inmediato anterior
             $sente = sprintf("select cant_retardos from contador_retardos where idemp=%d and idperiodo=%d", $idemp, $id_periodo_ant);
             $result =  mysqli_query($cn,$sente) or die(mysql_error()); 
             $row = mysqli_fetch_array($result);

             if ($row) {
               $cant_retardos = $row['cant_retardos'];                    
             }                        
        }
    }
    
    //mysql_close($cn);
    
    return $cant_retardos;
}

function cant_minutos($idemp, $reinicio_contador_minutos, $id_periodo){
	//elaborado el 21/03/2014
	$cn = ConectaBD();
    
    $cant_minutos = 0;
    	
            $id_periodo_ant = $id_periodo-1;
        
    
	//echo "periodo anterior".$id_periodo_ant."--";
    //    verifico retardos previos si $reinicio_contador = 0
    if ($id_periodo_ant != 0){
        if ($reinicio_contador_minutos == 0){

            //obtengo contador del periodo inmediato anterior
             $sente = sprintf("select min_acumulados from minutos_acumulados where idemp=%d and idperiodo=%d", $idemp, $id_periodo_ant);
			 //echo $sente."<br>";
             $result =  mysqli_query($cn,$sente) or die(mysql_error()); 
             $row = mysqli_fetch_array($result);

             if ($row) {
               $cant_minutos = $row['min_acumulados'];                    
             }                        
        }
    }    
    //mysql_close($cn);
    
    return $cant_minutos;
	
}
function hayParcial($fecha){
    //$fechaini,$fechafin
    //verifica si la quincena esta en un periodo parcial y aplica consideraciones
    // especiales para maestros que cuidan examenes
    $cn = ConectaBD();
    
    $sente = "select idParcial,fecha_ini,fecha_fin, idcurso, semestre, idtipoexcepcion from parciales
                    where '" . $fecha . "' >= STR_TO_DATE(fecha_ini,'%d/%m/%Y') and                          
                    '" . $fecha ."' <= STR_TO_DATE(fecha_fin,'%d/%m/%Y')";    

   // echo "<tr><td>".$sente."</td></tr>";
    
    $result = mysqli_query($cn,$sente);
    $row = mysqli_fetch_array($result);

    $idParcial = 0;
    
    if ($row){
        $idParcial = $row['idParcial'];
    }
    
    $datos[0]= $idParcial;
    $datos[1]= $row['fecha_ini'];
    $datos[2]= $row['fecha_fin'];
    $datos[3]= $row['idcurso'];
    $datos[4]= $row['semestre'];
    $datos[5]= $row['idtipoexcepcion'];
    
    //mysql_close($cn);
    return $datos;
    
}

function actualiza_num_retardos ($idemp, $id_periodo, $num_retardos){
    $cn = ConectaBD();
    
    //elimino el registro de retardos del periodo seleccionado.
    $sente_a = sprintf("delete from contador_retardos where idemp=%d and idperiodo=%d", $idemp, $id_periodo);
    $result_a = mysqli_query($cn,$sente_a); // or die(mysql_error()); 

    // grabo el nuevo registro
    $sente_a = "insert into contador_retardos (idemp, idperiodo, cant_retardos) values(" . $idemp . "," . $id_periodo . "," . $num_retardos . ")";
    $result_a = mysqli_query($cn,$sente_a);// or die(mysql_error()); 
    
    //mysql_close($cn);
    
}

function actualiza_num_minutos ($idemp, $id_periodo, $minutos_acumulados){
	// elaborado el 21/03/2014
    $cn = ConectaBD();
    
    //elimino el registro de retardos del periodo seleccionado.
    $sente_a = sprintf("delete from minutos_acumulados where idemp=%d and idperiodo=%d", $idemp, $id_periodo);
    $result_a = mysqli_query($cn,$sente_a); // or die(mysql_error()); 

    // grabo el nuevo registro
    $sente_a = "insert into minutos_acumulados (idemp, idperiodo, min_acumulados) values(" . $idemp . "," . $id_periodo . "," . $minutos_acumulados . ")";
    $result_a = mysqli_query($cn,$sente_a);// or die(mysql_error()); 
    
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
  /*  echo "<script>window.open('imprimir_retardos.php?fechaini=$fechaini&fechafin=$fechafin','myNewWinsr','width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no');</script>";*/
}

function listaTipoPermisos()
{
    $cn = ConectaBD();
    $tipo_permiso = "";
    
    try {
        $sente = "select idTipo_Permisos,Descripcion
                         from tipo_permisos";
        $result = mysqli_query($cn,$sente);

        while ($row = mysqli_fetch_array($result)) {
            $texto = $row['Descripcion'];
            $tipo_permiso = $tipo_permiso . "<option value=\"" . $row['idTipo_Permisos'] . "\">" . $texto . "</option>";
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
        
	$consulta=mysqli_query($cn,"SELECT iddepto, nombre FROM departamento ORDER BY nombre");
        
	mysqli_close($cn);

	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='lstDepto' id='lstDepto' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>TODOS</option>";
	while($registro=mysqli_fetch_row($consulta))
	{
		echo "<option value='".$registro[0]."'>".utf8_encode($registro[1])."</option>";
	}
	echo "</select>";
}

function convierte_mins_a_horas($mins,$manejar_dias){

    $dias = 0;
    
    $previo_minutos = $mins % 60 ;
    $minutos = dos_numeros ($previo_minutos); // lo convierte a 2 caracteres
    
    $previo_horas = $mins / 60; //va a incluir los decimales ej. 2.6
    
    $horas = parte_entera($previo_horas); // devuelve la parte entera
    
    // los dias laborales se toman de 8 horas no de 24
    if ($horas >= 8 && $manejar_dias == ""){
        $previo_dias = $horas / 8; //dias incluidos los decimales ej. 1.15
        $dias = parte_entera($previo_dias); // devuelve la parte entera de los días
        
        $horas = $horas % 8;
        $final = $dias . "d "; 
    }else{
        $final = "";
    }
    
    $final = $final . $horas . ":" . $minutos;
    
    return $final;           
}

function parte_entera($numero){
    
   // $tiene_enteros = strpos($numero,".");
    
    /*echo "<script> alert (".$numero.")</script>";*/
    
   /* if ($tiene_enteros == true){
        $separar = explode(".",$numero);
        $entero = $separar[0];
        //$decimal = $separar[1];
    } else {
        $entero = $numero;
        //$decimal = $numero;        
    }   */    
    
    return floor($numero);
            
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
        $result = mysqli_query($cn,$sente);
        
        echo "<option value='0'>TODOS</option>";
        
        while ($row = mysqli_fetch_array($result)) {
            $texto = $row['nombre'];
            $serv_social = $serv_social . "<option value=\"" . $row['idemp'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $serv_social = "";
    }
    //mysql_close($cn);

    return $serv_social;           
}

function minutos_transcurridos($hora1,$hora2){
    $separar[1]=explode(':',$hora1); 
    $separar[2]=explode(':',$hora2); 

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]; 

    return $total_minutos_trasncurridos;
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


function Request($GetValue) {
  if ($GetValue <> "") {
    $RData = arr_render($GetValue,",","1");
	$n = count($RData[1]);
	for ($x = 1; $x <= $n; $x++) {
    	if (isset($_POST[$RData[1][$x]]) and ($_POST[$RData[1][$x]] <> "")) {
	      $Value[$x][1] = $_POST[$RData[1][$x]];
		  $Value[$x][2] = 1;
	    } else {
	      if (isset($_GET[$RData[1][$x]]) and ($_GET[$RData[1][$x]] <> "")) {
		    $Value[$x][1] = $_GET[$RData[1][$x]];
		    $Value[$x][2] = 1;
		  } else {
		    $Value[$x][1] = "";
		    $Value[$x][2] = 0;
		  }
	    }
    }
  } else {
    $Value[1][1] = "ERROR DE DATOS";
	$Value[1][2] = 0;
  }
return $Value;
}

$RData="";
function arr_render($parametros,$separador,$x) {
$paso="";
$n=1;
		for ($i = 0; $i<=strlen($parametros)-1 ; $i++) {
			if (substr ($parametros, $i, 1)==$separador) {
				$paso="";
				$n=$n+1; 
			} else {
				$paso= $paso.substr($parametros, $i, 1);				
				$RData[$x][$n]=$paso;
			}
		}
return $RData;
return $n;
}

function nombre_dia($id_dia){
    switch($id_dia){
        case 1: 
            $dia = "Lun";
            break;
        case 2: 
            $dia = "Mar";            
            break;
        case 3: 
            $dia = "Mie";
            break;
        case 4: 
            $dia = "Jue";
            break;
        case 5: 
            $dia = "Vie";
            break;
        case 6: 
            $dia = "Sab";
            break;
        case 7: 
            $dia = "Dom";            
            break;
    }
    return $dia;    
        
}

function obtener_semestre ($fecha){
    $cn = ConectaBD();
    
    $sente = "select idsemestre,idcurso,descripcion from semestre where fecha_inicio <= '" . $fecha .
            "' and fecha_fin >= '" . $fecha . "'";
    $result = mysqli_query($cn,$sente);
    if ($row = mysqli_fetch_array($result)){
        
        //obteniendo descripcion del semestre
        $sente1 = "select descripcion from curso_escolar where idcurso = " . $row['idcurso'];        
        $result1 = mysqli_query($cn,$sente1);
        $row1 = mysqli_fetch_array($result1);
        $curso = $row1['descripcion'];
        
        $datos [0] = "Curso: " . $curso . " " . $row['descripcion'];
        $datos [1] = $row['idsemestre'];
        
    }
    else{
        $datos [0] = "";
        $datos [1] = "0";
    }

    return $datos;
}

    function obtenerHorarioTeorico($idEmp) {
        if ($idEmp != ""){
            $cn = ConectaBD();

            //obtengo semestre vigente
            
            $hoy = date("Y-m-d");
            $datos_semestre = obtener_semestre ($hoy);

            $desc_semestre = $datos_semestre [0];
            $semestre = $datos_semestre[1];

            if ($semestre == 0 ){
                echo "No existe un semestre vigente dado de alta";
            }else{
                $sente = "select id_dia,hora_ini,hora_fin from horarios_semestre where idEmp = " . $idEmp . 
                        " and id_semestre = " . $semestre . " order by id_dia,hora_ini" ; //. " and fecha_ini <= '" . $hoy . "' and fecha_fin >= '" . $hoy . "'";
                $result = mysqli_query($cn,$sente);

                $id_dia = "";
                $cont = 1;
                while ($row = mysqli_fetch_array($result)){                           
                    if ($id_dia != $row['id_dia'] ){
                        $cont ++;

                        $id_dia = $row['id_dia'];
                        $dia = nombre_dia($id_dia);

                        $datos[$cont] = $dia . " - " . $row['hora_ini'] . " a " . $row['hora_fin'];
                    }else{                   
                        $datos[$cont] .= " / " . $row['hora_ini'] . " a " . $row['hora_fin'];
                    }     
                }   

            }
            $datos [0] = $desc_semestre;
            $datos [1] = $cont; //en el indice 0 esta la cantidad de registros

            return $datos;

        } 
    }

    function dias_transcurridos($fecha_i,$fecha_f)
    {
            $dias = (strtotime($fecha_i)-strtotime($fecha_f))/86400;
            $dias = abs($dias); 
            $dias = floor($dias) + 1;		
            
            return $dias;
    }    
    
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
  /*  $separar = explode("-",$nuevafecha);
    $año = $separar[0];
    $mes = $separar[1];
    $dia = $separar[2];

    $nuevafecha = $dia."/".$mes."/".$año;
 */   
    return $nuevafecha;
    
  }
  
  function convertir_hora_12_a_24($hora){
      //llega formato de 01:05 p.m.
      //separo la hora del a.m. y p.m.
      $separar = explode (" ",$hora);
      $hora_1 = $separar[0]; // 01:05
      $tiempo = $separar[1]; // a. /p. 
      $tiempo_final = $separar[2]; // m. / m. 
      
      $hora_final = $hora_1; //para que las horas de la mañana tengan valor
      //
      //si es p.m. le aumento 12 hrs
      if ($tiempo == "p."){
          $separar = explode (":",$hora_1);
          $hh = $separar[0]; // 01
          $mm = $separar[1]; // 05
          
          if ($hh != "12"){
              $hh += 12;
          }
          
          $hora_final = $hh . ":" . $mm;
      }
      
      return $hora_final;
     
  }
  
  function id_dia($nombre){
    $dia = "";
    switch($nombre){
        
        case "LUNES": 
            $dia = "1";
            break;
        case "MARTES": 
            $dia = "2";            
            break;
        case "MIERCOLES": 
            $dia = "3";
            break;
        case "JUEVES": 
            $dia = "4";
            break;
        case "VIERNES": 
            $dia = "5";
            break;
        case "SABADO": 
            $dia = "6";
            break;
        case "DOMINGO": 
            $dia = "7";            
            break;
    }
    return $dia;  
  }
  
  function fechas_del_periodo($id_periodo){
        $cn = ConectaBD();

        //obtengo fecha inicial y final del periodo para verificacion
        $sente = "select fecha_inicio, fecha_fin from periodos where idperiodo =" . $id_periodo;
        $result =  mysqli_query($cn,$sente);
        $row = mysqli_fetch_array($result);

        if ($row){
            $fecha[0] = $row['fecha_inicio'];
            $fecha[1] = $row['fecha_fin'];
        }else{
            $fecha[0] = 0;
            $fecha[1] = 0;            
        }
           
        return $fecha;
  }
  
  function dia_que_debe_laborar($fecha,$dia_semana,$idemp,$id_curso,$id_semestre){
        //funcion que verifica si el empleado debe laborar en la fecha indicada, segun su horario cargado
        $cn = ConectaBD();              
        
        $sente = "select STR_TO_DATE(fecha_ini,'%d/%m/%Y') as fecha_ini,STR_TO_DATE(fecha_fin,'%d/%m/%Y') as fecha_fin ".
                " from horarios_semestre where idEmp = '".$idemp."' and id_curso = ".$id_curso.
                " and id_semestre = ".$id_semestre. " and id_dia = " . $dia_semana;
        $result =  mysqli_query($cn,$sente);
        
        $verifico_checada = "";
        
        while ($row = mysqli_fetch_array($result)){
            if ($fecha >= $row['fecha_ini'] || $fecha <= $row['fecha_fin']){
                //el empleado esta checando en un dia en el que si debe laborar
                $verifico_checada = "SI";
                break;
            }                             
        }
        
        return $verifico_checada;       
  }
  
  function datos_periodo($id_periodo){      
      //obtengo el curso y semestre de un periodo dado
      $cn = ConectaBD();
      
      $sente = "select id_curso, id_semestre from periodos where idperiodo = ".$id_periodo;
      $result = mysqli_query($cn,$sente);
      
      $datos[0] = 0;
      $datos[1] = 0;
      
      if ($row = mysqli_fetch_array($result)){
            $datos[0] = $row['id_curso'];
            $datos[1] = $row['id_semestre'];                      
      }
           
      return $datos;
  }

  function cambiafnormal($fecha){
    preg_match('/([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})/', $fecha, $mifecha);
    $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
    return $lafecha;
 }

 function cambiafmysql($fecha){
    ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
    return $lafecha;
}

function Par_Non($Valor)
{
    $Xrt = $Valor;
    $ParNon = ($Xrt % 2);
    if ($ParNon == 1) {
        $ResTN = "NON";
    } else {
        $ResTN = "PAR";
    }
    return $ResTN;
}

function mes($mes){
    if ($mes=="01") $mes="Ene";
    if ($mes=="02") $mes="Feb";
    if ($mes=="03") $mes="Mar";
    if ($mes=="04") $mes="Abr";
    if ($mes=="05") $mes="May";
    if ($mes=="06") $mes="Jun";
    if ($mes=="07") $mes="Jul";
    if ($mes=="08") $mes="Ago";
    if ($mes=="09") $mes="Sep";
    if ($mes=="10") $mes="Oct";
    if ($mes=="11") $mes="Nov";
    if ($mes=="12") $mes="Dic";
    return $mes;
}

function dianormal($mes){
    if ($mes=="01") $mes="Lun";
    if ($mes=="02") $mes="Mar";
    if ($mes=="03") $mes="Mie";
    if ($mes=="04") $mes="Jue";
    if ($mes=="05") $mes="Vie";
    if ($mes=="06") $mes="Sab";
    if ($mes=="07") $mes="Dom";
    return $mes;
}

function saber_dia($nombredia) {

$dias = array('Dom','Lun','Mar','Mie','Jue','Vie','Sab');

$fecha = $dias[date('N', strtotime($nombredia))];

return $fecha;

}
?>