<?php
session_start();
 include("conex.php");
 include('funciones_horarios.php');
 
$cn = ConectaBD();

$sente = "SELECT distinct idEmp FROM c_horarios_temporal where semana_inicio_descarga in (1,2) order by idEmp";
    echo "<tr><td>-".$sente."-</td></tr>";
$result = mysql_query($sente,$cn);

$id_curso = 6;
$id_semestre = 11;

//defino lista de profesores que tienen descarga, para armar los horarios cuando no les toque (quincenales)
while ($row = mysql_fetch_array($result)){

    //determino si el empleado maneja x periodos de tiempo horarios distintos
    $sente0 = "SELECT distinct fecha_ini,fecha_fin FROM c_horarios_temporal WHERE idEmp = '" . $row['idEmp'] . 
                "' and semana_inicio_descarga in (1,2) and id_curso = " . $id_curso . " and id_semestre = " . $id_semestre ;
    
     echo "<tr><td>-".$sente0."-</td></tr>";
    $result0 = mysql_query($sente0,$cn);
    
    while($row0 = mysql_fetch_array($result0)){    
        
        $fecha_ini = $row0['fecha_ini'];
        $fecha_fin = $row0['fecha_fin'];
    //genero horarios segun cada tipo de horario
  /*  for ($tipo_horario=1;$tipo_horario<=3;$tipo_horario++){
        if ($tipo_horario == 1 || $tipo_horario ==2){
            $ordenar = "a.id_c_horario";
        }else{
            $ordenar = "b.hora_ini";
        }        
    */  
        
        //genero ciclo solo para aquellos días en donde el profesor tenga descarga o preparacion quincenal
        $sente_d = "SELECT distinct id_dia FROM c_horarios_temporal WHERE idEmp = '" . $row['idEmp'] .
                   "' and fecha_ini = '" . $fecha_ini . "' and semana_inicio_descarga in (1,2) and id_curso = " . 
                   $id_curso . " and id_semestre = " . $id_semestre;
        echo "<tr><td>-".$sente_d."-</td></tr>";
        $result_d = mysql_query ($sente_d,$cn);
        
        while ($row_d = mysql_fetch_array($result_d)){

            $dia = $row_d['id_dia'];
            //por cada dia determino el horario del maestro
            //for($dia=1;$dia<=7;$dia++){
            $sente1 = "SELECT distinct a.idemp,a.id_dia,a.id_c_horario,a.id_asignatura,a.id_c_tipo_horario,a.id_curso,".            
                        "a.id_semestre,a.descripcion,a.semana_inicio_descarga,a.debe_checar,a.fecha_ini,a.fecha_fin,".
                        "b.hora_ini,b.hora_fin from ".
                        "c_horarios_temporal as a inner join catalogo_horarios as b ".
                        "on a.id_c_horario = b.id_c_horario and	a.id_c_tipo_horario = b.id_c_tipo_horario ".
                        "where a.idEmp = " . $row['idEmp'] . " and a.fecha_ini = '" . $fecha_ini .
                        //" and a.id_dia = " . $dia . " and a.id_c_tipo_horario = " . $tipo_horario . " order by " . $ordenar;
                        "' and a.id_dia = '" . $row_d['id_dia'] . "' and a.semana_inicio_descarga = 0 " .
                        " and a.id_curso = " . $id_curso . " and a.id_semestre = " . $id_semestre .
                        " 
                            order by b.hora_ini";
            
            $result1 = mysql_query($sente1,$cn);

            echo "<tr><td>-".$sente1."-</td></tr>";

            $h_ini = 0;
            $semana_descarga = "";                           

            while ($row1 = mysql_fetch_array($result1)){
                                              
                if ($row1['id_c_tipo_horario'] == 2){
                    //no se toman en cuenta las clases de computo ya que estan incluidas en el horario
                    //de los empleados de TICS
                    $estatus = "no hago nada cuando es computo";
                }else{
                    if ($h_ini == 0){
                        $h_ini = $row1['id_c_horario'];                
                        $h_fin = $row1['id_c_horario'];   
                        $tipo_horario_ini = $row1['id_c_tipo_horario'];
                        $tipo_horario_fin = $row1['id_c_tipo_horario'];                                                

                        //defino si en el bloque de horario hay descarga quincenal
                        /*
                        $es_quince = strpos(strtoupper($row1['descripcion']),"CADA 15");
                        $es_quincenal = strpos(strtoupper($row1['descripcion']),"QUINCENAL");

                        if ($es_quince > 0 || $es_quincenal > 0 ){
                            $semana_descarga = $row1['semana_inicio_descarga'];
                        }                      
                        */
                        if ($row1['semana_inicio_descarga'] <> 0){
                            $semana_descarga = $row1['semana_inicio_descarga'];
                        }                        
                    }else{                

                       //$tipo_horario = $row1['id_c_tipo_horario'];
                       $es_consecutivo = siguienteHorario($h_fin,$row1['id_c_horario'],$tipo_horario_fin,$row1['id_c_tipo_horario']);    


                        if ($es_consecutivo == "SI"){//($h_fin+1 == $row1['id_c_horario']){

                            //ubico el horario en el catalogo de horarios
                             $hora_ini = obtenerHorario($h_ini,$tipo_horario_ini,1);
                             $hora_fin = obtenerHorario($h_fin,$tipo_horario_fin,2);        
                                                         
                            //defino si en el bloque de horario hay descarga quincenal

                            if ($row1['semana_inicio_descarga'] <> 0){
                                $semana_descarga = $row1['semana_inicio_descarga'];
                            }                            
                            
                             //las clases estan pegadas                   
                             if ($tipo_horario_fin == 1 && $h_fin+1 == 9){
                                     //verifico si el maestro clase en horario final de mañana y horario inicial de tarde.
                                     //si es asi el horario se tiene que cortar x que de 1 a 2 no laboran los maestros
                                     
                                     if ($row1['id_c_tipo_horario'] != 1){
                                         // cuando el horario de salida y entrada son los mismos pero el tipo de horario es diferente
                                            $sente2 = "UPDATE horarios_semestre SET hora_fin = '" . $hora_fin . " WHERE idEmp = '" .  $row['idEmp'] .
                                                    " and id_dia = " . $dia . " and hora_fin = '" . $hora_ini . "'" ;
                                            $result2 = mysql_query($sente2,$cn);

                                            echo "actualice horario porque era consecutivo: dia: " . $dia . "</p>";
                                            echo $sente2 . "</p>";   
                                            
                                     }else{
                                                     
                                            //determino tipo empleado en base al tipo de horario
                                            if ($tipo_horario_ini == 4){
                                                $id_tipo_empleado = 3;
                                            }else{
                                                $id_tipo_empleado = 1;
                                            }

                                            $horario = $hora_ini . " A " . $hora_fin;

                                            $sente2 = "INSERT INTO horario_sin_preparacion (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,id_semestre,".
                                                       "id_tipo_empleado,fecha_ini,fecha_fin) VALUES ('".
                                                     $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                                                     $id_curso . "," . $id_semestre . "," . $id_tipo_empleado . ",'" .
                                                     $fecha_ini . "','" . $fecha_fin .  "')";
                                             echo "<tr><td>-".$sente2."-</td></tr>";
                                            $result2 = mysql_query($sente2,$cn);

                                            $h_ini = $row1['id_c_horario']; 
                                            $tipo_horario_ini = $row1['id_c_tipo_horario'];

                                            //defino si en el bloque de horario hay descarga quincenal
                                            $semana_descarga = "";

                                            if ($row1['semana_inicio_descarga'] <> 0){
                                                $semana_descarga = $row1['semana_inicio_descarga'];
                                            }

                                            echo "<tr><td>Grabe horario profesor: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</td></tr>";
                                     }
                             }                                   

                            $h_fin = $row1['id_c_horario'];
                            $tipo_horario_fin = $row1['id_c_tipo_horario'];

                        }else{
                            //hay un brinco entre cada clase

                            //ubico el horario en el catalogo de horarios
                            $hora_ini = obtenerHorario($h_ini,$tipo_horario_ini,1);
                            $hora_fin = obtenerHorario($h_fin,$tipo_horario_fin,2);

                            $horario = $hora_ini . " A " . $hora_fin;
                            
                            //determino tipo empleado en base al tipo de horario
                            if ($tipo_horario_ini == 4){
                                $id_tipo_empleado = 3;
                            }else{
                                $id_tipo_empleado = 1;
                            }                            

                            $sente2 = "INSERT INTO horario_sin_preparacion (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,id_semestre,".
                                    "id_tipo_empleado,fecha_ini,fecha_fin) VALUES ('".
                                     $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                                     $id_curso . "," . $id_semestre .",". $id_tipo_empleado . ",'" .
                                     $fecha_ini . "','" . $fecha_fin .  "')";
                             //echo "<tr><td>-".$sente1."-</td></tr>";
                            $result2 = mysql_query($sente2,$cn); 

                            $h_ini = 0;
                            $tipo_horario_ini = 0;

                             echo "<tr><td>Grabe horario profesor no consecutivo: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</td></tr>";
                            //
                            //grabo los valores de h_ini y h_fin anteriores
                            //establezco como h_ini y h_fin este nuevo valor
                            $h_ini = $row1['id_c_horario'];                
                            $h_fin = $row1['id_c_horario'];   
                            $tipo_horario_ini = $row1['id_c_tipo_horario'];          
                            $tipo_horario_fin = $row1['id_c_tipo_horario'];          


                            //defino si en el bloque de horario hay descarga quincenal
                            $semana_descarga = "";

                            if ($row1['semana_inicio_descarga'] <> 0){
                                $semana_descarga = $row1['semana_inicio_descarga'];
                            }                            
                        }
                    }   
                }
            }
            if ($h_ini != 0){
                       //hay un brinco entre cada clase
                       //busco el horario de inicio en el catalogo de horarios                   
                       $hora_ini = obtenerHorario($h_ini,$tipo_horario_ini,1);
                       $hora_fin = obtenerHorario($h_fin,$tipo_horario_fin,2);

                       $horario = $hora_ini . " A " . $hora_fin;
                            
                        //determino tipo empleado en base al tipo de horario
                    /*    if ($tipo_horario_ini == 4){
                            $id_tipo_empleado = 3;
                        }else{
                            $id_tipo_empleado = 1;
                        } 
                    */   
                        $sente2 = "INSERT INTO horario_sin_preparacion (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,".
                                "id_semestre,id_tipo_empleado,fecha_ini,fecha_fin) VALUES ('".
                                $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                                $id_curso . "," . $id_semestre ."," . $id_tipo_empleado . ",'" .
                                $fecha_ini . "','" . $fecha_fin .  "')";
                        //echo "<tr><td>-".$sente1."-</td></tr>";
                        $result2 = mysql_query($sente2,$cn);

                        $h_ini = 0;    
                        $tipo_horario_ini = 0;
                        $semana_descarga = "";

                        echo "<tr><td>Grabe horario profesor al final: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</td></tr>";
          
            }

        }
    }
        
}
echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';


?>
