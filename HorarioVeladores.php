<?php
    session_start(); // Use session variable on this page. This function must put on the top of page.
    
    include('conex.php');
    
    $fechaini1 = '2012-12-01';
    $fechafin1 = '2012-12-15';
    $idperiodo = 8;
       
    $cn = ConectaBD();
    
    //Obtengo lista de veladores
    $sente = "SELECT idEmp from empleado where idDepto = 17;";   
    $result = mysql_query($sente, $cn);  
        
    while ($row = mysql_fetch_array($result)) { 
        $idemp = $row['idEmp'];
        
        //obtengo el maximo id por empleado de la tabla veladores
        $sente1 = "select max(idvelador) as id_fecha_trabajo from veladores where idemp = ".$idemp;
        echo $sente1."<BR>";
        $result1 = mysql_query($sente1, $cn);  
        $row1 = mysql_fetch_array($result1);
    
        $max_id_velador = $row1['id_fecha_trabajo'];
        
        
        //con el id obtengo la ultima fecha registrada en la que debe trabajar
        $sente2 = "select fechastrabajo from veladores where idvelador = ".$max_id_velador;
        $result2 = mysql_query($sente2, $cn);  
        if ($row2 = mysql_fetch_array($result2)){ 
            $fechas_trabajo = $row2['fechastrabajo'];
        }
        
        //hago un for para grabar las fechas en las que el empleado debe trabajar
        for($i=1; $i <= 15; $i++){   
            
            //$fecha = date('Y-m-j');
            $nvaFecha = strtotime ( '+2 day' , strtotime ( $fechas_trabajo ) ) ;
            $nvaFecha = date ( 'Y-m-j' , $nuevafecha );

            echo $nvaFecha . " - " . $idemp . "<BR>";
        }
    }
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
