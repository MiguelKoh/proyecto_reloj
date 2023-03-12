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

    //--------*****************************************************************************************************
    $id_curso = 6;// mysql_real_escape_string($_POST["idCurso"],$cn);
    $id_semestre = 11;//mysql_real_escape_string($_POST["idSemestre"],$cn);
    
    //-------******************************************************************************************************   
    
    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;

        $id_c_tipo_horario = 0;
        
        if ($row != 1){                                                      
                        
            $sente = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_c_tipo_horario`,`id_asignatura`,`id_curso`," .
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
                   /* if ($horario == '9'){
                        $id_c_tipo_horario = 2;
                    }else{
                        $id_c_tipo_horario = 1;
                    }  */
                    $id_c_tipo_horario = 1;
                }                
                if ($c == 3){
                    $asignatura = $data[$c];
                }              
                if ($c == 4){
                    $grado = $data[$c];
                }
                if ($c == 6){
                    $seccion = $data[$c];
                }
                if ($c == 7){
                    $semestre = $data[$c];
                }                
                if ($c == 8){
                    $nombre_materia = $data[$c];
                }
                
               //-------- armo el insert en la tabla de asistencias ------
                if ($c==($num-1)){
                      $sente = $sente."".$idemp . "," .$dia.",". $horario.",".$id_c_tipo_horario .  "," . $asignatura. ",".$id_curso . "," . $id_semestre . 
                              //iam10072017-- ",'SISCAP_PROF','',0,'04/01/2017','18/07/2017',7,1,".$grado.",".$seccion.",'".$nombre_materia."'";
                              ",'SISCAP_PROF','',0,'07/08/2017','31/12/2017',7,1,".$grado.",".$seccion.",'".$nombre_materia."'";
                      echo $sente;
                }                
            }
            
            //------ grabo registro en tabla de registros--------

            
            $sente = $sente.");"; //Termina de armar la cadena para poder ser ejecutada
            echo $sente;
            $result=mysql_query($sente, $cn); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada            
            
        }
    }
    mysql_close($cn);
    fclose($handle);
    
    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
    //echo "<script>location.href='Menu_Carga_Horarios.php'</script>";
    


}
?>
