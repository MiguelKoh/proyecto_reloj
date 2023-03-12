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
    $semana_inicio = 0;
    $seccion = "0";
    $grado = "0";
    
    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;

        $id_c_tipo_horario = 1;
        
        
        if ($row != 1){                                                      
                                   
            for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                //idendificando cada uno de los campos que se grabaran en tablas diferentes
                
                if ($c == 0){
                    $idEmp = $data[$c];
                }          
                if ($c == 9){
                    $id_dia = $data[$c];
                }                
                if ($c == 10){
                    $id_c_horario = $data[$c];
                }

                if ($c == 11){
                    $descripcion = $data[$c];
                    //3-19¦                    
                    //ubicamos si la descripcion tiene guion para identificar el grado/seccion
               /*     $simbolo1 = '-';//chr(248); 
                    $simbolo2 = '.';//chr(166);
                     
                    $pos_guion = strpos($descripcion,$simbolo2);  
                    
                    if ($pos_guion == false){
                        $grado = 0;
                        $seccion = 0;
                    }else{
                        
                        $separar = explode($simbolo1,$descripcion);
                        $grado = $separar[0];
                        $seccion_tmp = $separar[1];

                       
                        $separar = explode($simbolo2,$seccion_tmp);
                        $seccion = $separar[0];
                        
                    }                    */
                }

                /*
                if ($c == 4){
                    $semana_inicio = $data[$c];
                    if ($semana_inicio == ""){
                        $semana_inicio = 0;
                    }
                }*/
                
                //-------- armo el insert en la tabla de asistencias ------
                if ($c==($num-1)){
                       
                    $sente = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_asignatura`,`id_c_tipo_horario`,`id_curso`," .
                              "`id_semestre`,`descripcion`,`semana_inicio_descarga`,`origen`,`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,`grado`,`seccion`".
                              ") VALUES (".$idEmp . "," .$id_dia. ",".$id_c_horario.",0,1" .  
                              "," . $id_curso . "," . $id_semestre . ",'" . $descripcion."','".$semana_inicio.
                              //iam10Jul10--  "','SISCAP_PLANEACION','04/01/2017','18/07/2017',7,1,".$grado.",".$seccion;  
                              "','SISCAP_PLANEACION','07/08/2017','31/12/2017',7,1,".$grado.",".$seccion;                              
                }
            }
            
            //------ grabo registro en tabla de registros--------
            $sente = $sente.");"; //Termina de armar la cadena para poder ser ejecutada
            $result=mysql_query($sente, $cn); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada 
            echo "<tr><td>".$sente."</td></tr>";
            
        }
    }
    
    
    
    //identifico cuales de las descargas corresponden al maestro, segun las cargas academicas que tenga    
  /*  $sente1 = "SELECT distinct idEmp,grado,seccion FROM c_horarios_temporal" .
              " WHERE id_curso = " . $id_curso . " and id_semestre = " . $id_semestre . 
              " and id_c_tipo_horario = 1";                                
    $result1 = mysql_query($sente1,$cn);
              
    while ($row1 = mysql_fetch_array($result1)){
  */
        // $idEmp = $row1['idEmp'];
    /*
    
         $sente2 = "SELECT * FROM `c_horarios_temporal_descarga` WHERE " . //idEmp = " . $idEmp . " and " .
                 " id_curso = " . $id_curso . " and id_semestre = " . $id_semestre .
                 " and grado <> 0 and seccion <> 0 ";
                // " and grado like '%".$row1['grado']."%' and seccion like '%".$row1['seccion']."%'";
         $result2 = mysql_query($sente2,$cn);
         
         
         while ($row2 = mysql_fetch_array($result2)){
            $sente3 = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_asignatura`,`id_c_tipo_horario`,`id_curso`," .
                    "`id_semestre`,`descripcion`,`semana_inicio_descarga`,`origen`,`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,`grado`,`seccion`".
                    ") VALUES (".$row2['idEmp'] . "," .$row2['id_dia']. ",".$row2['id_c_horario'].",0,1" .  
                    "," . $id_curso . "," . $id_semestre . ",'" . $row2['descripcion']."','".$row2['semana_inicio_descarga'].
                    "','SISCAP_DESCARGA','06/01/2014','15/07/2014',7,1,".$row2['grado'].",".$row2['grado'].")";                    
            $result3 = mysql_query($sente3, $cn);
            echo "<tr><td>".$sente3."</td></tr>";
         }
//    }    
    
     
    
    //grabo las descargas semanales y quincenales que no tienen seccion y grado especifico
    $sente2 = "SELECT * FROM `c_horarios_temporal_descarga` WHERE" .
                 " id_curso = " . $id_curso . " and id_semestre = " . $id_semestre.
                 " and grado = 0 and seccion = 0";
    $result2 = mysql_query($sente2,$cn);    

    while ($row2 = mysql_fetch_array($result2)){
          $sente3 = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_asignatura`,`id_c_tipo_horario`,`id_curso`," .
                    "`id_semestre`,`descripcion`,`semana_inicio_descarga`,`origen`,`fecha_ini`,`fecha_fin`,`id_depto`,`id_tipo_empleado`,`grado`,`seccion`".
                    ") VALUES (".$row2['idEmp'] . "," .$row2['id_dia']. ",".$row2['id_c_horario'].",0,1" .  
                    "," . $id_curso . "," . $id_semestre . ",'" . $row2['descripcion']."','".$row2['semana_inicio_descarga'].
                    "','SISCAP_DESCARGA','06/01/2014','15/07/2014',7,1,".$row2['grado'].",".$row2['grado'].")";                    
          $result3 = mysql_query($sente3, $cn);
          echo "<tr><td>".$sente3."</td></tr>";        
    }
    
     */
    mysql_close($cn);
    fclose($handle);
    
    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
  //echo "<script>location.href='Menu_Carga_Horarios.php'</script>";
    


}
?>
