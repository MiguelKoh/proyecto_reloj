<?php
session_start();
 include("conex.php");
 include('funciones_horarios.php');

$id_curso = 6;
$id_semestre = 11;

$cn = ConectaBD();

$sente = "SELECT distinct idEmp FROM c_horarios_temporal WHERE id_semestre = " . $id_semestre . " order by idEmp";
    echo $sente."</p>";
$result = mysql_query($sente,$cn);



//defino lista de profesores
while ($row = mysql_fetch_array($result)){

    //determino si el empleado maneja x periodos de tiempo horarios distintos
    $sente0 = "SELECT distinct fecha_ini,fecha_fin FROM c_horarios_temporal WHERE idEmp = '" . $row['idEmp'] . "'" .
             " and id_curso = " . $id_curso . " and id_semestre = " . $id_semestre;
     echo $sente0."</p>";
    $result0 = mysql_query($sente0,$cn);
    
    while($row0 = mysql_fetch_array($result0)){    
        
        $fecha_ini = $row0['fecha_ini'];
        $fecha_fin = $row0['fecha_fin'];

        //por cada dia determino el horario del maestro
        for($dia=1;$dia<=7;$dia++){
            
            $sente1 = "SELECT distinct a.idemp,a.id_dia,a.id_c_horario,a.id_asignatura,a.id_c_tipo_horario,a.id_curso,".            
                        "a.id_semestre,a.descripcion,a.semana_inicio_descarga,a.debe_checar,a.fecha_ini,a.fecha_fin,a.id_tipo_empleado,".
                        "b.hora_ini,b.hora_fin from ".
                        "c_horarios_temporal as a inner join catalogo_horarios as b ".
                        "on a.id_c_horario = b.id_c_horario and	a.id_c_tipo_horario = b.id_c_tipo_horario ".
                        "where a.idEmp = " . $row['idEmp'] . //" and a.fecha_ini = '" . $fecha_ini .
                        " and a.id_dia = " . $dia . " and a.id_curso = " . $id_curso . " and a.id_semestre = " . $id_semestre .
                        " order by b.hora_ini";
            
            $result1 = mysql_query($sente1,$cn);

            echo $sente1."</p>";

            $h_ini = 0;
            $semana_descarga = "";                           

            while ($row1 = mysql_fetch_array($result1)){                                
                
                echo "---------------------------------------------------------"."</p>";
                
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
                        $id_tipo_empleado = $row1['id_tipo_empleado'];


                        if ($row1['semana_inicio_descarga'] <> 0){
                            $semana_descarga = $row1['semana_inicio_descarga'];
                        }                        
                        
                    }else{                

                       //$tipo_horario = $row1['id_c_tipo_horario'];
                       $es_consecutivo = siguienteHorario($h_fin,$row1['id_c_horario'],$tipo_horario_fin,$row1['id_c_tipo_horario']);    

                       echo $h_fin . " - " . $row1['id_c_horario'] . " - " . $tipo_horario_fin . " - " . $row1['id_c_tipo_horario'] . "</p>";
                       echo "es consecutivo: " . $es_consecutivo ."</p>";

                        if ($es_consecutivo == "SI"){//($h_fin+1 == $row1['id_c_horario']){
                            
                            //ubico el horario en el catalogo de horarios
                             $hora_ini = obtenerHorario($h_ini,$tipo_horario_ini,1);
                             $hora_fin = obtenerHorario($h_fin,$tipo_horario_fin,2);        
                             
                             
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
                                          // para grabar por separado la hora de la comida del profesor, de 1 a 2 que no forma parte del horario docente

                                            //determino tipo empleado en base al tipo de horario
                                            if ($tipo_horario_ini == 4){
                                                $id_tipo_empleado = 3;
                                            }else{
                                                $id_tipo_empleado = 1;
                                            }                                        
                                            
                                            $horario = $hora_ini . " A " . $hora_fin;                                            
                                            
                                            $sente2 = "INSERT INTO horarios_semestre (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,id_semestre,".
                                                       "semana_descarga,id_tipo_empleado,fecha_ini,fecha_fin) VALUES ('".
                                                     $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                                                     $id_curso . "," . $id_semestre . ",'" .$semana_descarga. "'," . $id_tipo_empleado . ",'" .
                                                     $fecha_ini . "','" . $fecha_fin .  "')";
                                             echo $sente2." - Corte de 1 a 2 pm </p>";
                                            $result2 = mysql_query($sente2,$cn);

                                            $h_ini = $row1['id_c_horario']; 
                                            $tipo_horario_ini = $row1['id_c_tipo_horario'];
                                            $id_tipo_empleado = $row1['id_tipo_empleado'];

                                            //defino si en el bloque de horario hay descarga quincenal
                                            $semana_descarga = "";

                                            if ($row1['semana_inicio_descarga'] <> 0){
                                                $semana_descarga = $row1['semana_inicio_descarga'];
                                            }

                                             echo "Grabe horario profesor: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</p>";
                                     }
                             }else{
                                                  
                                 $sente2 = "UPDATE horarios_semestre SET hora_fin = '" . $hora_fin . " WHERE idEmp = '" .  $row['idEmp'] .
                                         " and id_dia = " . $dia . " and hora_fin = '" . $hora_ini . "'" ;
                                 $result2 = mysql_query($sente2,$cn);
                                 
                                 echo "actualice horario porque era consecutivo: dia: " . $dia . "</p>";
                                 echo $sente2 . "</p>";
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
                            
                            $sente2 = "INSERT INTO horarios_semestre (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,id_semestre,".
                                    "semana_descarga,id_tipo_empleado,fecha_ini,fecha_fin) VALUES ('".
                                     $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                                     $id_curso . "," . $id_semestre .",'" .$semana_descarga.  "',". $id_tipo_empleado . ",'" .
                                     $fecha_ini . "','" . $fecha_fin .  "')";
                             echo $sente2." - Brincos entre clases u horarios </p>";
                            $result2 = mysql_query($sente2,$cn); 

                            $h_ini = 0;
                            $tipo_horario_ini = 0;
                            $id_tipo_horario = 0;

                             echo "Grabe horario profesor no consecutivo: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</p>";
                            //
                            //grabo los valores de h_ini y h_fin anteriores
                            //establezco como h_ini y h_fin este nuevo valor
                            $h_ini = $row1['id_c_horario'];                
                            $h_fin = $row1['id_c_horario'];   
                            $tipo_horario_ini = $row1['id_c_tipo_horario'];          
                            $tipo_horario_fin = $row1['id_c_tipo_horario'];  
                            $id_tipo_empleado = $row1['id_tipo_empleado'];


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
                        if ($tipo_horario_ini == 4){
                            $id_tipo_empleado = 3;
                        }else{
                            $id_tipo_empleado = 1;
                        }
                                            
                        $sente2 = "INSERT INTO horarios_semestre (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,".
                                "id_semestre,semana_descarga,id_tipo_empleado,fecha_ini,fecha_fin) VALUES ('".
                                $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                                $id_curso . "," . $id_semestre .",'" .$semana_descarga.  "'," . $id_tipo_empleado . ",'" .
                                $fecha_ini . "','" . $fecha_fin .  "')";
                        echo $sente2." estoy al final </p>";
                        $result2 = mysql_query($sente2,$cn);

                        $h_ini = 0;    
                        $tipo_horario_ini = 0;
                        $semana_descarga = "";

                        echo "Grabe horario profesor al final: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</p>";
         
            }
            

        }
        break; //para que me tome la primera fecha
    }
        
}
echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';


?>
