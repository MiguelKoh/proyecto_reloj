<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Control de asistencias 1.0</title>
        <link href="css/estilos.css" rel="stylesheet" type="text/css"/>        
    </head>
    <body style="text-align: center">
        <table border="0" style="text-align:center;background-color: white" width="100%">
            <tr>
                <td colspan="3">
                    <img alt="Preparatoria Dos - UADY" src="imagen/logo2.jpg" >
                </td>
            </tr>
            <tr>
                <td style="width:10%">

                </td>
                <td align="left">
                    <table width="80%" cellpadding="1" cellspacing="1" border="0">
        
                        <form action ="" method="post"> 
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><h3>Menu de parciales, ordinarios y periodos de revisi&oacute;n </h3></td>
                            </tr>   
                            <tr>
                                <td align="right">
                                    <a href='Menu.php'><img src='imagen/regresar2.jpg' alt='Menu principal' width='50' height='50' border='0' /></a>
                                    <a href='Menu.php'><img src='imagen/home.png' alt='Menu principal' width='50' height='50' border='0' /></a>
                                </td>      
                            </tr>                                                  
                            <tr><td><a href="altaParciales.php"><li><h3>Alta de periodo de parciales, ordinario y revisiones </h3></li></a></td></tr>                            
                            <tr><td><a href="importarParciales.php"><li><h3>Importar maestros que deben asistir al examen </h3></li></a></td></tr>                            
                            <tr><td><a href="Borrar_periodo_parciales.php"><li><h3>Borrar periodos registrados </h3></li></a></td></tr>                                                        
                            <tr><td><a href="Borrar_maestros_parciales.php"><li><h3>Borrar maestros que deben asistir al examen </h3></li></a></td></tr>                            
                            <tr><td><a href="reporte_maestros_parciales.php"><li><h3>Consultar maestros que deben asistir al examen </h3></li></a></td></tr>                            
                            <?php
                            // put your code here
                            ?>
                        </form>
                   </table>
                </td>
            </tr>
        </table>
    </body>
</html>
