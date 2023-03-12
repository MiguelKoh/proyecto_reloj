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
    include("funciones_reloj.php");
    include("conex.php");
    
    $cn = ConectaBD();
    //-----------------------------------------------------------------
    $id_curso = 6;// mysql_real_escape_string($_POST["idCurso"],$cn);
    $id_semestre = 11;//mysql_real_escape_string($_POST["idSemestre"],$cn);
    //-----------------------------------------------------------------    
    $fecha_ini = '07/08/2017';
    $fecha_fin = '31/12/2017';
    $descripcion = "";
    $tipoEmpleado = "";
    $debeChecar = "";
    $tipoEmpleado = 3;
    
    $row = 0;
    $handle = fopen($destino, "r"); //Coloca el nombre de tu archivo .csv que contiene los datos
    
    // ciclo uno para sacar los horarios de deportivo y grabarlos en la tabla catalogo_horarios
    while (($data = fgetcsv($handle, 1000, ",")) != FALSE) { //Lee toda una linea completa, e ingresa los datos en el array 'data'
        $num = count($data); //Cuenta cuantos campos contiene la linea (el array 'data')
        $row++;
        
        if ($row != 1){                                                      
                        
            for ($c=0; $c < $num; $c++) { 
                if ($c == 0){
                    $depto = $data[$c];
                    //ubico el id del departamento
                    $sente = "SELECT idDepto FROM departamento WHERE Nombre = '".$depto."'";
                    $result = mysql_query($sente,$cn);
                    $row = mysql_fetch_array($result);
                    
                    $idDepto = 0;
                    if ($row){
                        $idDepto = $row['idDepto'];
                    }                    
                }                    
                if ($c == 2){
                    $idEmp = $data[$c];
                }     
                if ($c == 3){
                    $nombre_dia = $data[$c];
                    $idDia = id_dia($nombre_dia);
                }       
                
                if ($c == 4){
                    $horario = $data[$c];                                       
                }
                if ($c == 5){
                    $debeChecar = "";
                }     
                /*
                if ($c == 6){
                    $tipoEmpleado = 0;
                    if ($data[$c] == 'profesor'){
                        $tipoEmpleado = 1;
                    }
                    if ($data[$c] == 'tecnico'){
                        $tipoEmpleado = 2;
                    }
                    if ($data[$c] == 'admon'){
                        $tipoEmpleado = 3;
                    }
                }                      
                if ($c == 7){
                    $fecha_ini = '04/01/2016';//$data[$c];
                }
                if ($c == 8){
                    $fecha_fin = '15/07/2016';//$data[$c];
                }                
                if ($c == 9){
                    $descripcion = $data[$c];
                }*/
                //solo si es el final de archivo
                if ($c==($num-1)){
                    
                    if ($debeChecar <> 'NO'){

                        //obtengo hora_ini y hora_fin
                        $separar = explode(' A ',$horario);
                        $hora_ini = $separar[0];
                        $hora_fin = $separar[1];

                        //formateo el horario
                        $horario = $hora_ini . " A " . $hora_fin;

                        //verificamos si el horario existe en la tabla
                        $sente1 = "SELECT * from `catalogo_horarios` WHERE id_c_tipo_horario = 4 and descripcion = '".$horario."'";
                        $result1 = mysql_query($sente1,$cn);
                        $row1 = mysql_fetch_array($result1);

                        if ($row1){                    
                            //ya existe el horario
                            $id_c_horario = $row1['id_c_horario'];

                        }else{
                            //verifico el maximo horario registrado para tipo 3 y le aumento 1
                            $sente2 = "SELECT max(id_c_horario) as id_c_horario FROM `catalogo_horarios` WHERE id_c_tipo_horario = 4";
                            $result2 = mysql_query($sente2,$cn);
                            $row2 = mysql_fetch_array($result2);

                            if ($row2['id_c_horario']){
                                $id_c_horario = $row2['id_c_horario'] + 1;
                            }else{
                                //no existen registros de tipo 3
                                $id_c_horario = 1;
                            }

                            $sente3 = "INSERT INTO `catalogo_horarios` (`id_c_horario`,`id_c_tipo_horario`,`descripcion`,`hora_ini`,`hora_fin`)".
                                    " VALUES (" . $id_c_horario . ",4,'" . $horario . "','" . $hora_ini . "','" . $hora_fin . "')";
                            $result3 = mysql_query($sente3,$cn);                  

                        }                    

                        //grabo horario de maestro en tabla c_horarios_temporal
                        
                        $descripcion = '';
                        $sente = "INSERT INTO `c_horarios_temporal` (`idEmp`,`id_Dia`,`id_c_horario`,`id_asignatura`,`id_c_tipo_horario`,`id_curso`,".
                                 "`id_semestre`,`descripcion`,`semana_inicio_descarga`,`fecha_ini`,`fecha_fin`,`origen`,`id_depto`,`debe_checar`,`id_tipo_empleado`) VALUES('" . 
                                 $idEmp . "',".$idDia.",".$id_c_horario.",0,4,".$id_curso.",".$id_semestre.",'".$descripcion."',0,'".$fecha_ini. "','" . $fecha_fin . "','ADMON'," . $idDepto . 
                                 ",'" . $debeChecar . "'," . $tipoEmpleado . ")";
                        $result = mysql_query($sente,$cn);                                         
                                echo "<tr><td>".$sente."</td></tr>";
                    }
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
