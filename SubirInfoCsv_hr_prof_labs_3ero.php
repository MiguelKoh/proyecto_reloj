<?php


/* Código que lee un archivo .csv con datos, para luego insertarse en una base de datos, vía MySQL
*  Gracias a JoG
*  http://gualinx.wordpress.com
*/   



$uploaddir = "uploads/";
$archivo = $_FILES['excel']['name'];
$tipo = $_FILES['excel']['type'];

$fecha = date("d/m/Y g:i:s"); ; ;

//verifico que el registro sea un csv
if (strpos($archivo,".csv") == 0){

    echo '<script>alert("El archivo no tiene extension csv.\nIntente de nuevo.")</script>';
    echo "<script>location.href='Menu_Carga_Horarios.php'</script>";    
}else{

    $destino = $uploaddir . "bak_" . $archivo;

    //---- inicia proceso de copia -----    
    if (copy($_FILES['excel']['tmp_name'], $destino))
        echo "Archivo Cargado Con Exito";
    else
        echo "Error Al Cargar el Archivo";   

    // ----------- iniciamos --------------

    include("conex.php");
    $cn = ConectaBD();

    //-----------------------------------------------------------------
    $id_curso = 6;// mysql_real_escape_string($_POST["idCurso"],$cn);
    $id_semestre = 11;//mysql_real_escape_string($_POST["idSemestre"],$cn);
    
    //-----------------------------------------------------------------    
    
    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;

        $id_c_tipo_horario = 0;
        
        if ($row != 1){                                                      
                        
            $sente = "INSERT INTO `c_horarios_temporal_labs` (`idEmp`,`id_Dia`,`id_c_horario`,`id_c_tipo_horario`,`id_asignatura`,`id_curso`," .
                    "`id_semestre`,`origen`,`descripcion`,`semana_inicio_descarga`,`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,".
                    "`grado`,`seccion`,`materia`) VALUES ("; //Cambia los valores 'CampoX' por el nombre de tus campos de tu tabla y colócales los necesarios
            for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                //idendificando cada uno de los campos que se grabaran en tablas diferentes

                if ($c == 0){
                    $idemp = $data[$c];
                }
                if ($c == 1){
                    $dia = $data[$c];
                }       
                if ($c == 2){
                    $horario = $data[$c]; 
                    $id_c_tipo_horario = 1;
                }                
                if ($c == 3){
                    $asignatura = $data[$c];
                    
                    //busco la semana en la que inicia la asignatura
                    $semana_inicio = 0;
                    $sente2 = "SELECT * FROM asignaturas_quincenales WHERE idAsignatura = " . $asignatura;
                    $result2 = mysql_query($sente2,$cn);
                    $row2 = mysql_fetch_array($result2);                    
                    
                    if ($row2){
                       $semana_inicio = $row2['semanaQueInicia'] ;
                    }
                            
                }              
                /*if ($c == 5){
                    $grado = $data[$c];
                }*/
                if ($c == 4){
                    $nombre_materia = $data[$c];
                }
                
                if ($c == 5){
                    $seccion = $data[$c];
                }
                if ($c == 6){
                    $semestre = $data[$c];
                }                

                if ($c == 7){
                    //el archivo ya trae si el laboratorio es o no quincenal
                    $es_quincenal = $data[$c];
                    if ($es_quincenal != "Quincenal"){
                        $semana_inicio = 0;
                    }
                }                
                
               //-------- armo el insert en la tabla de asistencias ------
                if ($c==($num-1)){
                
                $grado = 3;
                    
                $sente = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_c_tipo_horario`,".
                        "`id_asignatura`,`id_curso`,`id_semestre`,`origen`,`descripcion`,`semana_inicio_descarga`,".
                        "`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,`grado`,`seccion`,`materia`) VALUES (".
                        $idemp.",".$dia.",".$horario.",1,".$asignatura.",".$id_curso.",".$id_semestre.",'SISCAP_LABS',".
                        //iam10Jul17--  "'',".$semana_inicio.",'04/01/2017','18/07/2017',7,1,".$grado.",".$seccion.",'".$nombre_materia."'"; 
                        "'',".$semana_inicio.",'07/08/2017','31/12/2017',7,1,".$grado.",".$seccion.",'".$nombre_materia."'";                         
                }                
            }
            
            //------ grabo registro en tabla de registros--------

            
            $sente = $sente.");"; //Termina de armar la cadena para poder ser ejecutada
            echo "<tr><td>".$row."</td><td>".$sente."</td></tr>";
            $result=mysql_query($sente, $cn); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada            
            
        }
    }
    
   /*
    //definiendo si el laboratorio es o no quincenal para el maestro y que semana
    $sente = "SELECT * FROM  c_horarios_temporal_labs WHERE id_semestre = " . $id_semestre . 
            " ORDER BY idemp,id_dia,id_c_horario,id_asignatura";
    $result = mysql_query($sente,$cn);
    
    echo $sente;
    
    $idemp = "";
    $id_dia = "";
    $id_c_horario = "";
    $asignatura = "";
    $fecha_ini = "";
    $fecha_fin = "";
    
    while ($row = mysql_fetch_array($result)){
        if ($idemp == "" ){
            //primer o nuevo registro
            $idemp = $row['idEmp'];
            $id_dia = $row['id_dia'];
            $asignatura = $row['id_asignatura'];
            $id_c_horario = $row['id_c_horario'];
            $fecha_ini = $row['fecha_ini'];
            $fecha_fin = $row['fecha_fin'];              
        }else {
            if ($idemp == $row['idEmp'] && $id_dia == $row['id_dia'] && $id_c_horario == $row['id_c_horario']){
                
                //el horario del laboratorio es semanal
                $sente1 = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_c_tipo_horario`,".
                        "`id_asignatura`,`id_curso`,`id_semestre`,`origen`,`descripcion`,`semana_inicio_descarga`,".
                        "`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,`grado`,`seccion`,`materia`) VALUES (".
                        $idemp.",".$id_dia.",".$id_c_horario.",1,".$asignatura.",".$id_curso.",".$id_semestre.",'SISCAP_LABS',".
                        "'',0,'".$fecha_ini."','".$fecha_fin."',7,1,".$row['grado'].",".$row['seccion'].",'".$row['materia']."')"; 
                $result1 = mysql_query($sente1,$cn);
                echo $sente1;
                
                $idemp = "";
                
                
            }else{
                //el horario es semanal
                
                //busco la semana en la que inicia la asignatura
                $sente2 = "SELECT * FROM asignaturas_quincenales WHERE idAsignatura = " . $asignatura;
                $result2 = mysql_query($sente2,$cn);
                $row2 = mysql_fetch_array($result2);
                
                if ($row){
                    $sente1 = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_c_tipo_horario`,".
                            "`id_asignatura`,`id_curso`,`id_semestre`,`origen`,`descripcion`,`semana_inicio_descarga`,".
                            "`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,`grado`,`seccion`,`materia`) VALUES (".
                            $idemp.",".$id_dia.",".$id_c_horario.",1,".$asignatura.",".$id_curso.",".$id_semestre.",'SISCAP_LABS',".
                            "'',".$row2['semanaQueInicia'].",'".$fecha_ini."','".$fecha_fin."',7,1,".$row['grado'].",".$row['seccion'].",'".$row['materia']."')";                     
                    $result1 = mysql_query($sente1,$cn);
                    echo $sente1;
                }

                //primer o nuevo registro
                $idemp = $row['idEmp'];
                $id_dia = $row['id_dia'];
                $asignatura = $row['id_asignatura'];
                $id_c_horario = $row['id_c_horario'];
                $fecha_ini = $row['fecha_ini'];
                $fecha_fin = $row['fecha_fin'];                  
            }
        }
    }
    */
    mysql_close($cn);
    fclose($handle);
    
    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
    //echo "<script>location.href='Menu_Carga_Horarios.php'</script>";
    


}
?>
