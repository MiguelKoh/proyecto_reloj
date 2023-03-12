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
    echo "<script>location.href='ImportarParciales.php'</script>";    
}else{

    $destino = $uploaddir . "bak_" . $archivo;

    //---- inicia proceso de copia -----    
    if (copy($_FILES['excel']['tmp_name'], $destino))
        echo "El archivo se resplado con exito. ";
    else
        echo "Error Al Cargar el Archivo";   

    // ----------- iniciamos --------------

    include("funciones_reloj.php");
    include("conex.php");
    $cn = ConectaBD();

    if ($id_parcial = mysql_real_escape_string($_POST["lstParciales"],$cn)){

        //obtengo numero de semestre del parcial seleccionado
        $sente = "SELECT semestre from parciales where idparcial = " . $id_parcial;
        $result = mysql_query($sente,$cn);

        if ($row = mysql_fetch_array($result)){
            $id_semestre = $row['semestre'];
        }

       // echo "<tr><td>Id parcial".$id_parcial."</td></tr>";

        //-----------------------------------------------------------------    

        $row = 0;
        $row_depurada = 0;

        $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
            $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
            $row++;


            if ($row != 1){       

                $sente = "INSERT INTO `maestros_parciales` (`idParcial`,`numSemestre`,`idEmp`,`numAula`,`dia`,`fecha`,`hora_ini`,`hora_fin`,`asignatura`) " .
                            "VALUES (" . $id_parcial . "," . $id_semestre . ",";

                for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                    //idendificando cada uno de los campos que se grabaran en tablas diferentes

                    //-------- armo el insert en la tabla de asistencias ------
                    switch($c){
                        case 0: 
                            $idemp = $data[$c];
                            break;
                        case 1: 
                            $nombre_emp = $data[$c];
                            break;
                        case 2: 
                            $asignatura = $data[$c];
                            break;                        
                        case 3: 
                            $aula = $data[$c];
                            break;
                        case 4: 
                            $dia_semana = $data[$c];
                            break;
                        case 5: 
                            $fecha = $data[$c];
                            break;
                        case 6: 
                            $HH = $data[$c];
                            $hora_entrada = convertir_hora_12_a_24($HH);
                            break;
                        case 7: 
                            //$hora_salida = $data[$c];            
                            $HH = $data[$c];
                            $hora_salida = convertir_hora_12_a_24($HH);

                            break;
                    }                

                }                                       

                //------ grabo registro en tabla de maestros_parciales--------

                //modulo libre es un dato que se baja de la tabla del SISCAP pero no tiene maestro asignado
                //por lo que no se toma en cuenta

               // if ($nombre_emp != "MODULO LIBRE"){

                    $row_depurada ++;

                    $sente = $sente . $idemp . "," . $aula . "," . $dia_semana . ",'" . $fecha . 
                            "','" . $hora_entrada . "','" . $hora_salida . "','" . $asignatura . "');";
                    //echo $sente . " ----------";
                    $result=mysql_query($sente, $cn); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada

                //}
                //reiniciando valores
                $sente = "INSERT INTO `maestros_parciales` (`idParcial`,`numSemestre`,`idEmp`,`numAula`,`dia`,`fecha`,`hora_ini`,`hora_fin`,`asignatura`) " .
                            "VALUES (" . $id_parcial . "," . $id_semestre . ",";

            }
        }
        mysql_close($cn);
        fclose($handle);

        echo "Se grabaron " .$row_depurada . " registros en la base de datos";

        echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';    
        echo "<script>location.href='OpParciales.php'</script>";
    

    }else{
        echo '<script>alert("No se selecciono ningun periodo de parciales.")</script>';  
        echo "<script>location.href='ImportarParciales.php'</script>";
    }
}
?>
