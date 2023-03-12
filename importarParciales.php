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
                        <form name="importa" method="post" enctype="multipart/form-data" action="subirInfoCsvParciales.php" >
                            <tr><td style="text-align:center;vertical-align:middle"><h3>Importar informaci&oacute;n de los maestros que cuidar&aacute;n examenes </h3></td></tr>
                            <tr>
                                <td align="right">
                                    <a href='OpParciales.php'><img src='imagen/regresar2.jpg' alt='Menu parciales ordinarios y revisiones' width='50' height='50' border='0' /></a>
                                    <a href='Menu.php'><img src='imagen/home.png' alt='Menu principal' width='50' height='50' border='0' /></a>
                                </td>      
                            </tr>   
                            <tr>
                                <td>Antes de subir la informaci&oacute;n, debes dar de alta el periodo del parcial, ordinario o retroalimentaci&oacute;n correspondiente</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>   
                            <tr><td>Seleccione el examen o retroalimentaci&oacute;n con el que trabajar&aacute;s</td></tr>
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
                            <tr><td>Seleccione el archivo con extension CSV que subir&aacute;s</td></tr>                                                                                        
                            <tr><td><input type="file" name="excel" id="excel"/> </td></tr> 
                            <tr><td>&nbsp;</td></tr>                                 
                            <tr><td><input type="submit" name="enviar"  value="Importar"  /></td></tr>                            
                            <tr><td><input type="hidden" value="upload" name="action" /></td></tr>
                        </form>
                        <!-- CARGA LA MISMA PAGINA MANDANDO LA VARIABLE upload -->
    
                    </table>
                </td>
            </tr>
        </table>

</body>
</html>