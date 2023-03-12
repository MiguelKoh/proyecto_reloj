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
                        
            $sente = "INSERT INTO `excel` (`idEmp`,`nombre`,`fecha`,`horario`,`horaIni`," .
                    "`horaFin`,`checadaIni1`,`checadaFin1`,`normal`,`tiempoReal`,`tarde`," .
                    "`temprano`,`ausente`,`otTime`,`jornadaTrabajada`,`excepcion`,`debeCEntrada`," .
                    "`debeCSalida`,`departamento`,`nDias`,`finSemana`,`feriado`,`attTime`," .
                    "`nDiasOT`,`finSemanaOT`,`feriadoOT`,`fechaSubido`) VALUES ("; //Cambia los valores 'CampoX' por el nombre de tus campos de tu tabla y colócales los necesarios
            for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                //idendificando cada uno de los campos que se grabaran en tablas diferentes
                
                if ($c == 0){
                    $idEmp = $data[$c];
                }
                
                if ($c == 1){
                    $nomEmp = $data[$c];
                }
                
                if ($c == 18){
                    $depto = $data[$c];
                }
                
                //-------- armo el insert en la tabla de asistencias ------
                if ($c==($num-1))
                      $sente = $sente."'".$data[$c] . "','" . $fecha . "'";
                else
                      $sente = $sente."'".$data[$c] . "',";
            }
            
            //------ grabo registro en tabla de existencias--------
            $sente = $sente.");"; //Termina de armar la cadena para poder ser ejecutada
            $result=mysql_query($sente, $cn); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada
            
            
            //------ inserto registro a tablas auxiliares -------
            
            //-------------departamento -------------------
            $sente1 = sprintf("select idDepto from departamento where nombre = '%s'",$depto);
            $result1 = mysql_query($sente1,$cn);
            $row1 = mysql_fetch_array($result1);            
            
            if (!$row1){
                //grabo el depto en la tabla
                $sente2 = "insert into departamento (nombre) values ('" . $depto . "')";
                $result2 = mysql_query($sente2); 
                
                //identifico el id que se le asigno al grabarlo en la tabla
                $sente3 = sprintf("select idDepto from departamento where nombre = '%s'",$depto);
                $result3 = mysql_query($sente3,$cn);
                $row3 = mysql_fetch_array($result3);                                
                
                $iddepto = $row3['idDepto'];
                
            }else{
                $iddepto = $row1['idDepto'];
            }           
            
            // ---------empleado --------
            // verifico si existe o no empleado en tabla y si no se da de alta
            $sente4 = sprintf("select idEmp from empleado where idEmp = '%s'",$idEmp);
            $result4 = mysql_query($sente4,$cn);
            $row4 = mysql_fetch_array($result4);
            
            if ($depto == "PROFESORES"){
                $idTipo = 1;
            }else{
                if ($depto == "ASESORES"){
                    $idTipo = 2;
                }else{
                    $idTipo = 3;
                }
            }
            
            if (!$row4){
                $sente5 = "insert into empleado values ('" . $idEmp . "','" . $nomEmp . "','" . $iddepto . "','" . $idTipo . "')";
                $result5 = mysql_query($sente5);                   
            }             
            
        }
    }

    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
    echo "<script>location.href='Menu.php'</script>";
    
    mysql_close($cn);
    fclose($handle);

}
?>
