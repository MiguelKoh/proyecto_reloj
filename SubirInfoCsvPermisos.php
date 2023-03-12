<?php


/* Código que lee un archivo .csv con datos, para luego insertarse en una base de datos, vía MySQL
*  Gracias a JoG
*  http://gualinx.wordpress.com
*/   

//session_start();

$uploaddir = "uploads/";

$archivo = $_FILES['excel']['name'];
$tipo = $_FILES['excel']['type'];

$fecha = date("d/m/Y g:i:s"); 
    include("conex.php");
    $cn = ConectaBD();
    
    //rescatamos el periodo
    //-----------------------------------------------------------------
    //$id_periodo = mysqli_real_escape_string($cn,$_POST["lstPeriodo"]);
    $fechacai = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
    $fechacaf = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
    $BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechacai."' AND fecha_fin='".$fechacaf."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $exis=mysqli_num_rows($queryBusca);



//verifico que el registro sea un csv
if (strpos($archivo,".csv") == 0 || $exis==0){

    echo '<script>alert("Verifique:\n-El archivo no tiene extension csv.\n-El rango de fechas no concide con una quincena\nIntente de nuevo.")</script>';
    echo "<script>location.href='importarPermisos.php'</script>";    
}else{

    $destino = $uploaddir . "bak_" . $archivo;

    //---- inicia proceso de copia -----    
    if (copy($_FILES['excel']['tmp_name'], $destino))
        echo "Archivo Cargado Con Exito";
    else
        echo "Error Al Cargar el Archivo";   
   //------------------------------------------------------------------

   
    
    //rescatamos el periodo
    //-----------------------------------------------------------------
    //$id_periodo = mysqli_real_escape_string($cn,$_POST["lstPeriodo"]);
    $fechacai = mysqli_real_escape_string($cn,$_POST["fechaInicio"]);
    $fechacaf = mysqli_real_escape_string($cn,$_POST["fechaFin"]);
    $BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechacai."' AND fecha_fin='".$fechacaf."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $busca=mysqli_fetch_array($queryBusca);
    $id_periodo=$busca['idperiodo'];
    
    //obtengo fecha inicial y final del periodo para verificacion
    $sente = "select fecha_inicio, fecha_fin from periodos where idperiodo =" . $id_periodo;
    $result =  mysqli_query($cn,$sente);
    $row = mysqli_fetch_array($result);
    
    $fecha_ini = $row['fecha_inicio'];
    $fecha_fin = $row['fecha_fin'];
    
    //--------------- iniciamos -------------------------------

    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;
        
        if ($row != 1){                                                      
                        
            $sente = "INSERT INTO `permisos` (`nombre`,`idEmp`,`fechaIni`,`horaIni`,`fechaFin`," .
                    "`horaFin`,`tipo`,`motivo`,`horaCaptura`,`minutosDiarios`,`idperiodo`) VALUES ("; //Cambia los valores 'CampoX' por el nombre de tus campos de tu tabla y colócales los necesarios
            for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                //idendificando cada uno de los campos que se grabaran en tablas diferentes                                
                
                if ($c == 2){
                    
                    //con esto verifico si viene o no en blanco la hora
                    $pos_espacio = strpos($data[$c]," ");
                    
                    if ($pos_espacio == 0){
                        $data[$c]=$data[$c]." 00:01:00 a.m.";
                    }
                    
                    $separar=explode(' ',$data[$c]);
                    $fechaIni = $separar[0]; //12/08/2012
                    $horaIni = $separar[1]; //11:00:00
                    $formatoIni = $separar[2]; // a.m
                    
                    //   ------ hora de inicio ----                
                    
                    $separar=explode(':',$horaIni);
                    $hora1 = $separar[0]; //11
                    $minuto1 = $separar[1];   //00  
                    
                    if ($formatoIni == 'p.m.'){
                        if ($hora1 != '12') {
                            $hora1 = $hora1 + 12;
                        }                        
                    }
                                        
                    $horaInit = $hora1 . ":" . $minuto1;
                    
                    $totMinsIni = ($hora1 * 60) + $minuto1;
                    
                    $horaIniF = $fechaIni . "','" . $horaInit;
                    
                    if($horaIniF == ":"){
                        $horaIniF = "00:01";
                    }
                    
                    $data[$c] = $fechaIni . "','" . $horaInit; //para grabar el insert
                }
                
                if ($c == 3){
                    $separar=explode(' ',$data[$c]);
                    $fechaFin = $separar[0];  // 12/08/2012
                    $horaFin = $separar[1]; //12:00:00
                    $formatoFin = $separar[2]; // a.m   
                    
                    //   ------ hora de fin ----                
                    $separar=explode(':',$horaFin);
                    $hora1 = $separar[0]; //11
                    $minuto1 = $separar[1];   //00                
                    
                    if ($formatoFin == 'p.m.'){
                        $hora1 = $hora1 + 12;
                    }

                    $horaFint = $hora1 . ":" . $minuto1;  
                    
                    $totMinsFin = ($hora1 * 60) + $minuto1;
                    
                    $data[$c] = $fechaFin . "','" . $horaFint;
                    
                    //ya con el dato de hora inicio y fin, calculo los minutos diarios del permiso.
                    //$total_min_diarios = date("H:i", strtotime("00:00") + strtotime($horaFint) - strtotime($horaInit) );                      
                    $total_min_diarios = $totMinsFin - $totMinsIni;
                }    
                
                //-------- armo el insert en la tabla de asistencias ------
                if ($c==($num-1))
                      $sente = $sente."'".$data[$c] . "','" . $total_min_diarios . "'," . $id_periodo;
                else
                      $sente = $sente."'".$data[$c] . "',";
            }
            
            //------ grabo registro en tabla de permisos--------
            $sente = $sente.");"; //Termina de armar la cadena para poder ser ejecutada           
            //echo "<tr><td>" . $sente . "</td></tr>";
            $result=mysqli_query($cn,$sente); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada
                                  
            
        }
    }

   echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
   echo "<script>location.href='importarPermisos.php'</script>";
    
    mysqli_close($cn);
    fclose($handle);
    session_destroy();

}
?>
