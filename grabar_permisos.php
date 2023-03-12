<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>

<?php
    session_start();
    
    include('conex.php'); 
    $cn = ConectaBD();
  
   /* if (!(isset($_POST["idEmp"]))) {
        echo "<script>location.href='captura_permisos.php'</script>";
    }else{
    * 
    */
        $cn = ConectaBD();
        
        $idDepto = mysql_real_escape_string(trim($_POST["idDepto"]), $cn);
        $idEmp = mysql_real_escape_string(trim($_POST["idEmp"]), $cn);
        $fechaInicio = mysql_real_escape_string(trim($_POST["fechaInicio"]), $cn);
        $fechaFin = mysql_real_escape_string(trim($_POST["fechaFin"]), $cn);
        $horaIni = mysql_real_escape_string(trim($_POST["horaIni"]), $cn);
        $horaFin = mysql_real_escape_string(trim($_POST["horaFin"]), $cn);
        $tipoPermiso = mysql_real_escape_string(trim($_POST["tipoPermiso"]), $cn);
        $descPermiso = mysql_real_escape_string(trim($_POST["descPermiso"]), $cn);
        
        echo "<tr><td>" . $idDepto . "</td></tr>";
        echo "<tr><td>" . $idEmp . "</td></tr>";
        echo "<tr><td>" . $fechaInicio . "</td></tr>";
        echo "<tr><td>" . $fechaFin . "</td></tr>";
        echo "<tr><td>" . $horaIni . "</td></tr>";
        echo "<tr><td>" . $horaFin . "</td></tr>";
        echo "<tr><td>" . $tipoPermiso . "</td></tr>";
        echo "<tr><td>" . $descPermiso . "</td></tr>";
        
        
        
        
//    }
    
?>    