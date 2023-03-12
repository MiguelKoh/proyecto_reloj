<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();

include('conex.php');
//include('funciones_Retardos.php');

$cn = ConectaBD();
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
$id_periodo = $_SESSION["id_periodo"];

$tipoRep = $op;
if ($tipoRep == "1"){
   $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
}else{   
   $nomRep = "Registro completo de asistencias"; 
}

    //obtengo el tipo de reporte
$tipoRep = $op;
if ($tipoRep == "1"){
   $nomRep = "Registro de ausencias, retardos y salidas antes de horario";
}else{   
   $nomRep = "Registro completo de asistencias";

}

function cant_retardos ($idemp,$reinicio_contador,$id_periodo){
    $cn_a = ConectaBD();
    
    $cant_retardos = 0;
    
    $id_periodo_ant = $id_periodo-1;
    
    //    verifico retardos previos si $reinicio_contador = 0
    if ($id_periodo_ant != 0){
        if ($reinicio_contador == 0){

            //obtengo contador del periodo inmediato anterior
             $sente_a = sprintf("select cant_retardos from contador_retardos where idemp=%d and idperiodo=%d", $idemp, $id_periodo_ant);
             $result_a =  mysql_query($sente_a, $cn_a) or die(mysql_error()); 
             $row_a = mysql_fetch_array($result_a);

             while ($row_a) {
               $cant_retardos = $row_a['cant_retardos'];                    
             }                        
        }
    }
    
    //mysql_close($cn_a);
    
    return $cant_retardos;
}

function actualiza_num_retardos ($idemp, $id_periodo, $num_retardos){
    $cn_a = ConectaBD();
    
    //elimino el registro de retardos del periodo seleccionado.
    $sente_a = sprintf("delete from contador_retardos where idemp=%d and idperiodo=%d", $idemp, $id_periodo);
    $result_a = mysql_query($sente_a, $cn_a); // or die(mysql_error()); 

    // grabo el nuevo registro
    $sente_a = "insert into contador_retardos (idemp, idperiodo, cant_retardos) values(" . $idemp . "," . $id_periodo . "," . $num_retardos . ")";
    $result_a = mysql_query($sente_a, $cn_a);// or die(mysql_error()); 
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
    //creo tabla temporal para almacenar los registros

function imprimir(){
  //  echo "<script>window.open('imprimir_retardos.php?fechaini=$fechaini&fechafin=$fechafin','myNewWinsr','width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no');</script>";
}



function regChecadas() {

    $cn = ConectaBD();
    $idDepto = $_SESSION["iddepto"];
    $idEmp = $_SESSION["idemp"];
    $nomEmp = $_SESSION["nomemp"];
    $nomDepto = $_SESSION["nomdepto"];
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"];
    //$op1 = $_SESSION["op1"];
    //$op2 = $_SESSION["op2"];
    $op = $_SESSION["op"];
    
    $reinicio_contador = $_SESSION["reinicio_contador"];
    $id_periodo = $_SESSION["id_periodo"];
    
    //$tipo_empleado = "3"; //1-maestros 2-tecnicos 3-administrativos profesionista
    $numero_empleados = 0; // empleados seleccionados.     
    
    //creo tabla temporal que tendra los datos del reporte
    $sente = "DELETE FROM retardos_temp";
    $result = mysql_query($sente, $cn) ;
    
  
/*    $sente="CREATE TABLE `retardos_temp` (
          `idEmp` int(10) unsigned NOT NULL,
          `nombre` varchar(100) DEFAULT NULL,
          `departamento` varchar(100) DEFAULT NULL,
          `horario` varchar(13) NOT NULL,
          `fecha` varchar(10) NOT NULL,
          `horaIni` varchar(5) DEFAULT NULL,
          `horaFin` varchar(5) DEFAULT NULL,
          `checadaIni1` varchar(5) DEFAULT NULL,
          `checadaFin1` varchar(5) DEFAULT NULL,

          `difEntrada` varchar(5) DEFAULT NULL,
          `difSalida` varchar(5) DEFAULT NULL,

          `ausente` varchar(5) DEFAULT NULL,
          `hrsTrabHorario` varchar(5) DEFAULT NULL,
          `hrsTrabTotal` varchar(5) DEFAULT NULL,

          `minDescuento` int(5) DEFAULT 0,

          `esRetardo` varchar(1) DEFAULT NULL,

          PRIMARY KEY (`idEmp`,`fecha`,`horario`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
     
    $result = mysql_query($sente, $cn) ;
     mysql_free_result($result);
   */ 
    

    
    //cambio posicion de fecha
    $fechaini1 = implode( '-', array_reverse( explode( '/', $fechaini ) ) ) ;
    $fechafin1 = implode( '-', array_reverse( explode( '/', $fechafin ) ) ) ;

    
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
                            "horaini,horafin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaini,checadaini1) as difentrada, TIMEDIFF(horafin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horafin THEN checadafin1
                            WHEN checadafin1>horafin THEN horafin
                            ELSE horafin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaini THEN horaini
                            WHEN checadaini1>horaini THEN checadaini1
                            ELSE horaini
                            END) as horas_segun_horario," .
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .
                            " from registros where departamento = '" . $nomDepto . 
                            "' and idemp ='" . $idEmp . 
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                            "' and ((TIMEDIFF(horaini,checadaini1)<0 or TIMEDIFF(horafin,checadafin1)>0 or ausente='Y')) " .
                           // "' and TIMEDIFF(horaini,checadaini1)<0 " .
                            " order by fecha";
            }else{
                if ($tipoRep == "2"){
                    $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                                "horaini,horafin,checadaini1,checadafin1," .
                                "ausente, TIMEDIFF(horaini,checadaini1) as difentrada, TIMEDIFF(horafin,checadafin1) as difsalida," .
                                "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .                            
                                "TIMEDIFF(CASE
                                WHEN checadafin1='' THEN 0
                                WHEN checadafin1<horafin THEN checadafin1
                                WHEN checadafin1>horafin THEN horafin
                                ELSE horafin
                                END,CASE
                                WHEN checadaini1='' THEN 0
                                WHEN checadaini1<horaini THEN horaini
                                WHEN checadaini1>horaini THEN checadaini1
                                ELSE horaini
                                END) as horas_segun_horario," . 
                                "excepcion, jornadaTrabajada, attTime as jornadaReal " .                            
                                " from registros where departamento = '" . $nomDepto . 
                                "' and idemp ='" . $idEmp .                             
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 . 
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                                "' order by fecha";                
                }
            }
        } else {            
            // todos los empleados de un solo depto
            if ($tipoRep == "1"){           
                $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                            "horaini,horafin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaini,checadaini1) as difentrada, TIMEDIFF(horafin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .  
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horafin THEN checadafin1
                            WHEN checadafin1>horafin THEN horafin
                            ELSE horafin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaini THEN horaini
                            WHEN checadaini1>horaini THEN checadaini1
                            ELSE horaini
                            END) as horas_segun_horario," .     
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .                        
                            " from registros where departamento = '" . $nomDepto .                            
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                         
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                            "' and ((TIMEDIFF(horaini,checadaini1)<0 or TIMEDIFF(horafin,checadafin1)>0 or ausente='Y')) " .
                            //"' and TIMEDIFF(horaini,checadaini1)<0 " .                            
                            " order by idemp, fecha";
            }else{
                if ($tipoRep == "2"){
                    $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                                "horaini,horafin,checadaini1,checadafin1," .
                                "ausente, TIMEDIFF(horaini,checadaini1) as difentrada, TIMEDIFF(horafin,checadafin1) as difsalida," .
                                "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .      
                                "TIMEDIFF(CASE
                                WHEN checadafin1='' THEN 0
                                WHEN checadafin1<horafin THEN checadafin1
                                WHEN checadafin1>horafin THEN horafin
                                ELSE horafin
                                END,CASE
                                WHEN checadaini1='' THEN 0
                                WHEN checadaini1<horaini THEN horaini
                                WHEN checadaini1>horaini THEN checadaini1
                                ELSE horaini
                                END) as horas_segun_horario," .  
                                "excepcion, jornadaTrabajada, attTime as jornadaReal " .                            
                                " from registros where departamento = '" . $nomDepto .  
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 . 
                                "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                                "' order by idemp, fecha";                
                }
            }
        }
    }else {
        
        if ($tipoRep == "1"){
            $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                        "horaini,horafin,checadaini1,checadafin1," .
                        "ausente, TIMEDIFF(horaini,checadaini1) as difentrada, TIMEDIFF(horafin,checadafin1) as difsalida," .
                        "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " .   
                        "TIMEDIFF(CASE
                        WHEN checadafin1='' THEN 0
                        WHEN checadafin1<horafin THEN checadafin1
                        WHEN checadafin1>horafin THEN horafin
                        ELSE horafin
                        END,CASE
                        WHEN checadaini1='' THEN 0
                        WHEN checadaini1<horaini THEN horaini
                        WHEN checadaini1>horaini THEN checadaini1
                        ELSE horaini
                        END) as horas_segun_horario," .   
                        "excepcion, jornadaTrabajada, attTime as jornadaReal " .                    
                        " from registros where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 . 
                        "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                        "' and ((TIMEDIFF(horaini,checadaini1)<0 or TIMEDIFF(horafin,checadafin1)>0 or ausente='Y')) " .
                        //"' and TIMEDIFF(horaini,checadaini1)<0 " .                    
                        " order by departamento, idemp, fecha";               
        }else{
            if ($tipoRep == "2"){
                $sente = "select idemp,nombre,departamento,horario,STR_TO_DATE(fecha,'%d/%m/%Y') as fecha,".
                            "horaini,horafin,checadaini1,checadafin1," .
                            "ausente, TIMEDIFF(horaini,checadaini1) as difentrada, TIMEDIFF(horafin,checadafin1) as difsalida," .
                            "TIMEDIFF(checadafin1,checadaini1) as horas_totales, " . 
                            "TIMEDIFF(CASE
                            WHEN checadafin1='' THEN 0
                            WHEN checadafin1<horafin THEN checadafin1
                            WHEN checadafin1>horafin THEN horafin
                            ELSE horafin
                            END,CASE
                            WHEN checadaini1='' THEN 0
                            WHEN checadaini1<horaini THEN horaini
                            WHEN checadaini1>horaini THEN checadaini1
                            ELSE horaini
                            END) as horas_segun_horario," . 
                            "excepcion, jornadaTrabajada, attTime as jornadaReal " .                        
                            " from registros where STR_TO_DATE(fecha,'%d/%m/%Y') >= '" . $fechaini1 .                    
                            "' and STR_TO_DATE(fecha,'%d/%m/%Y') <= '" . $fechafin1 . 
                            "' order by departamento, idemp, fecha";                   
            }
        }     
    }
    //echo $sente;
    
    //or die("No fue posible obtener los registros. Intente de nuevo.")
    $result = mysql_query($sente, $cn) ;
   // $row = mysql_fetch_array($result);

    $linea = 0;
    $Num = 1;
    $idempant = "";
    $nvo = "";
    $num_retardos = 0;
    $registros_x_empleado = 0;
    $total_minutos_x_descontar = 0;
    $total_minutos_permiso = 0;
    $es_retardo = "0";  // E-corresponde a retardo de entrada S-corresponde a salida anticipada
    $clase = ' class="FilaImpar"';
    $tot_entrada = 0;
    $tot_entrada_con_gracia = 0;
    $tot_salida = 0;

    while ($row = mysql_fetch_array($result)) {          
        //------------------------------------------------------------------------
        //      caso especial de los veladores, que tienen 2 horarios para cada dia, se marca como checado si la salida es
        //      a las 12:59 o la entrada es a las 00:00 y tiene registro en el reloj del contrario (entrada o salida)
        
        //caso 1: el horario esta en dos partes de 22:00 a 23:59 y 00:00 a 06:00
        $es_velador = "";
        $registros_x_empleado ++;
        
        if ( $row['departamento'] == 'VELADORES') {
            $es_velador = "SI";
            
            IF (($row['horafin'] == '23:59') && ($row['checadaini1'] != "")) {
                $row['checadafin1'] = '23:59';
            }

            IF (($row['horaini'] == '00:00') && ($row['checadafin1'] != "")) {
                $row['checadaini1'] = '00:00';
            }        
        }
        
        //caso 2: el horario esta en 1 solo registro 22:00 a 06:00
        $separar = explode (" ", $row['horario']);
        $horainicial = $separar[0];
        $horafinal = $separar[2];
        
        $separar = explode (":",$horainicial);
        $hora_horainicial = $separar[0];
        
        $separar = explode (":",$horafinal);
        $hora_horafinal = $separar[0];
        
        if (($hora_horafinal < $hora_horainicial) || ($hora_horafinal == $hora_horainicial )) { // ej 06:00 es menor que 22:00
           $es_velador = "SI";
           $row['horas_segun_horario'] = $row['jornadaTrabajada'] . ":00";
           $row['horas_totales'] = $row['jornadaReal'] . ":00";

           if ($row['horas_segun_horario'] == ":00") {
               $row['horas_segun_horario'] = "";
           }
        }
        
        //-------------------------------------------------------------------------
        // determino en base a los registros si el velador deberia checar o no
        $debe_checar = "";
        if ($es_velador == "SI"){
            if ($row['checadaini1'] != "" && $row['checadafin1'] != ""){
                $debe_checar = "SI";
            }
        }
        
        if ($registros_x_empleado == 1  && $debe_checar == "SI") {
            // determino si el empleado debe checar con los pares o con los impares
            $es_par = esPar($registros_x_empleado);
            if ($es_par == true){
                //hay que pensar en como hacerle para amarrar numeros pares e impartes al encontrar registros o no encontrarlos!!!!!
            }           
        }
        
        
 //       if (($es_velador == "SI" && $debe_checar == "SI") || ($es_velador == ""))  {                    
        
            //-------------------------------------------------------------------------

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


            //determino si ya es nuevo empleado o no
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
                
                $tot_entrada = 0;
                $tot_entrada_con_gracia = 0;
                $tot_salida = 0;
                

            } else {
                if ($idempant != $row['idemp']) {
                    // graba numero de retardos de la quincena
                    actualiza_num_retardos($idempant, $id_periodo, $num_retardos);
                    
                    $idempant = $row['idemp'];
                    $nombreant = $row['nombre'];
                    $departamentoant = $row['departamento'];
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
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                                        $total_minutos_permiso . '</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                                        $total_minutos_x_descontar . '</b></td>
                                <td align="center" style="font-size:15px;"></td>              
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                                        $tot_entrada . '</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                                        $tot_entrada_con_gracia . '</b></td>
                                <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                                        $tot_salida . '</b></td>                                            
                    </tr>';

                             //   <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                             //           $num_retardos . '</b></td>

                    echo '</table><table width="100%" border="0" align="center" bgcolor="#FFFFFF">';
                    

                    
                }else{
                    $nvo = "";
                }
            }

            if ($nvo == "SI") {
            /*    if ($numero_empleados > 0){
                    echo '<table width="95%" border="0" align="center" bgcolor="#FFFFF">';                
                    echo '<tr align "right"><td align="right"> Total de retardos en el periodo:   ' . $num_retardos . '</td></tr>'; 
                    echo '<tr align "right"><td align="right"> Total de minutos que se van a descontar:   ' . $total_minutos_x_descontar . '</td></tr>'; 
                    echo '<td>&nbsp&nbsp;</td>';                
                    echo '</table>';
                }
                */
                //-----------------------------------------------------------------------
                //    verifico retardos previos si $reinicio_contador = 0
                $cant_retardos = cant_retardos($idempant, $reinicio_contador, $id_periodo);

                //------------------------------------------------------------------------
                //obtengo el tipo de empleado
                $sente5 = "SELECT idTipo FROM empleado where idEmp =". $idempant;
                $result5 = mysql_query($sente5, $cn) ;    
                $row5 = mysql_fetch_array($result5);
                $tipo_empleado = $row5['idTipo'];

                $sente6 = "SELECT Descripcion FROM tipoempleado where idTipo =". $tipo_empleado;
                $result6 = mysql_query($sente6, $cn) ;    
                $row6 = mysql_fetch_array($result6);
                $tipo_empleado_desc = $row6['Descripcion'];   

                echo '<td>&nbsp&nbsp;</td>';            
                echo '<table width="100%" border="1" align="center" bgcolor="#08088A">';
                echo '<tr align "left"><td align="left"><font color="#FFFFFF" size="3"><b>' . 
                          $departamentoant . " - " . $idempant . " - " .  $nombreant . " -   " . $tipo_empleado_desc . '</b></font></td>';
                echo '</table>';

                echo '<table width="100%" border="0" align="center" bgcolor="#FFFFFF">';
                echo '<tr align="right"' . $clase . '>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Fecha registro</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora entrada</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hora salida</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Reg.checada entrada</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Reg.checada salida</b></td> 
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Min.Entrada tarde</b></td> 
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Min.Salida temprano</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Hrs. Trab. s/horario</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Total hrs. trabajadas</b></td> 
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Ausente</b></td>                        
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Mins<p>permiso<p>capturado</b></td>                        
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Min. descto.</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Retardo</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Mins Ent</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Mins Ent c/gracia</b></td>
                            <td align="center" bgcolor="#D8D8D8" style="font-size:14px;border: solid #000000; border-width:2px;"><b>Mins Sal</b></td>
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
                
                

            }        

            //--------------------------------------------------------------------------------
            // determino si hay o no descuento de minutos
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
            
            // ------ ausencia ---------
            if ($row['ausente']== "Y" ){
                $ausente = "SI";
                $minutos_x_descontar_formato_hora = date("H:i", strtotime("00:00") + strtotime($row['horafin']) - strtotime($row['horaini']) );
                $horas = 0;
                $minutos = 0;            
                $row['horas_totales'] = "00:00:00";

                $separar = explode(':',$minutos_x_descontar_formato_hora);
                $horas = $separar[0];
                $minutos = $separar[1]; 
                $total_minutos_ausente = ($horas*60)+$minutos;


            }else{
                $ausente = "";
            }        


            //  ------entrada-------
            $separar = explode(':',$row['difentrada']);
            $es_negativo_e = strpos($separar[0],"-");
            $horas_entrada = "00";
            $minutos_entrada = "00";


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


            //  -------salida-------
            $separar = explode(':',$row['difsalida']);
            $es_negativo_s = strpos($separar[0],"-");
            $horas_salida = "00";
            $minutos_salida = "00";        
            $total_minutos_salida = 0;

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

            //----------------------------------------------------------------------------------------
            //    obtengo informacion de los permisos para ese dia y ese empleado        
            $ubic_permiso = "";
            $separar =  explode('-',$row['fecha']);

            $anio = $separar[0];
            $mes = $separar[1];
            $dia = $separar[2];

            $fecha_reg = $dia . "/" . $mes . "/" . $anio;
            
            // permisos con horario establecido
            $sente1 = "select idemp, fechaini as fechaini1, fechafin as fechafin1," .
                      " horaini, horafin, tipo, motivo, horaCaptura, minutosDiarios" .    
                      " from permisos where fechaini >= '" . $fecha_reg .
                      "' and fechafin <= '" . $fecha_reg .
                      "' and horaini >= '" . $row['horaini'] .
                      "' and horafin <= '" . $row['horafin'] .
                      "' and idemp = " . $row['idemp'] ;              
            $result1 = mysql_query($sente1, $cn) ;          
            //$row1 = mysql_fetch_array($result1); 

            $mins_permiso = 0;

            //echo $sente1;           
            $permiso_hora_ini = 0;
            $permiso_hora_fin = 0;
            
            while ($row1 =mysql_fetch_array($result1)) {            
                $mins_permiso += $row1['minutosDiarios'];
                
                $permiso_hora_ini =  $row1['horaini'];
                $permiso_hora_fin =  $row1['horafin'];
            }         
            
            // permisos por todo el dia: de 00:01 a 23:59 y el usuario esta ausente
            if ($ausente == "SI"){
                $sente1 = "select idemp, fechaini as fechaini1, fechafin as fechafin1," .
                          " horaini, horafin, tipo, motivo, horaCaptura, minutosDiarios" .    
                          " from permisos where fechaini >= '" . $fecha_reg .
                          "' and fechafin <= '" . $fecha_reg .
                          "' and horaini = '00:01' " .
                          " and horafin = '23:59' " .
                          " and idemp = " . $row['idemp'] ;              
                $result1 = mysql_query($sente1, $cn) ;          
                //$row1 = mysql_fetch_array($result1); 

                $mins_permiso = 0;

                $permiso_hora_ini = 0;
                $permiso_hora_fin = 0;

                while ($row1 =mysql_fetch_array($result1)) {            
                    $mins_permiso += $row1['minutosDiarios'];

                    $permiso_hora_ini =  $row1['horaini'];
                    $permiso_hora_fin =  $row1['horafin'];
                }             

                $total_minutos_permiso += $mins_permiso; 
            }

            //-------------- proceso para verificar SALIDAS ----------------------------------
            // descuento todas las salidas antes de horario. no cuentan para la tolerancia de las entradas
            //$total_minutos_salida_grabar = 0;            
            if ($total_minutos_salida > 0){
                $es_retardo = "S";
                $es_retardo_web = "S";

                //grabo registro
           /*     
                $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario,horafin,checadaFin1," .
                  "difSalida,hrsTrabHorario,hrsTrabTotal,ausente,minDescuento,esRetardo) " .
                   "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $row['departamento'] . "','" . 
                        $row['fecha'] . "','" . $row['horario'] . "','" . $row['horafin'] . "','" .
                        $row['checadafin1'] . "','" . $dif_minutos_salida . "','" . 
                        $row['horas_segun_horario'] . "','" . $row['horas_totales'] . "','" . $ausente  . "'," . 
                        $total_minutos_salida_grabar . ",'" . $es_retardo . "')";
                $result1 = mysql_query($sente1, $cn) ;                 
            */
            }         
           

            //------------------proceso para verificar ENTRADAS ----------------------------------------
            // si el registro de entrada es despues de la hora establecida se toman en cuenta
            // y no esta ausente
            $minutos_x_descontar_e = 0;

            if ($total_minutos_entrada != 0){

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
                if ($tipo_empleado == 3){
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
                            }                
                        }  
                    }
                }

            }            

            $tot_entrada += $total_minutos_entrada;
            $tot_entrada_con_gracia += $minutos_x_descontar_e;
            
            //----------------------------------------------------------------------------------------
            if (($es_velador == "SI" && $debe_checar == "SI") || ($es_velador == "")){
            // se contabilizan los minutos para descontar a todos y a los veladores cuando si deben checar
                
                $minutos_x_descontar = $minutos_x_descontar_e + $total_minutos_salida + $total_minutos_ausente;//- $mins_permiso;
                
                if ($mins_permiso >= $minutos_x_descontar) {
                    $minutos_x_descontar = 0;
                    $es_retardo_web = "";
                }else {
                    $minutos_x_descontar -= $mins_permiso;
                }
                
                $total_minutos_x_descontar = $total_minutos_x_descontar + $minutos_x_descontar ;// - $total_minutos_permiso;
            }
            
            //---------------------------------------------------------------------------
            //se eliminan los descuentos en aquellos dias que no debe checar
                if ($es_velador == "SI" && $debe_checar == ""){
                    
                    $minutos_x_descontar= 0;
                    $minutos_x_descontar_e =0;
                    $es_retardo_web = "";
                }                       
            
            if (($minutos_x_descontar != 0) || ($tipoRep == "2")){
            
                echo '<tr align="right"' . $clase . ' >
                            <td align="center" style="font-size:15px;">' . $row['fecha'] . '</td>
                            <td align="center" style="font-size:15px;">' . $row['horaini'] . '</td>
                            <td align="center" style="font-size:15px;">' . $row['horafin'] . '</td>
                            <td align="center" style="font-size:15px;">' . $row['checadaini1'] . '</td>
                            <td align="center" style="font-size:15px;">' . $row['checadafin1'] . '</td> 
                            <td align="center" style="font-size:15px;">' . $dif_minutos_entrada . '</td> 
                            <td align="center" style="font-size:15px;">' . $dif_minutos_salida . '</td>
                            <td align="center" style="font-size:15px;">' . $row['horas_segun_horario'] . '</td>   
                            <td align="center" style="font-size:15px;">' . $row['horas_totales'] . '</td>
                            <td align="center" style="font-size:15px;">' . $ausente . '</td>                             
                            <td align="center" style="font-size:15px;">' . $mins_permiso . '</td>                           
                            <td align="center" style="font-size:15px;">' . $minutos_x_descontar . '</td>
                            <td align="center" style="font-size:15px;">' . $es_retardo_web . '</td>
                            <td align="center" style="font-size:15px;">' . $total_minutos_entrada . '</td>
                            <td align="center" style="font-size:15px;">' . $minutos_x_descontar_e . '</td>    
                            <td align="center" style="font-size:15px;">' . $total_minutos_salida . '</td>    
                </tr>';
            }
            
            //-------------------------------------------------------------------------------
            
            // determino si el permiso fue para la entrada "E", la salida "S" o ninguna de las dos "N"
            $total_minutos_entrada_grabar = $minutos_x_descontar_e;
            $total_minutos_salida_grabar = $total_minutos_salida;
                
            if ($mins_permiso > 0){
                
                if ($permiso_hora_ini == $row['horaini']) {
                    $ubic_permiso = "E";
                    $total_minutos_entrada_grabar = $minutos_x_descontar_e - $mins_permiso;
                }
                
                if ($permiso_hora_fin == $row ['horafin']) {
                    $ubic_permiso = "S";
                    $total_minutos_salida_grabar = $total_minutos_salida - $mins_permiso;
                }

                if (($permiso_hora_fin != $row ['horafin']) || ($permiso_hora_ini != $row ['horafin'])) {
                    $ubic_permiso = "N";
                }
            }
            //--------------------------------------------------------------------------------------
            
            // inserto registro de salida descuento si aplica
             if ($total_minutos_salida_grabar > 0) {
                $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario,horafin,checadaFin1," .
                  "difSalida,hrsTrabHorario,hrsTrabTotal,ausente,minDescuento,esRetardo,minPermiso) " .
                   "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $row['departamento'] . "','" . 
                        $row['fecha'] . "','" . $row['horario'] . "','" . $row['horafin'] . "','" .
                        $row['checadafin1'] . "','" . $dif_minutos_salida . "','" . 
                        $row['horas_segun_horario'] . "','" . $row['horas_totales'] . "','" . $ausente  . "','" . 
                        $total_minutos_salida_grabar . "','" . $es_retardo . "','". $mins_permiso ."')";
                $result1 = mysql_query($sente1, $cn) ;
       
             }
                
                
                // inserta registro en temporal si se cuenta como falta
            if ($total_minutos_entrada_grabar != 0) {    
    
                if ($ubic_permiso == "E") {
                    $minutos_x_descontar_grabar = $minutos_x_descontar - $mins_permiso;                
                }                
                
                if ($minutos_x_descontar > 0) {
                    $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario,horaIni, checadaIni1," .
                      "difEntrada,hrsTrabHorario,hrsTrabTotal,ausente,minDescuento,esRetardo,minPermiso) " .
                       "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $row['departamento'] . "','" . $row['fecha'] . "','" .
                            $row['horario'] . "','" .$row['horaini']  ."','" . $row['checadaini1'] . "','" .
                            $dif_minutos_entrada  . "','" . $row['horas_segun_horario'] .
                             "','" . $row['horas_totales'] . "','" .$ausente  . "','" . 
                            $total_minutos_entrada_grabar . "','" . $es_retardo . "','" . $mins_permiso . "')";
                    $result1 = mysql_query($sente1, $cn) ;   
               
                }
                
                
            }
            if ($ausente == "SI") {    
                if ($minutos_x_descontar > 0) {
                    $sente1 = "INSERT INTO retardos_temp (idEmp,nombre,departamento,fecha,horario," .
                      "ausente,minDescuento, minPermiso) " .
                       "VALUES ('" . $row['idemp'] . "','" .$row['nombre']  . "','" . $row['departamento'] . "','" . $row['fecha'] . "','" .
                            $row['horario'] . "','" .$ausente  . "'," . 
                            $minutos_x_descontar . ",'" . $mins_permiso . "')";
                    $result1 = mysql_query($sente1, $cn) ; 
                    
                    $Num+=1;                
                }     
            }        

    //    }
    }//fin while
    
    //graba numero de retardos por empleado de la quincena
    actualiza_num_retardos($idempant, $id_periodo, $num_retardos);
    
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
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>TOTAL</b></td> 
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
                 $total_minutos_permiso . '</b></td>         
         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                 $total_minutos_x_descontar . '</b></td>
        <td align="center" style="font-size:15px;"></td>              
        <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                 $tot_entrada . '</b></td>
        <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                 $tot_entrada_con_gracia . '</b></td>
        <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' . 
                 $tot_salida . '</b></td>                         
        </tr><tr><td><p></td></tr>';
             
    
  //         <td align="center" bgcolor="#848484" style="font-size:15px; border: solid 0 #060; border-top-width:2px;"><b>' .
//                 $num_retardos . '</b></td>
                     
     mysql_free_result($result);
   //  mysql_free_result($result1);
     mysql_close($cn);
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Control de asistencias 1.0</title>
        <link href="../adds/estilo.css" rel="stylesheet" type="text/css" />

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
<!--728-->
       <!-- <table width="95%" border="0" align="center"> -->
        <table border="0" style="text-align:center;background-color: white" width="100%">
            <form action="imprimir_retardos.php" target="_blank" method="post" id="Menu" >  

            <tr>
                <td colspan="3">
                    <img alt="Preparatoria Dos - UADY" src="imagen/logo2.jpg" />
                </td>
            </tr>            
            <tr align ="left">
                <td><p><b>Departamento: </b><?php echo $nomDepto; ?> </p></td> 
            </tr>
            <tr align ="left">
                <td><p><b>Empleado: </b><?php echo $nomEmp; ?> </p></td>                
            </tr>
            <tr align ="left">
                <td><p><b>Reporte seleccionado: </b><?php echo $nomRep; ?> </p></td>                
            </tr> 
            <tr align ="left">
                <td><p><b>Fecha inicial: </b><?php echo $fechaini; ?> </p></td>                
            </tr> 
            <tr align ="left">
                <td><p><b>Fecha final: </b><?php echo $fechafin; ?> </p></td>                
            </tr>
            <tr>
                <?php 
                if ($tipoRep == '1') {
                ?> 
                <td colspan="4" align="center">                   
                    <input type="submit" name="btnImprime" value="Imprimir" onclick=""></input>
                </td>
                
                <?php
                }
                ?>
                <td>
                    <option><a href="Menu.php">Regresar al Menu</a></option>
                </td>                            
            </tr>
            <tr>
                <td align="centar">
                    <?php
                        regChecadas();
                    ?>
                </td>
            </tr>            

            </form>
       </table>

    </body>
</html>