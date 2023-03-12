<?php
session_start();
 include("conex.php");
 include('funciones_horarios.php');
 
$cn = ConectaBD();

$sente = "SELECT distinct idEmp FROM c_horarios_temporal";
   // echo "<tr><td>-".$sente."-</td></tr>";
$result = mysql_query($sente,$cn);

$id_curso = 4;
$id_semestre = 8;

//defino lista de profesores
while ($row = mysql_fetch_array($result)){
    
    //por cada dia determino el horario del maestro
    for($dia=1;$dia<=7;$dia++){
        $sente1 = "SELECT distinct idemp,id_dia,id_c_horario,id_asignatura,id_c_tipo_horario,id_curso,id_semestre".
                " from c_horarios_temporal where idEmp = " . $row['idEmp'] .
                " and id_dia = " . $dia . " order by id_c_horario";
        $result1 = mysql_query($sente1,$cn);
                        
      //  echo "<tr><td>-".$sente1."-</td></tr>";
        
        $h_ini = 0;
        
        while ($row1 = mysql_fetch_array($result1)){

            if ($h_ini == 0){
                $h_ini = $row1['id_c_horario'];                
                $h_fin = $row1['id_c_horario'];   
                $tipo_horario = $row1['id_c_tipo_horario'];

            }else{
               $tipo_horario = $row1['id_c_tipo_horario'];
               
               if ($h_fin+1 == $row1['id_c_horario']){
                   
                    //las clases estan pegadas                   
                    if ($tipo_horario == 1 && $h_fin+1 == 9){
                            //verifico si el maestro clase en horario final de mañana y horario inicial de tarde.
                            //si es asi el horario se tiene que cortar x que de 1 a 2 no laboran los maestros
                           //ubico el horario en el catalogo de horarios
                            $hora_ini = obtenerHorario($h_ini,$tipo_horario,1);
                            $hora_fin = obtenerHorario($h_fin,$tipo_horario,2);

                            $horario = $hora_ini . " A " . $hora_fin;

                            $sente2 = "INSERT INTO horarios_semestre (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,id_semestre) VALUES ('".
                                     $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                                     $id_curso . "," . $id_semestre . ")";
                             //echo "<tr><td>-".$sente1."-</td></tr>";
                            $result2 = mysql_query($sente2,$cn);

                            $h_ini = $row1['id_c_horario'];                                      

                             echo "<tr><td>Grabe horario profesor: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</td></tr>";

                    }                                   
                                      
                   $h_fin = $row1['id_c_horario'];
                   
               }else{
                   //hay un brinco entre cada clase

                   //ubico el horario en el catalogo de horarios
                   $hora_ini = obtenerHorario($h_ini,$tipo_horario,1);
                   $hora_fin = obtenerHorario($h_fin,$tipo_horario,2);

                   $horario = $hora_ini . " A " . $hora_fin;
                   
                   $sente2 = "INSERT INTO horarios_semestre (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,id_semestre) VALUES ('".
                            $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                            $id_curso . "," . $id_semestre . ")";
                    //echo "<tr><td>-".$sente1."-</td></tr>";
                   $result2 = mysql_query($sente2,$cn);
                    
                   $h_ini = 0;                                      
                    
                    echo "<tr><td>Grabe horario profesor: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</td></tr>";
                   //
                   //grabo los valores de h_ini y h_fin anteriores
                   //establezco como h_ini y h_fin este nuevo valor                   
               }
            }                                    
        }
        if ($h_ini != 0){
                   //hay un brinco entre cada clase
                   //busco el horario de inicio en el catalogo de horarios                   
                   $hora_ini = obtenerHorario($h_ini,$tipo_horario,1);
                   $hora_fin = obtenerHorario($h_fin,$tipo_horario,2);
                   
                   $horario = $hora_ini . " A " . $hora_fin;
                   
                    $sente2 = "INSERT INTO horarios_semestre (idEmp,id_dia,horario,hora_ini,hora_fin,id_curso,id_semestre) VALUES ('".
                            $row['idEmp']. "'," . $dia . ",'" . $horario . "','". $hora_ini . "','" . $hora_fin . "'," .
                            $id_curso . "," . $id_semestre . ")";
                    //echo "<tr><td>-".$sente1."-</td></tr>";
                    $result2 = mysql_query($sente2,$cn);
                    
                    $h_ini = 0;
                    
                    echo "<tr><td>Grabe horario profesor: ".$row['idEmp']." dia: ".$dia." hora: ".$horario."</td></tr>";
                   //
                   //grabo los valores de h_ini y h_fin anteriores
                   //establezco como h_ini y h_fin este nuevo valor            
        }
    }

   
    
}

?>
