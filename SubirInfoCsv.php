<?php


/* Código que lee un archivo .csv con datos, para luego insertarse en una base de datos, vía MySQL
*  Gracias a JoG
*  http://gualinx.wordpress.com
*/   



$uploaddir = "uploads/";
$archivo = $_FILES['excel']['name'];
$tipo = $_FILES['excel']['type'];

$fecha = date("d/m/Y g:i:s"); ; ;

include("conex.php");
$cn = ConectaBD();

    //rescatamos el periodo
    //-----------------------------------------------------------------
    //$id_periodo = mysqli_real_escape_string($cn,$_POST["lstPeriodo"]);

    $fechacai = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
    $fechacaf = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
     $BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechacai."' 
    AND fecha_fin='".$fechacaf."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $ext=mysqli_num_rows($queryBusca);



//verifico que el registro sea un csv
if (strpos($archivo,".csv") == 0 || $ext==0){

    echo '<script>alert("Verifique:\n-El archivo no tiene extension csv.\n -Las fechas no coinciden con una quincena\nIntente de nuevo.")</script>';
    echo "<script>location.href='importar.php'</script>";    
}else{

    $destino = $uploaddir . "bak_" . $archivo;

    //---- inicia proceso de copia -----    
    if (copy($_FILES['excel']['tmp_name'], $destino))
        echo "Archivo Cargado Con Exito";
    else
        echo "Error Al Cargar el Archivo";   

    // ----------- iniciamos --------------

    

    //rescatamos el periodo
    //-----------------------------------------------------------------
    //$id_periodo = mysqli_real_escape_string($cn,$_POST["lstPeriodo"]);

    $fechacai = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
    $fechacaf = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
     $BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechacai."' 
    AND fecha_fin='".$fechacaf."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $busca=mysqli_fetch_array($queryBusca);
    $id_periodo=$busca['idperiodo'];
        
    //obtengo fecha inicial y final del periodo para verificacion
    $sente = "select fecha_inicio, fecha_fin from periodos where idperiodo =" . $id_periodo;
    $result =  mysqli_query($cn,$sente);
    $row = mysqli_fetch_array($result);
    
    $fecha_ini = $row['fecha_inicio'];
    $fecha_fin = $row['fecha_fin'];
    //-----------------------------------------------------------------
    
    
    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;


        if ($row != 1){                                                      
                        
            $sente = "INSERT INTO `registros` (`idEmp`,`nombre`,`fecha`,`horario`,`horaIni`," .
                    "`horaFin`,`checadaIni1`,`checadaFin1`,`normal`,`tiempoReal`,`tarde`," .
                    "`temprano`,`ausente`,`otTime`,`jornadaTrabajada`,`excepcion`,`debeCEntrada`," .
                    "`debeCSalida`,`departamento`,`nDias`,`finSemana`,`feriado`,`attTime`," .
                    "`nDiasOT`,`finSemanaOT`,`feriadoOT`,`fechaSubido`,`idperiodo`) VALUES ("; //Cambia los valores 'CampoX' por el nombre de tus campos de tu tabla y colócales los necesarios
            for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                //idendificando cada uno de los campos que se grabaran en tablas diferentes
                
                if ($c == 0){
                    $idEmp = $data[$c];
                     /*$SQLdepartamento="SELECT d.idDepto,d.Nombre as Depto 
                     FROM empleado e 
                     LEFT JOIN departamento d
                     ON d.idDepto=e.idDepto WHERE idEmp='".$idEmp."'";
                     $query=mysqli_query($cn,$SQLdepartamento);
                     $departo=mysqli_fetch_array($query);
                    //$depto = $data[$c];
                     $depto =$departo['Depto']; 
                     $idDepto=$departo['idDepto'];*/
                 }
                
                if ($c == 1){
                    $nomEmp = $data[$c];
                }
                
                if ($c == 2){
                    $fecha = $data[$c];
                }
                
                if ($c == 3){
                    $descripcion = $data[$c];
                }
                
                if ($c == 4){
                    $horaIni = $data[$c];
                }
                
                if ($c == 5){
                    $horaFin = $data[$c];
                }                
                
                if ($c == 18){
                    //verifico de que departamento es el empleado
                    $SQLdepartamento="SELECT d.idDepto,d.Nombre as Depto 
                     FROM empleado e 
                     LEFT JOIN departamento d
                     ON d.idDepto=e.idDepto WHERE idEmp='".$idEmp."'";
                     $query=mysqli_query($cn,$SQLdepartamento);
                     $departo=mysqli_fetch_array($query);
                    //$depto = $data[$c];
                     $depto =$departo['Depto']; 
                     $idDepto=$departo['idDepto'];
                     $data[$c]=$depto;
                }
                
                //-------- armo el insert en la tabla de asistencias ------
                if ($c==($num-1))
                      $sente = $sente."'".$data[$c] . "','" . $fecha ."'," . $id_periodo;
                else
                      $sente = $sente."'".$data[$c] . "',";
            }
            
            //------ grabo registro en tabla de registros--------
            $sente = $sente.");"; //Termina de armar la cadena para poder ser ejecutada
            $result=mysqli_query($cn,$sente); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada
            
            
            //------ inserto registro a tablas auxiliares -------
            // ------------ horarios ---------------------
            $sente0 = "insert into horario_teorico (idEmp, fecha, descripcion, horaIni, horaFin, idperiodo) values('".
                        $idEmp . "','" . $fecha . "','" . $descripcion . "','" . $horaIni . "','" . $horaFin . "'," . $id_periodo . ")";            
            $result0 = mysqli_query($cn,$sente0);   
            
            //echo "<tr><td>" . $sente0 . "</td></tr>";
            
            //-------------departamento -------------------
            $sente1 = sprintf("select idDepto from departamento where idDepto = '%s'",$idDepto);
            $result1 = mysqli_query($cn,$sente1);
            $row1 = mysqli_fetch_array($result1);            
            
            if (!$row1){
                //grabo el depto en la tabla
                //$sente2 = "insert into departamento (Nombre) values ('" . $depto . "')";
                //$result2 = mysqli_query($cn,$sente2); 
                
                //identifico el id que se le asigno al grabarlo en la tabla
                $provi=37;
                $sente3 = sprintf("select idDepto from departamento where idDepto = '%s'",$provi);
                $result3 = mysqli_query($cn,$sente3);
                $row3 = mysqli_fetch_array($result3);                                
                
                $iddepto = $row3['idDepto'];
                
            }else{
                $iddepto = $row1['idDepto'];
            }           
            
            // ---------empleado --------
            // verifico si existe o no empleado en tabla y si no se da de alta
            $sente4 = sprintf("select idEmp from empleado where idEmp = '%s'",$idEmp);
            $result4 = mysqli_query($cn,$sente4);
            $row4 = mysqli_fetch_array($result4);
            
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
                $sente5 = "insert into empleado (idEmp,Nombre,idDepto,idTipo) values ('" . $idEmp . "','" . $nomEmp . "','" . $iddepto . "','" . $idTipo . "')";
                $result5 = mysqli_query($cn,$sente5);                   
            }             
            
        }
    }
    mysqli_close($cn);
    fclose($handle);
    
    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
    echo "<script>location.href='importar.php'</script>";
    


}
?>
