<?php

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
    
    // ciclo uno para sacar los horarios de deportivo y grabarlos en la tabla catalogo_horarios
    while (($data = fgetcsv($handle, 1000, ",")) != FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;
        
        if ($row != 1){                                                      
                        
            for ($c=0; $c < $num; $c++) {
              
                if ($c == 1){
                    $materia = $data[$c];
                }
                if ($c == 2){
                    $idDia = $data[$c];
                }                
                if ($c == 3){
                    $horario = $data[$c];                    

                   // echo "<tr><td>".$horario."</td></tr>";
                    //obtengo hora_ini y hora_fin
                  /*  $separar = explode('-',$horario);
                    $hora_ini = $separar[0];
                    $hora_fin = $separar[1];

                    //formateo el horario
                    $horario = $hora_ini . " A " . $hora_fin;*/

                    //verificamos si el horario existe en la tabla
                    $sente1 = "SELECT * from `catalogo_horarios` WHERE id_c_tipo_horario = 3 and descripcion = '".$horario."'";
                    $result1 = mysql_query($sente1,$cn);
                    $row1 = mysql_fetch_array($result1);
                    
                    if ($row1){                    
                        //ya existe el horario
                        $id_c_horario = $row1['id_c_horario'];
                        
                    }else{
                        //verifico el maximo horario registrado para tipo 3 y le aumento 1
                        $sente2 = "SELECT max(id_c_horario) as id_c_horario FROM `catalogo_horarios` WHERE id_c_tipo_horario = 3";
                        $result2 = mysql_query($sente2,$cn);
                        $row2 = mysql_fetch_array($result2);
                        
                        if ($row2['id_c_horario']){
                            $id_c_horario = $row2['id_c_horario'] + 1;
                        }else{
                            //no existen registros de tipo 3
                            $id_c_horario = 1;
                        }

                        $sente3 = "INSERT INTO `catalogo_horarios` (`id_c_horario`,`id_c_tipo_horario`,`descripcion`,`hora_ini`,`hora_fin`)".
                                " VALUES (" . $id_c_horario . ",3,'" . $horario . "','" . $hora_ini . "','" . $hora_fin . "')";
                        $result3 = mysql_query($sente3,$cn);  
                

                    }                    
                }    
                if ($c == 5){
                    $idEmp = $data[$c];
                }                  
                
                //solo si es el final de archivo
                if ($c==($num-1)){

                    //grabo horario de maestro en tabla c_horarios_temporal
                    $sente = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_asignatura`,`id_c_tipo_horario`,`id_curso`,".
                             "`id_semestre`,`origen`,`descripcion`,`semana_inicio_descarga`,`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,".
                             "`grado`,`seccion`,`materia`) VALUES('" . $idEmp . "',".$idDia.",".
                            //iam10Jul17--  $id_c_horario.",0,3,".$id_curso.",".$id_semestre.",'SISCAP_DEP','',0,'04/01/2017','18/07/2017',5,1,0,0,'".$materia."')";
                            $id_c_horario.",0,3,".$id_curso.",".$id_semestre.",'SISCAP_DEP','',0,'07/08/2017','31/12/2017',5,1,0,0,'".$materia."')";                                                                            
                    $result = mysql_query($sente,$cn);                                         
                            echo "<tr><td>".$sente."</td></tr>";
                }
            }

        }
    }
    mysql_close($cn);
    fclose($handle);
    
    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
   // echo "<script>location.href='Menu_Carga_Horarios.php'</script>";
    


}
?>
