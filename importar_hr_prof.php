<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    
    $cn = ConectaBD();
    
    $variables=Request("idCurso,idSemestre");
 
    if ($variables[1][2]) {$idCurso    = $variables[1][1];} else {$idCurso = 0;}  
    if ($variables[2][2]) {$idSemestre    = $variables[2][1];} else {$idSemestre = 0;} 
    
    
    if(isset($_POST['importar'])){
        echo "<script>location.href='subirInfoCsv_hr_prof.php'</script>";
    }
       
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
                        <form name="importa" method="post" enctype="multipart/form-data" action="SubirInfoCsv_hr_prof.php" >
                            <tr><td style="text-align:center;vertical-align:middle"><h3>Importar horario de profesores </h3></td></tr>
                            <tr>
                                <td align="right">
                                    <a href='Menu_Carga_Horarios.php'><img src='imagen/regresar2.jpg' alt='Menu subir informacion' width='50' height='50' border='0' /></a>
                                    <a href='Menu.php'><img src='imagen/home.png' alt='Menu principal' width='50' height='50' border='0' /></a>
                                </td>      
                            </tr>                             
                            <tr>
                                <td>Seleccione el curso escolar</td>
                            </tr>

                            
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>Seleccione el semestre</td>
                            </tr>                            

                            <tr>
                                <td>Seleccione el archivo con extensi&oacute;n CSV que importar&aacute;</td>
                            </tr>                                                                                      
                            <tr>
                                <td><input type="file" name="excel" id="excel"/> </td>
                            </tr> 
                            <tr><td>&nbsp;</td></tr>                                 
                            <tr>
                                <td><input type="submit" name="importar"  value="Importar"  /></td>
                            </tr>                            
                            <tr><td><input type="hidden" value="upload" name="action" /></td></tr>
                        </form>
                        <!-- CARGA LA MISMA PAGINA MANDANDO LA VARIABLE upload -->
    
                    </table>
                </td>
            </tr>
        </table>

</body>
</html>