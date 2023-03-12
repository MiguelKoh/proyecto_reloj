<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");        
      
?>

<!-- http://ProgramarEnPHP.wordpress.com -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Control de asistencias 1.0</title>
    <link href="css/estilos.css" rel="stylesheet" type="text/css"/>    
</head>

<body style="text-align: center">
    <!-- FORMULARIO PARA SOICITAR LA CARGA DEL EXCEL -->

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
                        <form form action="" method="post" name="Borrar_registros_maestros_parciales" >
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><h3>Eliminar informaci&oacute;n de los periodos de examenes y revisiones dados de alta</h3></td>
                            </tr>   
                            <tr>
                                <td align="right">
                                    <a href='OpParciales.php'><img src='imagen/regresar2.jpg' alt='Menu de parciales' width='50' height='50' border='0' /></a>
                                    <a href='Menu.php'><img src='imagen/home.png' alt='Menu principal' width='50' height='50' border='0' /></a>
                                </td>      
                            </tr>                             
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>Se eliminar&aacute; la informaci&oacute;n del periodo registrado</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>Seleccione el examen o retroalimentaci&oacute;n que desea eliminar</td></tr>
                            <tr>
                                <td style="text-align:letf;vertical-align:middle">
                                    
                                    <select name="lstParciales" id="lstPeriodo">                                        
                                        <?php
                                        $resultado = listaParciales();
                                        echo $resultado;

                                        ?>
                                    </select>
                                </td>
                            </tr>                               
                            <tr><td>&nbsp;</td></tr> 
                            <tr><td><input type="submit" name="borrar"  value="Borrar"  /></td></tr>   
                            <tr><td>&nbsp;</td></tr>
                            <?php
                                if (isset($_POST['borrar'])){
                                    $cn = ConectaBD();

                                    //rescatamos el periodo
                                    //-----------------------------------------------------------------
                                    $id_parcial = mysql_real_escape_string(trim($_POST["lstParciales"]),$cn);  
                                    
                                    //verifico si no hay maestros cargado de ese periodo en tabla maestros_parciales
                                    $sente = "SELECT * FROM maestros_parciales WHERE idParcial = " . $id_parcial;
                                    $result = mysql_query($sente,$cn);
                                    
                                    if ($row = mysql_fetch_array($result)){
                                        echo '<script>alert ("No se puede eliminar el registro ya que hay maestros capturados para ese examen. ' .
                                                ' Elimine la carga de maestros que cuidaran el examen e intentelo de nuevo.")</script>'; 
                                        echo "<script>location.href='OpParciales.php'</script>";

                                    }else{    
                                        if ($id_parcial){
                                            //obteniendo descripcion del parcial
                                            $sente = "SELECT descripcion FROM parciales WHERE idParcial = " . $id_parcial;
                                            $result = mysql_query($sente,$cn);

                                            if ($row = mysql_fetch_array($result)){
                                                $desc_parcial = $row['descripcion'];
                                            }

                                            //borrando registros de maestros en parciales
                                            $sente = "select  count(*) as cantidad from parciales where idParcial = " . $id_parcial;
                                            $result = mysql_query($sente, $cn);
                                            $row = mysql_fetch_array($result);
                                            $cant_registros = $row['cantidad'];

                                            $sente = "DELETE FROM parciales where idParcial = " . $id_parcial;
                                            $result = mysql_query($sente, $cn);

                                            echo "<tr><td>Se borro " . $cant_registros . " en la tabla parciales del : " . $desc_parcial . "</td></tr>";

                                            echo '<script>alert ("Los registros se borraron correctamente")</script>';

                                            echo "<script>location.href='OpParciales.php'</script>";
                                        }else{
                                            echo '<script>alert ("Seleccione el periodo que desea eliminar")</script>';
                                        }

                                    }
                                }
                            ?>                                                                                 
                        </form>
                    </table>
                </td>
            </tr>
            

        </table>

</body>
</html>