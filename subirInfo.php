

<?php
    include("conex.php");

    $uploaddir = "uploads/";

    extract($_POST);

    //cargamos el archivo al servidor con el mismo nombre
    //solo le agregue el sufijo bak_ 
    $archivo = $_FILES['excel']['name'];
    $tipo = $_FILES['excel']['type'];
    $destino = $uploaddir . "bak_" . $archivo;
    
    //conectamos con la base de datos 
    $cn = ConectaBD();    
    
    //----------verifica que el archivo no se haya cargado con anterioridad---------------
    /*$sql = sprintf("select nombrearchivo, fechacarga from bitacora where nombrearchivo = %d",$archivo);
    $result = mysql_query($sql,$cn);
    $row = mysql_fetch_array($result);
    */
    $sql = "select nombrearchivo, fechacarga from bitacora where nombrearchivo=" .$archivo;
    $result = mysql_query($sql,$cn);
    $row = mysql_fetch_array($result);   
    
    if (!$row){     

        //---- inicia proceso de copia -----    
        if (copy($_FILES['excel']['tmp_name'], $destino))
            echo "Archivo Cargado Con Exito";
        else
            echo "Error Al Cargar el Archivo";

        ////////////////////////////////////////////////////////
        if (file_exists($destino)) {
            /** Clases necesarias */
            require_once('PHPExcel.php');
            require_once('PHPExcel/Reader/Excel2007.php');

        // Cargando la hoja de cálculo
            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load($destino);
            $objFecha = new PHPExcel_Shared_Date();

        // Asignar hoja de excel activa
            $objPHPExcel->setActiveSheetIndex(0);



            // Llenamos el arreglo con los datos  del archivo xlsx
            for ($i = 2; $i <= 10000; $i++) {
                $_DATOS_EXCEL[$i]['idEmp'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['nombre'] = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['fecha'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['horario'] = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['horaIni'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['horaFin'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['checadaIni1'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['checadaFin1'] = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['normal'] = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['tiempoReal'] = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['tarde'] = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['temprano'] = $objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['ausente'] = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['onTime'] = $objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['jornadaTrabajada'] = $objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['excepcion'] = $objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['debeCEntrada'] = $objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['debeCSalida'] = $objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['departamento'] = $objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['nDias'] = $objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['finSemana'] = $objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['feriado'] = $objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['attTime'] = $objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['nDiasOT'] = $objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['finSemanaOT'] = $objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['feriadoOT'] = $objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue();                        

                if (strlen($_DATOS_EXCEL[$i]['idEmp']) == 0) {
                    break;
                } 
            }
        }
        //si por algo no cargo el archivo bak_ 
        else {
            echo "Necesitas primero importar el archivo";
        }
        $errores = 0;   
        $terminar = 0;

        //recorremos el arreglo multidimensional 
        //para ir recuperando los datos obtenidos
        //del excel e ir insertandolos en la BD
        foreach ($_DATOS_EXCEL as $campo => $valor) {
            $veces = 0;
            $idEmp = "";
            $depto = "";
            $sql = "INSERT INTO excel VALUES ('";
            foreach ($valor as $campo2 => $valor2) {
                $veces ++;             

                //verifica si el id del empleado esta vacio. 
                //Eso indica que la lectura de excel termino
                if ($veces == 1){
                    $idEmp = $valor2;
                    if ($valor2 == ""){

                        $terminar = 1;
                        break;
                    }
                }
                // arma el query con todos los campos
                if ($veces < 26) {
                    if ($veces == 2){
                        $nombre = $valor2;
                    } 
                    if ($veces == 19){
                        $depto = $valor2;
                    }
                    $sql.= $valor2 . "','";            
                }else {
                    $sql.= $valor2 ."')";
                }

            }
            

            // verifica si debe insertar o no el registro 
            if ($terminar == 0){
                $result = mysql_query($sql);
                if (!$result) {
                    echo "Error al insertar registro " . $campo . "= " . $sql;
                    $errores+=1;
                }
            }else {
                break;
            }
            
            // verifico si existe o no depto en tabla y si no se da de alta
            $sql2 = sprintf("select idDepto from departamento where nombre = '%s'",$depto);
            $result2 = mysql_query($sql2,$cn);
            $row2 = mysql_fetch_array($result2);
            
            if (!$row2){
                //grabo el depto en la tabla
                $sql2 = "insert into departamento (nombre) values ('" . $depto . "')";
                $result = mysql_query($sql2); 
                
                //identifico el id que se le asigno al grabarlo en la tabla
                $sql2 = sprintf("select idDepto from departamento where nombre = '%s'",$depto);
                $result2 = mysql_query($sql2,$cn);
                $row2 = mysql_fetch_array($result2);
            }
            
            // verifico si existe o no empleado en tabla y si no se da de alta
            $sql3 = sprintf("select idEmp from empleado where idEmp = '%s'",$idEmp);
            $result3 = mysql_query($sql3,$cn);
            $row3 = mysql_fetch_array($result2);
            
            if (!$row3){
                $sql3 = "insert into empleado values ('" . $idEmp . "','" . $nombre . "','" . $row2['idDepto'] . "')";
                $result = mysql_query($sql3);                   
            }            
            
        }
        /////////////////////////////////////////////////////////////////////////
        $campo = $campo - 2;
        echo "<strong><center>ARCHIVO IMPORTADO CON EXITO, EN TOTAL $campo REGISTROS Y $errores ERRORES</center></strong>";
        
        //---------------- actualizo bitacora ----------------------
        $fecha = date("Y-m-d H:i:s");
        $sql = "INSERT INTO bitacora (nombreArchivo,fechaCarga,regCargados,errores) VALUES('" . 
                $archivo . "','" . $fecha . "','" 
                . $campo . "','" . $errores . "')";
        $result = mysql_query($sql);

        if (!$result) {
            echo "Error al actualizar la bitacora";
            $errores+=1;
        }                

   } else {
       echo "EL ARCHIVO YA SE CARGO EL " . $row['fecha'] . "RECTIFICALO POR FAVOR.";
   }    
        //una vez terminado el proceso borramos el 
        //archivo que esta en el servidor el bak_
    unlink($destino);

?>