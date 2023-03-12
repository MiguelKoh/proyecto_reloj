<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

include('conex.php');
include('funciones_reloj.php');

//$cn = ConectaBD();
$idDepto = $_SESSION["iddepto"];
$idEmp = $_SESSION["idemp"];
$nomEmp = $_SESSION["nomemp"];
$nomDepto = $_SESSION["nomdepto"];
$op = $_SESSION["op"];
//$op2 = $_SESSION["op2"];

$fechaini = $_SESSION["fechaini"];
$fechafin = $_SESSION["fechafin"];

//unicamente se reinicia el contador cuando el valor de $reinicio_contador=1
$reinicio_contador = $_SESSION["reinicio_contador"];
$reinicio_contador_minutos = $_SESSION["reinicio_contador_minutos"];
$id_periodo = $_SESSION["id_periodo"];


/*echo "contador".$reinicio_contador;
echo "<br>id_periodo".$id_periodo;*/

//$tipoRep = $op;
//if ($tipoRep == "1"){
//   $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
//}else{   
//   $nomRep = "Registro completo de asistencias"; 
//}

    //obtengo el tipo de reporte
$tipoRep = $op;
if ($tipoRep == "1"){
   $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
}else{   
   $nomRep = "Registro completo de asistencias";
}

function convertirDia($diaCon) {
    if($diaCon=="Lun")
        $diaD=1;
    if($diaCon=="Mar")
     $diaD=2;
    if($diaCon=="Mie")
     $diaD=3;
    if($diaCon=="Jue")
     $diaD=4;
    if($diaCon=="Vie")
     $diaD=5;
    if($diaCon=="Sab")
     $diaD=6;
    if($diaCon=="Dom")
     $diaD=7;

 return $diaD;

}
function regChecadas() {

    $cn = ConectaBD();
    $idDepto = $_SESSION["iddepto"];
    $idEmp = $_SESSION["idemp"];
    $nomEmp = $_SESSION["nomemp"];
    $nomDepto = $_SESSION["nomdepto"];
    $fechaini = $_SESSION["fechaini"];    
    //$fechaini = "06/12/2012";
    $fechafin = $_SESSION["fechafin"];
    $op = $_SESSION["op"];
	
    $reinicio_contador = $_SESSION["reinicio_contador"];
    $reinicio_contador_minutos = $_SESSION["reinicio_contador_minutos"];
    $id_periodo = $_SESSION["id_periodo"];
    
    //Obtengo el curso y el periodo
    $datos_periodo = datos_periodo($id_periodo);

    $idcurso = $datos_periodo[0];
    $semestre = $datos_periodo[1];    
    
    $numero_empleados = 0; // empleados seleccionados.     

    //creo tabla temporal que tendra los datos del reporte
    $sente = "DELETE FROM retardos_temp";
    $result = mysqli_query($cn,$sente) ;
    
//    /*echo $fechaini."<br>";
//    echo $fechafin."<br>";*/
    
    //cambio posicion de fecha
    
    
    $fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    $fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;
    
  /*  $fechaini1='2013-01-29';
    $fechafin1='2013-01-31';
  */      
  /*  //obtenemos el id del parcial en el periodo de la quincena
    $datos_Parcial = hayParcial($fechaini1,$fechafin1); 
    
    $idParcial = $datos_Parcial[0];
    $fecha_ini_parcial = $datos_Parcial[1];
    $fecha_fin_parcial = $datos_Parcial[2];
    $idcurso = $datos_Parcial[3];
    $semestre = $datos_Parcial[4];
    $numParcial = $datos_Parcial[5];    
    
    //fecha inicio y fin de parciales
    
    $fecha_ini_parcial1 = cambiarFormatoFecha($fecha_ini_parcial,"/","/");
    $fecha_fin_parcial1 = cambiarFormatoFecha($fecha_fin_parcial,"/","/");    
   */ 
    //obtengo el tipo de reporte
    $tipoRep = $op;
    if ($op == "1") {
        $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
    }else{
        $nomRep = "Registro completo de checadas";
    }

    //obtener horario de empleados del departamento seleccionado
    if ($nomDepto <> "TODOS") { 
        if ($nomEmp <> "TODOS"){
            // depto y empleado especifico
            if ($tipoRep == "1"){           
                 $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                            "horaIni,horaFin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horaFin THEN checadafin1
                            WHEN checadafin1>horaFin THEN horaFin
                            ELSE horaFin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaIni THEN horaIni
                            WHEN checadaini1>horaIni THEN checadaini1
                            ELSE horaIni
                            END) as horas_segun_horario," .
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .
                            " from registros where departamento = '" . $nomDepto . 
                            "' and idemp ='" . $idEmp . 
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                            "' and ((TIMEDIFF(horaIni,CASE WHEN checadaini1='' THEN 0 ELSE checadaini1 END)<=0 
or TIMEDIFF(horaFin,CASE WHEN checadafin1='' THEN 0 ELSE checadafin1 END)>=0 or ausente='Y')) "		
							.
							/*******              ***
							"'parte del query anterior : "and ((TIMEDIFF(horaIni,checadaini1)<0 or TIMEDIFF(horaFin,checadafin1)>0 or ausente='Y')) "  // se cambio en query de < a <= y > a >=   el 01/07/2013
							Se valido el caso de que checadaini1 sea vac�o � que checadafin1 sea vac�o el 02/07/2013
							*****************/
                           // "' and TIMEDIFF(horaIni,checadaini1)<0 " .
                            " order by nombre,fecha";

            }else{
                if ($tipoRep == "2"){
                     $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                                "horaIni,horaFin,checadaini1,checadafin1," .
                                "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                                "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .                            
                                "TIMEDIFF(CASE
                                WHEN checadafin1='' THEN 0
                                WHEN checadafin1<horaFin THEN checadafin1
                                WHEN checadafin1>horaFin THEN horaFin
                                ELSE horaFin
                                END,CASE
                                WHEN checadaini1='' THEN 0
                                WHEN checadaini1<horaIni THEN horaIni
                                WHEN checadaini1>horaIni THEN checadaini1
                                ELSE horaIni
                                END) as horas_segun_horario," . 
                                "excepcion, jornadaTrabajada, attTime as jornadaReal " .                            
                                " from registros where departamento = '" . $nomDepto . 
                                "' and idemp ='" . $idEmp .                             
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 . 
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                                "' order by nombre,fecha";                
                }
            }
        } else {            
            // todos los empleados de un solo depto
            if ($tipoRep == "1"){           
                $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                            "horaIni,horaFin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .  
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horaFin THEN checadafin1
                            WHEN checadafin1>horaFin THEN horaFin
                            ELSE horaFin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaIni THEN horaIni
                            WHEN checadaini1>horaIni THEN checadaini1
                            ELSE horaIni
                            END) as horas_segun_horario," .     
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .                        
                            " from registros where departamento = '" . $nomDepto .                            
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                            //"' and ((TIMEDIFF(horaIni,checadaini1)<0 or TIMEDIFF(horaFin,checadafin1)>0 or ausente='Y')) " .
                            "' order by nombre,idemp, fecha";
            }else{
                if ($tipoRep == "2"){
                    $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                                "horaIni,horaFin,checadaini1,checadafin1," .
                                "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                                "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .      
                                "TIMEDIFF(CASE
                                WHEN checadafin1='' THEN 0
                                WHEN checadafin1<horaFin THEN checadafin1
                                WHEN checadafin1>horaFin THEN horaFin
                                ELSE horaFin
                                END,CASE
                                WHEN checadaini1='' THEN 0
                                WHEN checadaini1<horaIni THEN horaIni
                                WHEN checadaini1>horaIni THEN checadaini1
                                ELSE horaIni
                                END) as horas_segun_horario," .  
                                "excepcion, jornadaTrabajada, attTime as jornadaReal " .                            
                                " from registros where departamento = '" . $nomDepto .  
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 . 
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                                "' order by nombre,idemp, fecha";       //and idemp = 9604           
                }
            }
        }
    }else {
        //todos los deptos
        if ($tipoRep == "1"){
             $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                        "horaIni,horaFin,checadaini1,checadafin1," .
                        "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                        "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .   
                        "TIMEDIFF(CASE
                        WHEN checadafin1='' THEN 0
                        WHEN checadafin1<horaFin THEN checadafin1
                        WHEN checadafin1>horaFin THEN horaFin
                        ELSE horaFin
                        END,CASE
                        WHEN checadaini1='' THEN 0
                        WHEN checadaini1<horaIni THEN horaIni
                        WHEN checadaini1>horaIni THEN checadaini1
                        ELSE horaIni
                        END) as horas_segun_horario," .   
                        "excepcion, jornadaTrabajada, attTime as jornadaReal " .                    
                        " from registros where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 . 
                        "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                        "' and ((TIMEDIFF(horaIni,CASE WHEN checadaini1='' THEN 0 ELSE checadaini1 END)<=0 
or TIMEDIFF(horaFin,CASE WHEN checadafin1='' THEN 0 ELSE checadafin1 END)>=0 or ausente='Y')) " .
					   /*******              ***
							"'parte del query anterior : "and ((TIMEDIFF(horaIni,checadaini1)<0 or TIMEDIFF(horaFin,checadafin1)>0 or ausente='Y')) "  // se cambio en query de < a <= y > a >=   el 01/07/2013
							Se valido el caso de que checadaini1 sea vac�o � que checadafin1 sea vac�o el 02/07/2013
							*****************/
					    //"' and TIMEDIFF(horaIni,checadaini1)<0 " .                    
                        "AND idEmp=6808 order by nombre,departamento, idemp, fecha";               
        }else{
            if ($tipoRep == "2"){
                $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                            "horaIni,horaFin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaIni,checadaini1) as difentrada, TIMEDIFF(horaFin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " . 
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horaFin THEN checadafin1
                            WHEN checadafin1>horaFin THEN horaFin
                            ELSE horaFin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaIni THEN horaIni
                            WHEN checadaini1>horaIni THEN checadaini1
                            ELSE horaIni
                            END) as horas_segun_horario," . 
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .                        
                            " from registros where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                    
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                            "' order by nombre,departamento, idemp, fecha";                   
            }
        }     
    }
   echo $sente;
    
    $result = mysqli_query($cn,$sente);

    $linea = 0;
    $Num = 1;
    $idempant = "";
    $nvo = "";
   /***********************************************************************************/
    // 12/03/2014 
     $minutos_acumulados=0; // variable para almacenar el total minutos acumulados para administrativos
    //$cantidad=1;
     $inicioDescuentoAdmin=0;  
     $tardeveces=0;  
     $totaldescontar=0;
     $contarllegadatarde=0;
     $tot_entrada=0;
   /***********************************************************************************/
    $num_retardos = 0;
    $registros_x_empleado = 0;
    $total_minutos_x_descontar = 0;
    $total_minutos_permiso = 0;
    $es_retardo = "0";  // E-corresponde a retardo de entrada S-corresponde a salida anticipada
    $clase = ' class="FilaImpar"';
    $tot_entrada = 0;
    $tot_entrada_con_gracia = 0;
    $tot_salida = 0;
    $sig_registro = ""; // es para definir si los veladores checan o no
    $cont = 0;

    
    while ($row = mysqli_fetch_array($result)) {    

    //$rowun = mysqli_fetch_array($result);        
	//echo  $row['nombre']  ;
            
        $horario_teorico_entrada = $row['horaIni'];
        $horario_teorico_salida = $row['horaFin'];
        
        //------------- verifico si la fecha del registro esta dentro de algun parcial ------------------------------------
        //obtenemos el id del parcial en el periodo de la quincena
		
            $datos_Parcial = hayParcial($row['fecha']); 

            $idParcial = $datos_Parcial[0];
            $fecha_ini_parcial = $datos_Parcial[1];
            $fecha_fin_parcial = $datos_Parcial[2];
           // $idcurso = $datos_Parcial[3];
          //  $semestre = $datos_Parcial[4];
            $numParcial = $datos_Parcial[5];             

            //fecha inicio y fin de parciales

            $fecha_ini_parcial1 = cambiarFormatoFecha($fecha_ini_parcial,"/","/");
            $fecha_fin_parcial1 = cambiarFormatoFecha($fecha_fin_parcial,"/","/");        
        
        
        //-----------------------------------------------------------------------------------------------------------------
        //      caso especial de los veladores, que tienen 2 horarios para cada dia, se marca como checado si la salida es
        //      a las 12:59 o la entrada es a las 00:00 y tiene registro en el reloj del contrario (entrada o salida)
        
        // ----------cambio formato de fecha del registro----------                    
            $separar =  explode('-',$row['fecha']);

            $anio = $separar[0];
            $mes = $separar[1];
            $dia = $separar[2];

            $fecha_reg = $dia . "/" . $mes . "/" . $anio;   

            $dia_semana = diaSemana($anio,$mes,$dia); //me sirve para verificar si el maestro tiene descarga o no en parciales
            //--------------------


            //caso 1: el horario esta en dos partes de 22:00 a 23:59 y 00:00 a 06:00
            $es_velador = "";
            $registros_x_empleado ++;

            if ( $row['departamento'] == 'VELADORES') {
                    $es_velador = "SI";                    

            //echo $row['horaFin']."-".$row['checadaini1']."---".$row['horaIni']."-".$row['checadafin1']."-".$row['fecha']."<br>"; 
                    IF (($row['horaFin'] == '23:59') && ($row['checadaini1'] != "")) {
                            $row['checadafin1'] = '23:59';
                    }

                    IF (($row['horaIni'] == '00:00') && ($row['checadafin1'] != "")) {
                            $row['checadaini1'] = '00:00';
                    }        
            }

            //caso 2: el horario esta en 1 solo registro 22:00 a 06:00
           //echo  $row['horario']."sda<br>";
            $separar = explode (" ", $row['horario']);
            $horaInicial = $separar[0];
            $horaFinal = $separar[2];

            $separar = explode (":",$horaInicial);
            $hora_horaInicial = $separar[0];

            $separar = explode (":",$horaFinal);
            $hora_horaFinal = $separar[0];

            if (($hora_horaFinal < $hora_horaInicial) || ($hora_horaFinal == $hora_horaInicial )) { // ej 06:00 es menor que 22:00
               $es_velador = "SI";
               $row['horas_segun_horario'] = $row['jornadaTrabajada'] . ":00";
               $row['horas_totales'] = $row['jornadaReal'] . ":00";

               if ($row['horas_segun_horario'] == ":00") {
                       $row['horas_segun_horario'] = "";
               }
            }

            //-------------------------------------------------------------------------
            // determino si la fecha esta en la tabla de fechas que debe trabajar velador

            if ($es_velador == "SI"){    
                    $debe_trabajar = velador_debe_trabajar($row['idemp'],$fecha_reg);
                    //echo $debe_trabajar."<br>";
                    if ($debe_trabajar == ""){
                            //no le toca trabajar y no se le debe descontar
                            $row['ausente'] = "";
                            $row['difentrada'] = "";
                            $row['difsalida'] = "";
                    }
            }
                               
        //------------------------------------------------------------------------------------------------------------------- 
    

            if ($linea == 0) {
                $clase = ' class="FilaPar"';
                $linea = 1;
            } else {
                $clase = ' class="FilaImpar"';
                $linea = 0;
            }


            if ($row['departamento'] == $nomDepto) {
                $marca = " checked ";
            } else {
                $marca = "";
            }


       //-----------------------------determino si ya es nuevo empleado o no----------------------------------------------
			
            if ($idempant == "") {
                $idempant = $row['idemp'];
                $nombreant = $row['nombre'];
                $departamentoant = $row['departamento'];
                $nvo = "SI";

                $num_retardos = 0;
                $registros_x_empleado = 0;
                $minutos_x_descontar = 0; // x dia
                $total_minutos_x_descontar =0;  // x periodo seleccionado
                $total_minutos_permiso = 0;
                $es_retardo = "0";
                /***********************************************************************************/
                // minutos acumulados para administrativos   
                 $minutos_acumulados=0; //si es nuevo en la quincena inicializo a 0 //modificado el 12/03/2014
                 /*$inicioDescuentoAdmin=0;    
                 $totaldescontar=0;
                 $contarllegadatarde=0;
                 $tot_entrada=0;*/
                  $inicioDescuentoAdmin=0;    
                  $tardeveces=0;                  
                /***********************************************************************************/

                $tot_entrada = 0;
                $tot_entrada_con_gracia = 0;
                $tot_salida = 0;
                $sig_registro = ""; // es para definir si los veladores checan o no
                

            } else {
                if ($idempant != $row['idemp']) {
                    // graba numero de retardos de la quincena
                    actualiza_num_retardos($idempant, $id_periodo, $num_retardos);
                    /*****************************************************************/
					// graba en bd el num de minutos tarde de la quincena para administrativos
                    actualiza_num_minutos($idempant, $id_periodo, $minutos_acumulados); 
                    //modificado el 12/03/2014
					//imprime los totales x quincena del empleado
                    /*****************************************************************/                 
                        
                    $total_minutos_permiso_formato = convierte_mins_a_horas($total_minutos_permiso,"");
                    $total_minutos_x_descontar_formato = convierte_mins_a_horas($total_minutos_x_descontar,"");
                    $tot_entrada_formato = convierte_mins_a_horas($tot_entrada,"");
                    $tot_entrada_con_gracia_formato = convierte_mins_a_horas($tot_entrada_con_gracia,"");
                    $tot_salida_formato = convierte_mins_a_horas($tot_salida,"");     
                    
                                       
                    $idempant = $row['idemp'];
                    $nombreant = utf8_encode($row['nombre']);
                    $departamentoant = utf8_encode($row['departamento']);
                    $nvo = "SI";                
                    echo '<tr align="right"  ' . $clase . '>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td> 
                                <td align="center" style="font-size:15px;"></td> 
                                <td align="center" style="font-size:15px;"></td>
                                <td align="center" style="font-size:15px;"></td>   
                                <td align="center" style="font-size:15px;"><b>TOTAL</b></td>     
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                                        $total_minutos_permiso_formato . '</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                                        $total_minutos_x_descontar_formato . '</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px;"><b>' . 
                                        $tot_entrada_formato . '</b></td>              
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                                        $tot_entrada_con_gracia_formato . '</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                                        $tot_salida_formato . '</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"></td>  
                                            
                              <td ><b></b></td> 
                    </tr>';                               

                    echo '</table>'; 
                    //modificado el 12/03/2014
                    /*if ($tipo_empleado == 3){
						  // es administrativo
                          echo 'minutos E/S fuera de horario:'.$minutos_acumulados.'---'.convierte_mins_a_horas($minutos_acumulados,"").'<br>';  
                      
                      } */
                  ///////////    
                    echo '<table width="100%" border="0" align="center" bgcolor="#FFFFFF">';
                    
                }else{
                    $nvo = "";
                }
            }

            if ($nvo == "SI") {

                //---------------------------------------------------------------------------------------------
                //    verifico retardos previos si $reinicio_contador = 0
                $cant_retardos = cant_retardos($idempant, $reinicio_contador, $id_periodo);  
                
                /*****************************************************************/
				
            
				// verifico si tiene minutos acumulados  para administrativos			               
            	//modificado el 12/03/2014   
                //echo "min acumulados previos: ".$cminutos_acumulados."<br>";
				
                 /*****************************************************************/      
                             
                //--------------------------------------------------------------------------------------------------
                //obtengo el tipo de empleado
                $sente5 = "SELECT idTipo FROM empleado where idEmp =". $idempant;
                $result5 = mysqli_query($cn,$sente5) ;    
                $row5 = mysqli_fetch_array($result5);
                $tipo_empleado = $row5['idTipo'];
               
              
                $cminutos_acumulados=cant_minutos($idempant, $reinicio_contador_minutos, $id_periodo);  
             

                $sente6 = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
                $result6 = mysqli_query($cn,$sente6) ;    
                $row6 = mysqli_fetch_array($result6);
                $tipo_empleado_desc = utf8_encode($row6['Descripcion']);   

                $SQLdepartamento="SELECT d.idDepto,d.Nombre as Depto FROM empleado e
                     LEFT JOIN departamento d  ON d.idDepto=e.idDepto WHERE idEmp='".$idempant."'";
                     $query=mysqli_query($cn,$SQLdepartamento);
                     $departo=mysqli_fetch_array($query);
                     $exitD=mysqli_num_rows($query);
                     if($exitD>0)
                          $departamentoant =$departo['Depto']; 
                      else
                        $departamentoant= 'POR CONFIRMAR';

                echo '<td>&nbsp&nbsp;</td>';            
                echo '<table width="100%" border="1" align="center" bgcolor="#08088A">';
                echo '<tr align "left"><td align="left"><font color="#FFFFFF" size="3"><b>' . 
                         utf8_encode($nombreant)  . " [" . $idempant . "] " . utf8_encode($departamentoant)  . " -   " . $tipo_empleado_desc . '</b></font></td>';
                echo '</table>';

                echo '<table width="100%" border="0" align="center" bgcolor="#FFFFFF">';
                echo '<tr align="right"' . $clase . '>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Fecha Registro</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>D&iacute;a</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Horario</b></td>                          
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Registro Entrada</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Registro Salida</b></td> 
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Entrada Tarde</b></td> 
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Salida Temprano</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hrs. Trab. S/Horario</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Total Hrs. Trabajadas</b></td> 
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Ausente</b></td>                        
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Permiso</b></td>                        
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Descuento</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Retardo</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Mins Ent</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Toleracia</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Mins Sal</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Observ.</b></td>
                      </tr>';                         
                $num_retardos = $cant_retardos;
                $registros_x_empleado = 0;
                $total_minutos_x_descontar = 0;
                $total_minutos_permiso = 0;
                $es_retardo = "0";
                $numero_empleados += 1;    
                $tot_entrada = 0;
                $tot_entrada_con_gracia = 0;
                $tot_salida = 0; 
                
                
                $minutos_acumulados=$cminutos_acumulados;
                 $inicioDescuentoAdmin=0;    
                 $tardeveces=0;
               

            }        

            //--------------------------------------------------------------------------------------------------------------------
            // determino si hay o no descuento de minutos x registro
                //$num_retardos = 0;
                //$minutos_x_descontar = 0; // x dia
                //$total_minutos_x_descontar =0;  // x periodo seleccionado 
            $minutos_x_descontar = 0;
            $es_retardo = "0";
            $es_retardo_web = "";
            $dif_minutos_entrada = "";
            $dif_minutos_salida = 0;
            $total_minutos_ausente = 0;
            $total_minutos_entrada = 0;
            $total_minutos_salida = 0;
            $mins_permiso = 0;
            
         	  
            //------------------------------------------------------------------------------------------
            
            //--------------------- el maestro estubo en parcial --------------------
            // verfica si el maestro esta en la lista de profesores que cuidaron parcial
            // y si el horario de parcial coincide con su horario teorico de entrada
            // ya que hay maestros que vienen varias veces al dia.
            // si el maestro aparece, solo se verifica su entrada, mas no su salida.
            
            //marca_parcial = par - parciales, ord - ordinarios, rev - revisiones examenes
            
            //    ciclo para verificar si cuido o no parcial. Los datos se extraen de SISCAP
            $verifica_salida = "S";
            $verifica_entrada = "S";
            $marca_parcial = "";
            $fecha_reg_nva = str_replace ("-","/",$row["fecha"]); //formato aaaa/mm/dd
            
            //no se toman en cuenta los domingos
            if ($dia_semana >= 1 && $dia_semana <= 6){ 
            
                //echo "<tr><td>Parcial: ".$idParcial."</td></tr>";
                if ($idParcial > 0){

                   // $fecha_reg_nva = str_replace ("-","/",$row["fecha"]);                    
                    if (($fecha_reg_nva >= $fecha_ini_parcial1  &&  $fecha_reg_nva <= $fecha_fin_parcial1) && ($row['departamento'] == "PROFESORES" || $tipo_empleado == 1 )){

                      //  echo "<tr><td>".$fecha_ini_parcial1." - ". $fecha_fin_parcial1 . " - " . $row['departamento'] . " - " . $tipo_empleado_desc . "</td></tr>";
                        
                        $verifica_salida = "N";
                        $row['difsalida'] = "00:00";
                        //iam29Ago17--$marca_parcial = "Par";
                        $marca_parcial = "cuido Parcial";
                        
                        if ($numParcial == 3){
                            //iam29Ago17--$marca_parcial="Ord";
                            $marca_parcial="cuido Ordinario";
                        }
                        
                        if ($numParcial == 4){
                            //iam29Ago17--$marca_parcial="Rev";
                            $marca_parcial="Revision examenes";
                            $verifica_entrada = "N";
                        }                        
                    }

                    $sente_p="select numAula, numSemestre, hora_ini, hora_fin from maestros_parciales
                              where idemp = " .$row['idemp'] . " and fecha = '" . $fecha_reg. 
                              "' and hora_ini = '" . $row['horaIni'] . "'";

                   // echo "<tr><td>".$row['fecha']." * " .$fecha_ini_parcial ."</td></tr>";

                    $result_p = mysqli_query($cn,$sente_p) ; 
                    $row_p = mysqli_fetch_array($result_p);
                    
                    $hora_ini_parcial = "";

                    if ($row_p){

                        $hora_ini_parcial = $row_p['hora_ini'];

                        $verifica_salida = "N";                   
                        $row['difsalida'] = "00:00";
                        $marca_parcial = "cuido Parcial";
                        
                        if ($numParcial == 3){
                            //iam30Ago17--$marca_parcial="C-Ord";
                            $marca_parcial="cuido Ordinario";
                        }                        
                        
                    }                               

                    //verifico si el maestro tiene descarga durante parciales
                    //iam29Ago17--if ($marca_parcial == "C-Par" || $marca_parcial == "Par" || $marca_parcial == "C-Ord" || $marca_parcial == "Ord" || $marca_parcial == "Rev"){
                    if ($marca_parcial == "cuido Parcial" || $marca_parcial == "Parcial" || $marca_parcial == "cuido Ordinario" || $marca_parcial == "Ordinario" || $marca_parcial == "Revision examenes"){
                        $sente_p="select hora_ini, hora_fin from maestros_descarga
                                  where idemp = " . $row['idemp'] . " and dia= " . $dia_semana .
                                 " and idcurso = " . $idcurso . " and semestre = " . $semestre . 
                                 " and hora_ini = '" . $hora_ini_parcial . "'";                            

                        $result_p = mysqli_query($cn,$sente_p) ; 
                        $row_p = mysqli_fetch_array($result_p);

                        if ($row_p){
                            //el maestro tiene descarga y aunque haya checado no se le verifica su entrada
                            //ya que no tiene porque venir
                            $verifica_entrada = "N";
                        }    
                    }
                }
            }
            
            //-------------------------------------------------------------------------------------------------------			
            //----------------verificaciones especiales por votaciones. --------------------------
            //-----------(a los profesores no se les verifica salida, solo entrada ---------------       
            if (($fecha_reg_nva == '2012/11/23') && ($row['departamento'] == "PROFESORES" || $tipo_empleado == 1 )){
                        $verifica_salida = "N";
                        $row['difsalida'] = "00:00";
                        //iam29Ago17--$marca_parcial = "Elec";   
                        $marca_parcial = "asistio Elecciones";                                    
            }
                        
            // --------------------------------------- ausencia -------------------------------------------
            if ($row['ausente']== "Y" ){
                
                //se justifica la ausencia de todos aquellos profesores que no vinieron por parciales.
                if (($idParcial > 0  &&  $fecha_reg_nva >= $fecha_ini_parcial1  &&  $fecha_reg_nva <= $fecha_fin_parcial1) && ($row['departamento'] == "PROFESORES" || $tipo_empleado == 1)){
                    //echo "<tr><td>".$row['fecha']." * " .$fecha_ini_parcial ." * " . $tipo_empleado . "</td></tr>";
                    $ausente = "SI";
                    $total_minutos_ausente = 0;
                    //iam29Ago17--$marca_parcial = "Parc";
                    $marca_parcial = "cuido Parcial";
                    
                    if ($numParcial == 3){
                        //iam29Ago17--$marca_parcial="Ord";
                        $marca_parcial="cuido Ordinario";
                    }                      

                    if ($numParcial == 4){
                        //iam29Ago17--$marca_parcial="Rev";                        
                        $marca_parcial="Revision examenes";                        
                    }                       
                    
                }else {
                    // Se justica la ausencia de todos aquellos profesores que no vinieron el día de las elecciones
                    if (($fecha_reg_nva == '2012/11/23') && ($row['departamento'] == "PROFESORES" || $tipo_empleado == 1 )){
                        $ausente = "SI";
                        $total_minutos_ausente = 0;
                        //iam29Ago17--$marca_parcial = "Elec";                        
                        $marca_parcial = "asistio Elecciones";                        
                        
                    } else {                                                           
                        $ausente = "SI";
                        $minutos_x_descontar_formato_hora = date("H:i", strtotime("00:00") + strtotime($row['horaFin']) - strtotime($row['horaIni']) );
                        $horas = 0;
                        $minutos = 0;            
                        $row['horas_totales'] = "00:00:00";

                        $separar = explode(':',$minutos_x_descontar_formato_hora);
                        $horas = $separar[0];
                        $minutos = $separar[1]; 
                        $total_minutos_ausente = ($horas*60)+$minutos;
                    }
                }

            }else{
                $ausente = "";
            }        
            //periodos adicionales al trabajo por ejemplo propedeutico o induccion        
            //echo "<tr><td>".$fecha_reg."</td><td>".$dia_semana."</td><td>".$idempant."</td><td>".$idcurso."</td><td>--".$semestre."</td></tr>";
           
            $fecha_registro = $row['fecha']; //aaaa-mm-dd;
            
            $verifico_checada = dia_que_debe_laborar($fecha_registro,$dia_semana,$idempant ,$idcurso,$semestre);
            
            if ($verifico_checada == ""){
                //aunque el empleado checo, esta fuera del horario establecido en el contrato
                $verifica_entrada = "";
                $verifica_salida = "";
                $horario_teorico_entrada = "";
                $horario_teorico_salida = "";
                $total_minutos_entrada = 0;
                $total_minutos_salida = 0;
                $total_minutos_ausente = 0;
                //iam29Ago17--$marca_parcial ="Adic";
                $marca_parcial ="periodo Adicional";
            }
            
            //  -------------------------------entrada--------------------------------------
            $separar = explode(':',$row['difentrada']);
            $es_negativo_e = strpos($separar[0],"-");
            $horas_entrada = "00";
            $minutos_entrada = "00";

            //cuando hay parciales y el profesor cuida examen no se verifica su entrada.
            if ($verifica_entrada == "S"){

                //si el maestro cuida examen
                //iam29Ago17--if ($marca_parcial == "Par" || $marca_parcial == "Ord"){
                if ($marca_parcial == "cuido Parcial" || $marca_parcial == "cuido Ordinario"){
                    $row['difentrada'] = "00:00";
                }
                else {
                    //asigno el valor de hora y minutos si tiene valor
                    if (strlen($row['difentrada']) > 0 ){
                        $horas_entrada = $separar[0];
                        $minutos_entrada = $separar[1];            
                    }

                    // le quito a la variable de horas el negativo
                    if (substr($horas_entrada,0,1) == "-"){            
                        $es_negativo_e = true; // son retardos
                        $horas_entrada = substr($horas_entrada,1,2);
                        $total_minutos_entrada = ($horas_entrada*60)+$minutos_entrada;
                        $dif_minutos_entrada = $row['difentrada'];
                    }else{
                        $es_negativo_e = false; // son entradas temprano
                        $total_minutos_entrada=0;
                        $dif_minutos_entrada = "";
                    }        
                }    
            }  
            
            //  ----------------------------salida-----------------------------------------                            
            $separar = explode(':',$row['difsalida']);
            $es_negativo_s = strpos($separar[0],"-");
            $horas_salida = "00";
            $minutos_salida = "00";        
            $total_minutos_salida = 0;
                
            //cuando hay parciales y el profesor cuida examen no se verifica su salida.
            if ($verifica_salida == "S"){
                
                //asigno el valor de hora y minutos si tiene valor
                if (strlen($row['difsalida']) > 0 ){
                    $horas_salida = $separar[0];
                    $minutos_salida = $separar[1];            
                }  

                // le quito a la variable de horas el negativo
                if (substr($horas_salida,0,1) == "-"){            
                    $es_negativo_s = false; // son salidas despues de horario
                    $total_minutos_salida = 0;
                    $dif_minutos_salida = "";

                }else{
                    $es_negativo_s = true; // son salidas temprano
                    $horas_salida = substr($horas_salida,0,2);
                    $total_minutos_salida = ($horas_salida*60)+$minutos_salida;
                    $dif_minutos_salida =  $row['difsalida'];
                }        

                //eliminando asignando "" si la variable tiene 00:00:00
                if ($dif_minutos_entrada == "00:00:00"){
                    $dif_minutos_entrada = "";
                }

                if ($dif_minutos_salida == "00:00:00"){
                    $dif_minutos_salida ="";
                }
                $tot_salida += $total_minutos_salida;  
                          
            }
            
            //----------------------------------------------------------------------------------------------------------------
            //    obtengo informacion de los permisos para ese dia y ese empleado   
            //
            $ubic_permiso = "";
            $mins_permiso = 0;   
            
            $permiso_info = array(); //(0- hora inicial, 1- hora final, 2- minutos permiso)
            
            //removerArreglos($permiso_info,$cont);
           
            $cont = 0;
            
            // permisos por todo el$permiso_info[][] = 0; dia: de 00:01 a 23:59 y el usuario esta ausente
			/**************************************
			query modificado 03/05/2013
			original :
			$sente1 = "select idemp, fechaini as fechaini1, fechafin as fechafin1," .
                          " horaini, horafin, tipo, motivo, horaCaptura, minutosDiarios" .    
                          " from permisos where fechaini >= '" . $fecha_reg .
                          "' and fechafin <= '" . $fecha_reg .
                          "' and horaini = '00:01' " .
                          " and horafin = '23:59' " .
                          " and idemp = " . $row['idemp'] ;			 			 
			 */
            if ($ausente == "SI"){
                $sente1 = "select idemp, fechaini as fechaini1, fechafin as fechafin1," .
                          " horaIni, horaFin, tipo, motivo, horaCaptura, minutosDiarios" .    
                          " from permisos where fechaini = '" . $fecha_reg .
                          "' and fechafin = '" . $fecha_reg .
                          "' and horaIni = '00:01' " .
                          " and horaFin = '23:59' " .
                          " and idemp = " . $row['idemp'] ;              
                $result1 = mysqli_query($cn,$sente1) ; 
								
				//echo $sente1."-- query ausente-<br>";         
                //$row1 = mysql_fetch_array($result1); 

               // $mins_permiso = 0;
                
                $permiso_hora_ini = 0;
                $permiso_hora_fin = 0;
                
                while ($row1 =mysqli_fetch_array($result1)) {
                    $nombre_dia = nombre_dia ($dia_semana);
                //se agrega para cambiar formato de fecha 
                
                list($dia, $mes, $año) = explode('/', cambiafnormal($fecha_registro)); 
                $fechaMuestra=$dia."-".mes($mes)."-".$año; 

               
                $diaSemV=convertirDia($nombre_dia);
                $horarioV=$row['horaIni']." A ".$row['horaFin'];
                //VERIFICAMOS SI ES SU HORARIO EL TEORICO
                $SQLhorarioWeb="SELECT * FROM horarios_semestre 
                WHERE idEmp='".$idempant."' AND id_dia='".$diaSemV."' 
                AND fecha_ini='07/08/2017' AND fecha_fin='31/12/2017' 
                AND horario='".$horarioV."'";
                $queryD=mysqli_query($cn,$SQLhorarioWeb);
                $existe=mysqli_num_rows( $queryD);
                if($existe>0){
                    //guardo todo en un arreglo
                   
                    $permiso_info[$cont][0] = $row1['horaIni'];
                    $permiso_info[$cont][1] = $row1['horaFin'];
                    $permiso_info[$cont][2] = $row1['minutosDiarios'];
                    
                    $cont++;
                    
                    $mins_permiso += $row1['minutosDiarios'];

                    $permiso_hora_ini =  $row1['horaIni'];
                    $permiso_hora_fin =  $row1['horaFin'];
                
                } 
            }

            }            
            
            // permisos con horario establecido
            // el permiso puede estar entre el horario
            // caso 1: permiso hrinicial >= horario ini y permiso hrfinal <= horario fin
            // caso 2: permiso hrinicial <= horario ini y permiso hrfinal <= horario fin
            // caso 3: permiso hrinicial <= horario ini y permiso hrfinal >= horario fin
            // caso 4: permiso hrinicial >= horario ini y permiso hrinicial <= horario fin para que el permiso no se salga del rango.
            /************************************/
			/* query modificado 03/05/2013 
			original :
			$sente1 = "select idemp, fechaini as fechaini1, fechafin as fechafin1," .
                      " horaini, horafin, tipo, motivo, horaCaptura, minutosDiarios" .    
                      " from permisos where fechaini >= '" . $fecha_reg .
                      "' and fechafin <= '" . $fecha_reg .
                      "' and ((horaini >= '" . $row['horaini'] . "' and horafin <= '" . $row['horafin'] . 
                      "') or (horaini<='" . $row['horaini'] . "' and horafin <= '" . $row['horafin'] . 
                      "') or (horaini <= '" . $row['horaini'] . "' and horafin >= '" . $row['horafin'] .
                      "') or (horaini >= '" . $row['horaini'] . "' and horaini <= '" . $row['horafin'] . "'))" .                    
                      " and idemp = " . $row['idemp'] ;     
			*/
			/*******************************/
            $sente1 = "select idemp, fechaini as fechaini1, fechafin as fechafin1," .
                      " horaIni, horaFin, tipo, motivo, horaCaptura, minutosDiarios" .    
                      " from permisos where fechaini = '" . $fecha_reg .
                      "' and fechafin = '" . $fecha_reg .
                      "' and ((horaIni >= '" . $row['horaIni'] . "' and horaFin <= '" . $row['horaFin'] . 
                      "') or (horaIni<='" . $row['horaIni'] . "' and horaFin <= '" . $row['horaFin'] . 
                      "') or (horaIni <= '" . $row['horaIni'] . "' and horaFin >= '" . $row['horaFin'] .
                      "') or (horaIni >= '" . $row['horaIni'] . "' and horaIni <= '" . $row['horaFin'] . "'))" .                    
                      " and idemp = " . $row['idemp'] ;              
            $result1 = mysqli_query($cn,$sente1) ;          

          // echo $sente1."--- query permisos<br>";           
            
            while ($row1 =mysqli_fetch_array($result1)) {  

                //guardo todo en un arreglo
                $nombre_dia = nombre_dia ($dia_semana);
                //se agrega para cambiar formato de fecha 
                
                list($dia, $mes, $año) = explode('/', cambiafnormal($fecha_registro)); 
                $fechaMuestra=$dia."-".mes($mes)."-".$año; 

               
                $diaSemV=convertirDia($nombre_dia);
                $horarioV=$row['horaIni']." A ".$row['horaFin'];
                //VERIFICAMOS SI ES SU HORARIO EL TEORICO
                $SQLhorarioWeb="SELECT * FROM horarios_semestre 
                WHERE idEmp='".$idempant."' AND id_dia='".$diaSemV."' 
                AND fecha_ini='07/08/2017' AND fecha_fin='31/12/2017' 
                AND horario='".$horarioV."'";
                $queryD=mysqli_query($cn,$SQLhorarioWeb);
                $existe=mysqli_num_rows( $queryD);
                if($existe>0){
				
                $permiso_info[$cont][0] = $row1['horaIni'];
                $permiso_info[$cont][1] = $row1['horaFin'];
                $permiso_info[$cont][2] = $row1['minutosDiarios'];
                    
                $cont++;
                
                $mins_permiso += $row1['minutosDiarios'];
                
                $permiso_hora_ini =  $row1['horaIni'];
                $permiso_hora_fin =  $row1['horaFin'];   
               }             
            }                    

             $total_minutos_permiso += $mins_permiso; 
           //----------------------------------------------------------------------------------------------------------------
  

            //-------------- proceso para verificar SALIDAS ----------------------------------
            // descuento todas las salidas antes de horario. no cuentan para la tolerancia de las entradas
            //$total_minutos_salida_grabar = 0;            
            if ($total_minutos_salida > 0){
                $es_retardo = "S";
                $es_retardo_web = "S";
            }         
           

            //------------------proceso para verificar ENTRADAS ----------------------------------------
            // si el registro de entrada es despues de la hora establecida se toman en cuenta
            // y no esta ausente
            $minutos_x_descontar_e = 0;

            if ($total_minutos_entrada > 0){ //originalmente !=

                //Tipo Maestro: no importa numero de retardos, a partir de minuto 6 se 
                //descuenta total de minutos - 5. Despues de 20 minutos de retardo, 
                //se descuenta 1 hr    
                if ($tipo_empleado == 1){
                    if ($total_minutos_entrada > 5){
                        $num_retardos += 1;
                        $es_retardo = "E";

                        if ($es_retardo_web == "S") {
                            $es_retardo_web = "E/S";
                        }

                        if ($total_minutos_entrada > 20) {
                            $minutos_x_descontar_e = 60;
                            if ($total_minutos_entrada > $minutos_x_descontar_e){
                                $minutos_x_descontar_e = $total_minutos_entrada; // si la entrada tarde es mayor a 1hr se le descuenta todos los minutos
                            }
                            
                        }else{
                            $minutos_x_descontar_e = $total_minutos_entrada - 5;
                        }
                    }
                }

                //Tipo técnico: no importa numero de retardos, a partir de minuto 6
                // se descuenta total minutos - 5 mins  
                if ($tipo_empleado == 2){
                    if ($total_minutos_entrada > 5){
                        $num_retardos += 1; 
                        $es_retardo = "E";

                        if ($es_retardo_web == "S") {
                            $es_retardo_web = "E/S";
                        }
                        $minutos_x_descontar_e = $total_minutos_entrada - 5;
                    }     
                }

                //Tipo administrativo-profesionista: 6 retardos al mes. 15 mins tolerancia
                // x vez. Durante esas 6 veces se descuenta a partir de minuto 16.
                // Despues de los 6 se descuentan todos los minutos.  
                
               /* if ($tipo_empleado == 3){
                    if ($total_minutos_entrada > 0){
                        $num_retardos += 1;
                        //$es_retardo = "1";
                        if ($num_retardos > 6){
                            $es_retardo = "E";
                            if ($es_retardo_web == "S") {
                                $es_retardo_web = "E/S";
                            }else{
                                $es_retardo_web = "E";
                            }


                            $minutos_x_descontar_e = $total_minutos_entrada;
                           
                        }else {
                            if ($total_minutos_entrada > 15){
                                $es_retardo = "E";

                                if ($es_retardo_web == "S") {
                                    $es_retardo_web = "E/S";
                                }else{
                                    $es_retardo_web = "E";
                                }                            

                                $minutos_x_descontar_e = $total_minutos_entrada - 15; 
                                $minutos_acumulados = $minutos_x_descontar_e; //modificado el 12/03/2014
                            }                
                        }  
                    }
                }*/
                
                 //Tipo administrativo-profesionista: Sin mins tolerancia                
                // Despues de los 90 acumulados al mes se descuentan todos los minutos a partir del minuto 91.  
				// Elaborado el 18/03/2014
                //WALTER
                $min_acumulados_ahora=0;
                
                if ($tipo_empleado == 3){
                      //echo $row['fecha']."-".$minutos_x_descontar_e."<br>";
                    if ($total_minutos_entrada > 0){                             
                                             
                          //minutos acumulados registro anterior excede los 90 minutos
                            $es_retardo = "E";
                            if ($es_retardo_web == "S") {
                                $es_retardo_web = "E/S";
                            }else{
                                $es_retardo_web = "E";
                            }
                            $minutos_x_descontar_e = $total_minutos_entrada;
                           //echo "* min_acumulados anteriores  > 90=".$minutos_acumulados."  **  ";
                           
                            $minutos_acumulados += $total_minutos_entrada;
                            //echo "min_acumulados_ahora=".$minutos_acumulados."<br>";
                            
                        
                    }
                }
            }            

            $tot_entrada += $total_minutos_entrada;
            $tot_entrada_con_gracia += $minutos_x_descontar_e;


            //----------------------------------------------------------------------------------------
            if (($es_velador == "SI" && $debe_trabajar == "SI") || ($es_velador == "")){
            // se contabilizan los minutos para descontar a todos y a los veladores cuando si deben checar
                
                //$minutos_x_descontar = $minutos_x_descontar_e + $total_minutos_salida + $total_minutos_ausente;//- $mins_permiso;
                
                if ($mins_permiso >= $minutos_x_descontar) {
                    $minutos_x_descontar = 0;
                    //$total_minutos_ausente = 0; // acabo de agregar OJO!!!!!!
                    $es_retardo_web = "";
                }else {
                    $minutos_x_descontar -= $mins_permiso;                   
                    
                }
                
                //$total_minutos_x_descontar = $total_minutos_x_descontar + $minutos_x_descontar ;// - $total_minutos_permiso;
            }
            
            //---------------------------------------------------------------------------
            //se eliminan los descuentos en aquellos dias que no debe checar
                if ($es_velador == "SI" && $debe_trabajar == ""){
                    
                    $minutos_x_descontar= 0;
                    $minutos_x_descontar_e =0;
                    $es_retardo_web = "";                                    
                }                       
            
            //establece una marca cuando el maestro cuido parcial    
            if ($marca_parcial == "*"){
                $tms = $marca_parcial;
            }else{
                $tms = $total_minutos_salida;
            }
            
            //-----------------------------------------------------------------------------------------------------
            //Si hay minutos por descontar, entonces verifico de que tipo son y grabo en temporal
            
                // determino si el permiso fue para la entrada "E", la salida "S" o ninguna de las dos "N"
                $total_minutos_entrada_grabar = $minutos_x_descontar_e;
                $total_minutos_salida_grabar = $total_minutos_salida;
		        $ubic_permiso = "";
		//echo "<tr><td>PerIni: ".$permiso_info[$a][0]."-Ent:".$row['horaIni']."-PerFin:".$permiso_info[$a][1]."-Sal:".$row ['horaFin']."</td></tr>";

                //ciclo para verificar si el permiso es de entrada o salida
                for ($a=0; $a<$cont; $a++){
                    
                    $mins_permiso_array = intval($permiso_info[$a][2]); //pasa de caracter a numerico
                    
                  //  echo "<tr><td>fecha: ".$fecha_reg." PerIni: ".$permiso_info[$a][0]."-Ent:".$row['horaIni']."-PerFin:".$permiso_info[$a][1]."-Sal:".$row ['horaFin']."</td></tr>";
                    
                    if ($mins_permiso_array > 0){  //($mins_permiso > 0){
                        if  ($permiso_info[$a][0] ==  $row['horaIni']) { //($permiso_hora_ini == $row['horaIni']) {
                            $ubic_permiso = "E";
                            $total_minutos_entrada_grabar = $minutos_x_descontar_e - $permiso_info[$a][2]; //$mins_permiso;
                        }else{
                            if ($permiso_info[$a][1] == $row ['horaFin']) { //$permiso_hora_fin == $row ['horaFin']) {
                                $ubic_permiso = "S";
                                $total_minutos_salida_grabar = $total_minutos_salida - $mins_permiso_array; // $mins_permiso;
                            }else{
                                if (($permiso_info[$a][1] != $row ['horaFin']) || ($permiso_info[$a][0] != $row ['horaFin'])) {
                                    // si $ubic_permiso = "N" ubico el mayor retardo (entrada o salida y a ese le resto los minutos de permiso
                                    $ubic_permiso = "N";
									
                                    if ($total_minutos_salida_grabar >= $total_minutos_entrada_grabar ){
                                        $total_minutos_salida_grabar = $total_minutos_salida - $mins_permiso_array; 
										
                                    }else{
                                          $total_minutos_entrada_grabar = $minutos_x_descontar_e - $mins_permiso_array; //$mins_permiso;                            
                                    }                                
                                }
                            }
                        }
                    }
                }
               // echo "NNN".$total_minutos_entrada_grabar; //$mins_permiso;
  //echo $row['fecha']."<br>"; 
                $nombre_dia = nombre_dia ($dia_semana);
                //se agrega para cambiar formato de fecha 
                
                list($dia, $mes, $año) = explode('/', cambiafnormal($fecha_registro)); 
                $fechaMuestra=$dia."-".mes($mes)."-".$año; 

               
                $diaSemV=convertirDia($nombre_dia);
                $horarioV=$row['horaIni']." A ".$row['horaFin'];
                //VERIFICAMOS SI ES SU HORARIO EL TEORICO
                $SQLhorarioWeb="SELECT * FROM horarios_semestre 
                WHERE idEmp='".$idempant."' AND id_dia='".$diaSemV."' 
                AND fecha_ini='07/08/2017' AND fecha_fin='31/12/2017' 
                AND horario='".$horarioV."'";
                $queryD=mysqli_query($cn,$SQLhorarioWeb);
                $existe=mysqli_num_rows( $queryD);
                if($existe>0){
                    //echo  $row['fecha']."--".$minutos_x_descontar."-".$total_minutos_permiso."-".$total_minutos_ausente."-".$total_minutos_entrada_grabar."--".$total_minutos_salida_grabar."<br>";
        if ($ausente == "SI" AND $tipo_empleado == 3) {    
                    if ($total_minutos_ausente > 0) {
                         $SQLdepartamento="SELECT d.idDepto,d.Nombre as Depto FROM empleado e
                     LEFT JOIN departamento d
                     ON d.idDepto=e.idDepto WHERE idEmp='".$row['idemp']."'";
                     $query=mysqli_query($cn,$SQLdepartamento);
                     $departo=mysqli_fetch_array($query);
                     $exitD=mysqli_num_rows($query);
                     if($exitD>0)
                          $depto =$departo['Depto']; 
                      else
                        $depto= 'POR CONFIRMAR';

                         $senteP = "select idemp, fechaini as fechaini1, fechafin as fechafin1,
                        horaIni, horaFin, tipo, motivo, horaCaptura, minutosDiarios    
                        from permisos where fechaini = '" .cambiafnormal($fecha_registro)."' AND idemp = '" . $row['idemp']."'" ;              
                    $resultP= mysqli_query($cn,$senteP) ;  
                    $queryPermiso=mysqli_num_rows($resultP); 
              
                    if($queryPermiso==0)  {
                             $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario," .
                              "ausente,minDescuento, minPermiso,observaciones) " .
                               "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $depto . "','" . $row['fecha'] . "','" .
                                    $row['horario'] . "','" .$ausente  . "'," . 
                                    $total_minutos_ausente . ",'" . $mins_permiso . "','".$marca_parcial."')";
                            $result1 = mysqli_query($cn,$sente1) ; 
                       
                            }             
                        }     
                    } 


                    if ($total_minutos_permiso >= $total_minutos_ausente ){
                        $total_minutos_ausente = 0;                   

                    }
                    
                    if ($total_minutos_entrada_grabar < 0){
                        $total_minutos_entrada_grabar = 0;
                    }
                    if ($total_minutos_salida_grabar < 0){
                        $total_minutos_salida_grabar = 0;
                    }                
                
                $minutos_x_descontar = $total_minutos_entrada_grabar + $total_minutos_salida_grabar + $total_minutos_ausente;
				
                $total_minutos_x_descontar += $minutos_x_descontar ;
              }

             $row['fecha']."--".$ausente."-".$tipo_empleado."-".$total_minutos_ausente ."<br>";
                
                /***********************************************************************************/	
                // Se utilizaba en caso de que se incluyan los minutos de salida y ausencia dentro de los 90 min
                // modificado el 21/03/2014                        
                //$minutos_acumulados += $total_minutos_salida_grabar + $total_minutos_ausente;  
                // Si es administrativo y hay minutos de salida                         
                // if ($tipo_empleado == 3){				
//               if (( $minutos_x_descontar_e == 0) && ($minutos_acumulados > 90) && ($total_minutos_salida_grabar > 0)){
//                  // echo "----- los acumulados con salida para descontar son =".($minutos_acumulados-90)."<br>";
//				   $total_minutos_x_descontar = ($minutos_acumulados-90);                  
//				   $minutos_x_descontar = $total_minutos_x_descontar;
//				   $total_minutos_salida_grabar =  $minutos_x_descontar;				   
//                }   
//              }    
           /***********************************************************************************/
		   
           //-------------------------------------------------------------------------------------------------------
            if (($minutos_x_descontar != 0) || ($tipoRep == "2")){
                
                $mins_permiso_formato = convierte_mins_a_horas($mins_permiso,"");
                $minutos_x_descontar_formato = convierte_mins_a_horas($minutos_x_descontar,"");
                $total_minutos_entrada_formato = convierte_mins_a_horas($total_minutos_entrada,"");
                $minutos_x_descontar_e_formato = convierte_mins_a_horas($minutos_x_descontar_e,"");
                $total_minutos_salida_formato = convierte_mins_a_horas($total_minutos_salida,"");
                
                
                $nombre_dia = nombre_dia ($dia_semana);
                //se agrega para cambiar formato de fecha 
                
                list($dia, $mes, $año) = explode('/', cambiafnormal($fecha_registro)); 
                $fechaMuestra=$dia."-".mes($mes)."-".$año; 

             
                $diaSemV=convertirDia($nombre_dia);
                $horarioV=$row['horaIni']." A ".$row['horaFin'];
                //VERIFICAMOS SI ES SU HORARIO EL TEORICO
                $SQLhorarioWeb="SELECT * FROM horarios_semestre 
                WHERE idEmp='".$idempant."' AND id_dia='".$diaSemV."' 
                AND fecha_ini='07/08/2017' AND fecha_fin='31/12/2017' 
                AND horario='".$horarioV."'";
                $queryD=mysqli_query($cn,$SQLhorarioWeb);
                $existe=mysqli_num_rows( $queryD);
                if($existe>0){
                //echo $existe."-".$horarioV."SI<br>";        
                           
                           //echo $ausente."dad";
                     
                    if($tipo_empleado == 3 AND $ausente==''){  
                       //VERIFICAR SI TIENE PERMISO 
                       $senteP = "select idemp, fechaini as fechaini1, fechafin as fechafin1,
                        horaIni, horaFin, tipo, motivo, horaCaptura, minutosDiarios    
                        from permisos where fechaini = '" .cambiafnormal($fecha_registro)."' AND idemp = '" . $row['idemp']."'" ;              
                    $resultP= mysqli_query($cn,$senteP) ;  
                    $queryPermiso=mysqli_num_rows($resultP); 
                    if($queryPermiso==0)  {            
                          
                            $SELECTDes=mysqli_query($cn,"SELECT tarde,ausente, excepcion FROM registros WHERE fecha='".cambiafnormal($fecha_registro)."' AND idEmp='".$idempant."' ");
                               $arrayTarde=mysqli_fetch_array($SELECTDes); 
                               $numTarde=mysqli_num_rows($SELECTDes);
                            if($arrayTarde['tarde']=='' || $arrayTarde['excepcion']==''){

                               
                                   $tarde=$arrayTarde['tarde'];
                                   list($horat, $minutot)=explode(":", $tarde);
                                     $horat= $horat * 60;
                                     $minuTarde=$horat+$minutot;
                                    $contarllegadatarde+=$minuTarde;
                                    if($numTarde>0){
                                        $cantidad=0;
                    $cantidad=0;
                    if($id_periodo%2==0)
                        $idPeriodoAnt=$id_periodo-1;    
                    else
                        $idPeriodoAnt=$id_periodo;

                       $SQLinicio="SELECT cantidad FROM descuento_administrativos 
                      WHERE idEmp='".$idempant."' AND idPeriodo='".$idPeriodoAnt."'";
                      $queryexiste=mysqli_query($cn,$SQLinicio); 
                      $numReg=mysqli_num_rows($queryexiste);
                      if($numReg>0){
                        $rowc=mysqli_fetch_array($queryexiste);
                        //$cantidad=$rowc['cantidad']*15;

                      }
                     
                            
                                    $inicioDescuentoAdmin_ahora=0;
                                
                                    if ($total_minutos_entrada+$cantidad > 0){                             
                                        if ($inicioDescuentoAdmin+$cantidad> 90){                  
                                            $minuTarde=$minuTarde;

                                           
                                            
                                        }else {         //minuto tarde es menos a 15
                                            if($minuTarde<15){
                                                //$inicioDescuentoAdmin=$inicioDescuentoAdmin+$minuTarde;
                                                $minuTarde=0;
                                            }else{//minuto tarde es mayor a 15
                                                $minuTardeAhora=$minuTarde-15;
                                                $inicioDescuentoAdmin_ahora=$inicioDescuentoAdmin+15;

                                                if ($inicioDescuentoAdmin_ahora+$cantidad<90){
                                                    $minuTarde=$minuTarde-15;
                                                    $inicioDescuentoAdmin=$inicioDescuentoAdmin+15;
                                                    //agrego una variable que cuenta cuantos minutos tarde
                                                    $tardeveces++;
                                                }
                                                else{
                                                    $tardeveces++;
                                                  $minuTardeDes=$cantidad+$inicioDescuentoAdmin_ahora-90;
                                                  $minAnt=$minuTarde;
                                                  $minuTarde=$minuTardeAhora+$minuTardeDes;
                                                    
                                                  $inicioDescuentoAdmin=$inicioDescuentoAdmin+$minAnt-$minuTarde;
                                                }
                                              }              
                                        }  
                                    }
                                    //echo $inicioDescuentoAdmin."-".$tardeveces."<br>";
                                     if($tardeveces>0){
                                     $SQLvari="SELECT * FROM descuento_administrativos
                                     WHERE idEmp='".$idempant."' AND idPeriodo='".$id_periodo."'";
                                     $queryver=mysqli_query($cn,$SQLvari);
                                     $existevari=mysqli_num_rows($queryver);
                                     if($existevari>0){
                                        $SQLupdate="UPDATE descuento_administrativos SET cantidad='".$tardeveces."' WHERE idEmp='".$idempant."' AND idPeriodo='".$id_periodo."'";
                                        $querydescuento=mysqli_query($cn,$SQLupdate); 
                                     }else{
         $SQLdescuento="INSERT INTO descuento_administrativos (idEmp,idPeriodo,cantidad) VALUES ('".$idempant."','".$id_periodo."','".$tardeveces."')";
         $querydescuento=mysqli_query($cn,$SQLdescuento);  
    //echo $tardeveces."<br>"
     }
    }   //graba numero de retardos por empleado de la quincena


                                     $totaldescontar+=$minuTarde;
                                    $minutos_x_descontar_formato=$minuTarde;
                                     $minutos_x_descontar_formato = convierte_mins_a_horas($minutos_x_descontar_formato,"");
                                     $minutos_x_descontar_e_formato = convierte_mins_a_horas($minuTarde,"");
                                }
                                
                                }
                            }//sin permiso
                            }
 $queryPermiso=0;    

 if($tipo_empleado == 3 ){ 
                       $senteP = "select idemp, fechaini as fechaini1, fechafin as fechafin1,
                        horaIni, horaFin, tipo, motivo, horaCaptura, minutosDiarios    
                        from permisos where fechaini = '" .cambiafnormal($fecha_registro)."' AND idemp = '" . $row['idemp']."'" ;              
                    $resultP= mysqli_query($cn,$senteP) ;  
                    $queryPermiso=mysqli_num_rows($resultP); 
                }
                    if($queryPermiso==0)  {  
                            
                            echo '<tr align="right"' . $clase . ' >
                            <td align="center" style="font-size:13px;">' . $fechaMuestra . '</td>
                            <td align="center" style="font-size:13px;">' . $nombre_dia . '</td>
                            <td align="center" style="font-size:14px;">' . $horario_teorico_entrada ."-" . $horario_teorico_salida . '</td>
                            <td align="center" style="font-size:14px;">' . $row['checadaini1'] . '</td>
                            <td align="center" style="font-size:14px;">' . $row['checadafin1'] . '</td> 
                            <td align="center" style="font-size:14px;">' .  substr($dif_minutos_entrada,0, -7) . '</td> 
                            <td align="center" style="font-size:14px;">' . substr($dif_minutos_salida,0, -7) . '</td>
                            <td align="center" style="font-size:14px;">' . substr($row['horas_segun_horario'] ,0, -7). '</td>   
                            <td align="center" style="font-size:14px;">' . substr($row['horas_totales'],0, -7) . '</td>
                            <td align="center" style="font-size:14px;">'.$ausente.'</td>                             
                            <td align="center" style="font-size:14px;">' . $mins_permiso_formato . '</td>                           
                            <td align="center" style="font-size:14px;">' . $minutos_x_descontar_formato . '</td>
                            <td align="center" style="font-size:14px;">' . $es_retardo_web . '</td>
                            <td align="center" style="font-size:14px;">' . $total_minutos_entrada_formato . '</td>
                            <td align="center" style="font-size:14px;">' . $minutos_x_descontar_e_formato . '</td>    
                            <td align="center" style="font-size:14px;">' . $total_minutos_salida_formato .'</td>
                            <td align="center" style="font-size:14px;">' . $marca_parcial .'</td>      
                </tr>'; 
                
                    //quito lo que se debe contar
}else{
    $total_minutos_salida_grabar=0;
}
  }
                   //pregunto a que departamento pertenece;

                    $SQLdepartamento="SELECT d.idDepto,d.Nombre as Depto FROM empleado e
                     LEFT JOIN departamento d
                     ON d.idDepto=e.idDepto WHERE idEmp='".$row['idemp']."'";
                     $query=mysqli_query($cn,$SQLdepartamento);
                     $departo=mysqli_fetch_array($query);
                     $exitD=mysqli_num_rows($query);
                     if($exitD>0)
                          $depto =$departo['Depto']; 
                      else
                        $depto= 'POR CONFIRMAR';
                     //$idDepto=$departo['idDepto'];              
                //echo convierte_mins_a_horas($total_minutos_x_descontar,"");//iam prueba
            //-------------------------------------------------------------------------------


                // inserto registro de salida descuento si aplica
				//echo "total minutos ".$total_minutos_salida_grabar."<br>";

                    if ($total_minutos_salida_grabar > 0) {
    					 
                       $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario,horaFin,checadaFin1," .
                          "difSalida,hrsTrabHorario,hrsTrabTotal,ausente,minDescuento,esRetardo,minPermiso,observaciones) " .
                           "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $depto . "','" . 
                                $row['fecha'] . "','" . $row['horario'] . "','" . $row['horaFin'] . "','" .
                                $row['checadafin1'] . "','" . $dif_minutos_salida . "','" . 
                                $row['horas_segun_horario'] . "','" . $row['horas_totales'] . "','" . $ausente  . "','" . 
                                $total_minutos_salida_grabar . "','" . $es_retardo . "','". $mins_permiso ."','".$marca_parcial."')";
                        $result1 = mysqli_query($cn,$sente1) ;
                    }

                    // inserta registro en temporal si se cuenta como falta
                    if ($total_minutos_entrada_grabar != 0) {    

                        if ($ubic_permiso == "E") {
                            $minutos_x_descontar_grabar = $minutos_x_descontar - $mins_permiso;                
                        }                

                        if ($minutos_x_descontar > 0) {
    						//echo $minutos_x_descontar."min<br>";
                           if($tipo_empleado == 3){ 
                             $total_minutos_entrada_grabar=$minuTarde;
                           }  
                     $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario,horaIni, checadaIni1," .
                              "difEntrada,hrsTrabHorario,hrsTrabTotal,ausente,minDescuento,esRetardo,minPermiso,observaciones) " .
                               "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $depto . "','" . $row['fecha'] . "','" .
                                    $row['horario'] . "','" .$row['horaIni']  ."','" . $row['checadaini1'] . "','" .
                                    $dif_minutos_entrada  . "','" . $row['horas_segun_horario'] .
                                     "','" . $row['horas_totales'] . "','" .$ausente  . "','" . 
                                    $total_minutos_entrada_grabar . "','" . $es_retardo . "','" . $mins_permiso . "','".$marca_parcial."')";
                            $result1 = mysqli_query($cn,$sente1) ;   
                        }
                    }
				
                    if ($ausente == "SI") {    
                        if ($minutos_x_descontar > 0) {
                             $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario," .
                              "ausente,minDescuento, minPermiso,observaciones) " .
                               "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $depto . "','" . $row['fecha'] . "','" .
                                    $row['horario'] . "','" .$ausente  . "'," . 
                                    $minutos_x_descontar . ",'" . $mins_permiso . "','".$marca_parcial."')";
                            $result1 = mysqli_query($cn,$sente1) ; 
                            $Num+=1;                
                        }     
                    }        
            }
    //    }
	//echo "insert: ".$sente1."<br>";
                /************************SI TIENE DESCUENTO TERCERO******/
   //echo $tardeveces."<br>";
   
    }//fin while
    //echo $tardeveces."hello<br>";
    
    actualiza_num_retardos($idempant, $id_periodo, $num_retardos);
    
    /*****************************************************************/
	// graba en bd el num de minutos tarde de la quincena para administrativos  
    actualiza_num_minutos($idempant, $id_periodo, $minutos_acumulados); 
    //modificado el 12/03/2014                        
    /*****************************************************************/  
	
    $total_minutos_permiso_formato = convierte_mins_a_horas($total_minutos_permiso,"");
   if($tipo_empleado != 3 || ($tipo_empleado == 3 && $ausente=='Y')){  
        $total_minutos_x_descontar_formato = convierte_mins_a_horas($total_minutos_x_descontar,"");
        $tot_entrada_formato = convierte_mins_a_horas($tot_entrada,"");
        $tot_entrada_con_gracia_formato = convierte_mins_a_horas($tot_entrada_con_gracia,"");
    }
    $tot_salida_formato = convierte_mins_a_horas($tot_salida,"");   

                    //--------------------------------------------------------------------------------------
if($tipo_empleado == 3 && $ausente==''){   
                           
    $total_minutos_x_descontar_formato=convierte_mins_a_horas($totaldescontar,"");
    $tot_entrada_formato = convierte_mins_a_horas($contarllegadatarde,"");
    $tot_entrada_con_gracia_formato = convierte_mins_a_horas($totaldescontar,"");
    
    

    }
       

     
   //echo "<tr><td>".$sente1 ."</td></tr>";
  
    echo '<tr align="right"' . $clase . ' border="1">
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td> 
         <td align="center" style="font-size:15px;"></td> 
         <td align="center" style="font-size:15px;"></td>
         <td align="center" style="font-size:15px;"></td>   
         <td align="center" style="font-size:15px;"></td>                         
         <td align="center" style="font-size:15px;"><b>TOTAL</b></td>
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                 $total_minutos_permiso_formato . '</b></td> 
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                 $total_minutos_x_descontar_formato . '</b></td>         
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"></td>
        <td align="center" style="font-size:15px;" bgcolor="#848484"><b>' . 
                 $tot_entrada_formato . '</b></td>              
        <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                 $tot_entrada_con_gracia_formato . '</b></td>
        <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"></td>
        <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"></td> 
                      <td><b>
                </b></td>';
  echo '      </tr><tr><td><p></td></tr>';                
  //
                       
     mysqli_free_result($result);

    
   //  mysql_free_result($result1);
     mysqli_close($cn);
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Reloj</title>
    <!-- meta info -->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="keywords" content="Aid wear" />
    <meta name="description" content="App Reloj">
    <meta name="author" content="Reloj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../adds/estilo.css" rel="stylesheet" type="text/css" />
    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

        <style type="text/css">

            body {
                background-image: url(../imagenes/fondo.gif);
            }
            .Estilo13 {font-family: "Times New Roman", Times, serif; font-size: 16px; }
            .Estilo14 {font-size: 16px}
            .Estilo15 {
                font-size: 18px;
                font-weight: bold;
            }
            .Estilo16 {color: #FF0000}            
        </style>
        <script type="text/javascript" src="../adds/lib.js">
            function regresar(){
                location.href='Menu.php';
            }                        
        </script>
        
    </head>
    <!--Comienza area de Contenido -->
    <body>

    <div class="global-wrap">
          
         <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <img src="assets/img/logo_uady-gray.png" alt="Mountain View" style="margin-left:-4px;width:170px;height:auto;">
              <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="index.php">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="listar_catalogos.php">Catálogos</a>
                </li>
                <li>
                    <a href="consultar_empleados.php">Empleados</a>
                </li>
                <li>
                    <a href="reporte_horarios.php">Horarios</a>
                </li>
                <li>
                    <a href="captura_permisos.php">Permisos</a>
                </li>
                <li>
                    <a href="importar.php">Entradas/Salidas</a>
                </li>
                <li>
                    <a href="main.php">Reportes</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">

                <!-- //////////////////////////////////
                //////////////START PAGE CONTENT/////////
                ////////////////////////////////////-->
                <div style="margin-left: -80px">
<!--728-->
       <!-- <table width="95%" border="0" align="center"> -->
       <!--archivo original-->
        <table border="0" style="text-align:center;background-color: white" width="100%">
            <form action="reporte_retardos.php" target="_blank" method="post" id="Menu" >  
            <tr >
                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Imprimir Reporte</b></h3></td>
            </tr>
            <tr>
                <td colspan="4" align="right">
                     <a href='main.php'><img src='imagen/buscar.gif' alt='Consultar Checadas' width='30' height='30' border='0' /></a>
                </td>      
            </tr>
            <tr align ="left">
                <td><b>Departamento: </b><?php echo utf8_encode($nomDepto); ?> </td> 
            </tr>
            <tr align ="left">
                <td><b>Empleado: </b><?php echo utf8_encode($nomEmp); ?> </td>                
            </tr>
            <tr align ="left">
                <td><b>Reporte seleccionado: </b><?php echo utf8_encode($nomRep); ?> </td>                
            </tr> 
            <tr align ="left">
                <td><b>Fecha inicial: </b><?php echo $fechaini; ?> </td>                
            </tr> 
            <tr align ="left">
                <td><b>Fecha final: </b><?php echo $fechafin; ?> </td>                
            </tr>
            <tr>
                <?php 
                if ($tipoRep == '1') {
                ?> 
                <td colspan="3" align="center">                   
                    <input type="submit" name="btnImprime" value="Imprimir" onClick=""></input>
                </td>
                
                <?php } ?>                     
            </tr>
            <tr>
                <td align="center">
                    <?php
                        regChecadas();
                    ?>
                </td>
            </tr>            

            </form>
       </table>

    </div>   

                 <!-- //////////////////////////////////
                //////////////END PAGE CONTENT/////////
                ////////////////////////////////////-->

                <div id="includedFooter"></div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
   
    </div>

    <!-- Scripts queries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        $(function(){
            $("#includedHeader").load("templates/header/header.html"); 
            $("#includedContent").load("assets/prueba.html"); 
            $("#includedFooter").load("templates/footer/footer.html"); 
        });

       
            $("#wrapper").toggleClass("toggled");
     
    </script>
    
</body>
</html>
