<?php


/* Código que lee un archivo .csv con datos, para luego insertarse en una base de datos, vía MySQL
*  Gracias a JoG
*  http://gualinx.wordpress.com
*/   

session_start();

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

    echo '<script>alert("Verifique:\n-El archivo no tiene extension csv.\n-El rango de fechas no coincide con una quincena.\nIntente de nuevo.")</script>';
    echo "<script>location.href='importarChecadas.php'</script>";    
}else{

    $destino = $uploaddir . "bak_" . $archivo;

    //---- inicia proceso de copia -----    
    if (copy($_FILES['excel']['tmp_name'], $destino))
        echo "Archivo Cargado Con Exito";
    else
        echo "Error Al Cargar el Archivo";   

    
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
    
	//echo "<tr><td>".$sente."</td></tr>";
	
    $fecha_ini = $row['fecha_inicio'];
    $fecha_fin = $row['fecha_fin'];
    
    // las asignamos a variables de sesion
    $_SESSION['fechaini'] = $fecha_ini;
    $_SESSION['fechafin'] = $fecha_fin;
    $_SESSION["id_periodo"] = $id_periodo;
    
    
    //-----------------------------------------------------------------         
    
    // ----------- iniciamos --------------
    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;


        if ($row != 1){       
            
            $sente = "INSERT INTO `checadas` (`idEmp`,`fecha`,`hora`,`tipo`,`estatus`,`idperiodo`) ";
                                  
            for ($c=0; $c < $num; $c++) { //Aquí va colocando los campos en la cadena, si aun no es el último campo, le agrega la coma (,) para separar los datos
                //idendificando cada uno de los campos que se grabaran en tablas diferentes
                
                if ($c == 0){
                    $idEmp = $data[$c];
                }
                
                if ($c == 2){
                    $a = $data[$c];
                   // 14/08/2012 01:00 p.m.
                   $separar=explode(' ',$a);  
                    
                    $fecha = $separar[0];
                    $horap = $separar[1];
                    $momento = $separar[2];
                    
                    
                    if ($momento == "p.m."){
                        $separar = explode (':',$horap);
                        $hora = $separar[0];
                        $minuto = $separar[1];
                        
                        if ($hora == "12"){
                            $horaf = $horap;
                        }else{
                            $horaf = $hora + 12 . ":" . $minuto; //aumenta 12 horas a la tarde
                        }
                                                
                    }else{
                        $horaf = $horap;
                    }             
            
                }
                
                if ($c == 3){
                    $tipo = $data[$c];
                }
                
                if ($c == 5){
                    $estatus = $data[$c];
                }                

            }
            
            //------ grabo registro en tabla de checadas--------

            $sente = $sente . " VALUES ('" . $idEmp . "','" . $fecha . "','" . $horaf . "','". $tipo . "','" . $estatus . "','" . $id_periodo . "');"; //Termina de armar la cadena para poder ser ejecutada
            $result=mysqli_query($cn,$sente); //Aquí está la clave, se ejecuta con MySQL la cadena del insert formada
          //  echo "<tr><td>".$sente."</td></tr>";                         
        }
    }
    
    mysqli_close($cn);
    fclose($handle);
    
    echo '<script>alert("Los registros se grabaron correctamente en la base de datos.")</script>';
    echo "<script>location.href='CompletarChecadas.php'</script>";


}
?>
