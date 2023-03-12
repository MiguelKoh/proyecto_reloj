<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Control de asistencias 1.0</title>
        <link href="../adds/estilo.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../adds/lib.js">;</script>

        <style type="text/css">
            <!--
            body {
                background-image: url(../imagenes/fondo.gif);
            }
            .Estilo13 {font-family: "Times New Roman", Times, serif; font-size: 16px; }
            .Estilo14 {font-size: 16px}
            .Estilo15 {
                font-size: 18px;
                font-weight: bold;
            }
            .Estilo16 {color: #FF0000}
            -->
        </style></head>

    <?php
    include("conex.php");
    $link = ConectaBD();
    ?>
    
    <!--Comienza area de Contenido -->
    <body>
        <table border="0" style="text-align:center;background-color: white" width="100%">
            <tr>
                <td colspan="3">
                    <img alt="Preparatoria Dos - UADY" src="imagen/logo2.jpg" />
                </td>
            </tr>
            <tr>
                <td style="width:10%">

                </td>
                <td align="left">
                    <table width="100%" cellpadding="1" cellspacing="1" border="0">
                    <!--Menu.php -->
                        <form action="Menu.php" method="post" id="Menu" >
                                <tr>
                                    <td>&nbsp;</td>
                                </tr> 
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>                             
                                <tr>
                                    <td align="center"><p class="Estilo15"> Control de asistencias 1.0 </p></td>                
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr> 
                                <tr>
                                    <td colspan="4" align="center"><input type="submit" name="Submit" value="Ingresar"/></td>
                                </tr>            

                        </form>
                    </table> 
                </td>
            </tr>
        </table>
        
    </body>
</html>
