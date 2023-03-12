<?php
        session_start();
	include("conex.php");
        
        function listaRegistrados(){
            
            $con=Conectarse();

            $sente = "select Folio,Nombres,ApePat,ApeMat,Curso,Direccion,".
                     "Correo,Tel1,Tel2,tipo,movimiento ".
                     "from aspirantes as a order by Folio";
            $resul = mysql_query($sente,$con) or die("No fue posible obtener la lista de registrados. Intente de nuevo.");           

            $linea = 0;
            $Num = 1;
            $veces = 0;
            
            while($row = mysql_fetch_array($resul)){                                              
                
                $veces ++;
                
                echo '<tr align="left" style="font-size:small">                    
                        <td>'. $row['Folio'].'</td>
                        <td>'. strtoupper($row['Nombres']).'</td>
                        <td>'. strtoupper($row['ApePat']).'</td>
                        <td>'. strtoupper($row['ApeMat']).'</td>
                        <td>'. $row['Curso'].'</td>
                        <td>'. strtoupper($row['Direccion']).'</td>
                        <td>'. $row['Correo'].'</td>    
                        <td>'. $row['Tel1'].'</td>
                        <td>'. $row['Tel2'].'</td>                             
                        <td>'. $row['movimiento'].'</td> 
                      </tr>';                

                $Num+=1;
            }//fin while

            mysql_free_result($resul);
          //  mysql_close($con);         
        }




?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Alumnos de 2do y 3ero inscritos</title>
        <link type="text/css" rel="stylesheet" href="Estilo_registro.css" />
        <script language="javascript" type="text/javascript">                        
        </script>
    </head>    
  <!--  <body body style="text-align:center; background-image: url(imagen/banner_quimica.jpg);background-repeat:no-repeat;background-position: top;">-->
    <body>
        <div id="Excel">                       
                        <form action="" name="frmAlumRegistrado" id="frmAlumRegistrado" method="post">                                                                                       
                                <table width="85%" style="border-color:graytext " border="1" align="center">
                                    <tr><h2><b>Alumnos inscritos a 2do y 3ero para curso escolar 2015-2016</b></h2></tr>
                                    <tr align="right"><a href="exportar_excel.php"><img src="Archivos/excel.jpg" alt="Descargar archivo a Excel"></a></tr>                                        
                                    
                                    <tr style="font-size:small" >
                                        <th style="width: 12%;background-color:activecaption ">Folio</th>                                     
                                        <th style="width: 12%;background-color:activecaption">Nombres</th>  
                                        <th style="width: 15%;background-color:activecaption">A.Paterno</th> 
                                        <th style="width: 15%;background-color:activecaption">A.Materno</th> 
                                        <th style="width: 5%;background-color:activecaption">Curso</th> 
                                        <th style="width: 15%;background-color:activecaption">Direcci&oacute;n</th> 
                                        <th style="width: 5%;background-color:activecaption">Email</th> 
                                        <th style="width: 5%;background-color:activecaption">Tel.1</th> 
                                        <th style="width: 5%;background-color:activecaption">Tel.2</th> 
                                        <th style="width: 5%;background-color:activecaption">Ficha </br>Pago</th> 
                                        
                                        
                                       <!-- <th style="width: 15%">Horario</th>-->
                                    </tr>
                                    <?php
                                        //$resultado = listaLicenciatura();
                                        //echo $resultado;
                                        listaRegistrados();
                                    ?>
                                </table>                                                               
                        </form>
        </div>
    </body>
</html>
