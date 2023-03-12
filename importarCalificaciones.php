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
                    <table width="100%" cellpadding="1" cellspacing="1" border="0">
                        <form name="importa" method="post" enctype="multipart/form-data" action="subirInfoCalifs.php" >                              
                            <tr><td>Seleccione el archivo con extension csv que subira</td></tr>
                            <tr><td>&nbsp;</td></tr>                                                                                        
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