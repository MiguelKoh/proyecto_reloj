<?php


/* Código que lee un archivo .csv con datos, para luego insertarse en una base de datos, vía MySQL
*  Gracias a JoG
*  http://gualinx.wordpress.com
*/   
function esPar($numero){ 
    //determino si el numero es par o impar
   $resto = $numero%2; 
   if (($resto==0) && ($numero!=0)) { 
        return true; 
   }else{ 
        return false; 
   }  
}


$uploaddir = "uploads/";
$archivo = $_FILES['excel']['name'];
$tipo = $_FILES['excel']['type'];

$fecha = date("d/m/Y g:i:s"); ; ;

//verifico que el registro sea un csv
if (strpos($archivo,".csv") == 0){

    echo '<script>alert("El archivo no tiene extension csv.\nIntente de nuevo.")</script>';
    echo "<script>location.href='Menu.php'</script>";    
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
    
    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;


        if ($row != 1){                                                      
                        
            $sente0 = "INSERT INTO `calificaciones` (`libro`,`hoja`,`materia`,`fecha`," .
                    "`rfc`,`matricula`,`calif`) VALUES ("; //Cambia los valores 'CampoX' por el nombre de tus campos de tu tabla y colócales los necesarios
            for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                //idendificando cada uno de los campos que se grabaran en tablas diferentes
                
                if ($c == 0){
                    $libro = $data[$c];
                }
                
                if ($c == 1){
                    $hoja = $data[$c];
                }
                
                if ($c == 2){
                    $materia = $data[$c];
                }
                
                if ($c == 3){
                    $fecha = $data[$c];
                }
                
                if ($c == 4){
                    $rfc = $data[$c];
                }
                
                if ($c > 4){
                    $es_Par = esPar($c);
                    if ($es_Par == true){
                        $calif = $data[$c];
                        
                        $sente = $sente0 . "'" . $libro . "','" . $hoja . "','" . $materia . "','" . $fecha . "','" . $rfc . "','" . $matricula . "'," . $calif . ");";
                        
                        echo "<tr><td>" . $sente . "</td></tr>";
                        
                        $result=mysql_query($sente, $cn); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada    
                        
                        $matricula = "";
                        $calif = 0;
                    }else{
                        $matricula = $data[$c];
                    }
                }
                //-------- armo el insert en la tabla de asistencias ------
        /*        if ($c==($num-1))
                      $sente = $sente."'".$data[$c] . "','" . $fecha ."'," . $id_periodo;
                else
                      $sente = $sente."'".$data[$c] . "',";
         * 
         */
            }
            
            //------ grabo registro en tabla de registros--------
     /*       $sente = $sente.");"; //Termina de armar la cadena para poder ser ejecutada
            $result=mysql_query($sente, $cn); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada    
       */     
        }
    }
    mysql_close($cn);
    fclose($handle);
    
    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
    echo "<script>location.href='Menu.php'</script>";
    


}
?>
