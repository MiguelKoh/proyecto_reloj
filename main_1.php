<?php
session_start();
include("conex.php");

$cn = ConectaBD();

function listaDeptos() {
    $cn = ConectaBD();
    $depto = "";
    
    $depto = $depto . "<option value=0>TODOS</option>";
    try {
        $sente = "select IDDEPTO,NOMBRE
                         from departamento";
        $result = mysqli_query($cn,$sente);

        while ($row = mysqli_fetch_array($result)) {
            $texto = $row['NOMBRE'];
            $depto = $depto . "<option value=\"" . $row['IDDEPTO'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $depto = "";
    }
    mysqli_close($cn);

    return $depto;
}

function listaEmpleados() {
    $cn = ConectaBD();
    $empleado = "";
    
    $empleado = $empleado . "<option value=0>TODOS</option>";
    try {
        $sente = "select IDEMP,NOMBRE,IDDEPTO
                      from empleado";

        $result = mysqli_query($cn,$sente);

        while ($row = mysqli_fetch_array($result)) {
            $texto = $row['NOMBRE'];
            $empleado = $empleado . "<option value=\"" . $row['IDEMP'] . "\">" . $texto . "</option>";
        }
    } catch (Exception $e) {
        $empleado = "";
    }
    mysqli_close($cn);

    return $empleado;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Control de asistencias 1.0</title>

        <script language="javascript" type="text/javascript">

            function validarSelect(){
                  document.frmSelect.submit();
                }
        </script>

        <link href="css/estilos.css" rel="stylesheet" type="text/css"/>
    </head>
    <body style="text-align: center">

        <table border="0" style="text-align:center;background-color: white" width="100%">
            <tr style="height:15%">
                <td colspan="3">
                    <img alt="Preparatoria Dos - UADY" src="imagen/logo2.jpg" >

                </td>
            </tr>
            <tr style="height:85%">
                <td style="width:10%">

                </td>
                <td align="left">
                    <table width="100%" style="height:100%" cellpadding="1" cellspacing="1" border="0">
                        <form action="acceso.php" name="frmSelect" id="frmSelect" method="post">
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><b>Seleccione los datos que se piden </b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                                <td style="text-align:center;vertical-align:middle">Departamento
                                    <select name="lstDepto" id="lstDepto">
                                        <option value="0">TODOS</option>
                                        <?php
                                        $resultado = listaDeptos();
                                        echo $resultado;

                                        ?>
                                    </select>
                                </td>
                            </tr>
                                                      
                            <tr>
                                <td style="text-align:center;vertical-align:middle">Empleado
                                    <select name="lstEmpleado" id="lstEmpleado">
                                        <option value="0">TODOS</option>
                                        <?php
                                        $resultado2 = listaEmpleados();
                                        echo $resultado2;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                           
                            <tr>
                                <td style="text-align:center;vertical-align:middle">Fecha inicio periodo:
                                    <input type="text" name="dFechaInicio" value="<?php echo date("d/m/Y") ?>">
                                </td>
                            </tr>                                     
                            <tr>
                                <td style="text-align:center;vertical-align:middle">Fecha final periodo: 
                                    <input type="text" name="dFechaFin" value="<?php echo date("d/m/Y") ?>">
                                </td>
                            </tr>      
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><b>Elija un reporte: </b></td>
                            </tr>  
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                                <td style="text-align:center;vertical-align:middle">  
                                    <input type="radio" name="chkop" value="1" checked/>Faltas, retardos y salidas antes de horario <br>
                                    <input type="radio" name="chkop" value="2"/>Todos los registros<br>
                                </td>
                            </tr>                            
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                            <tr>
                                <td style="text-align:center;vertical-align:middle">
                                    <input type="button" value="Procesar" id="btnRegis" name="btnProceso" onclick="validarSelect()"/>
                                </td>
                            </tr>


                        </form>
                    </table>
                    <script type="text/javascript">
                        <?php
                        $cn = ConectaBD();
                        //if ($_SESSION["iddepto"] != "") {
                        //    echo 'document.frmSelect.lstDepto.value=' . $_SESSION["iddepto"] . '; ';
                        //}
                        
                        $iddepto = mysqli_real_escape_string($cn,$_POST["iddepto"]);
                        $_SESSION = $iddepto;
                        
                        //if ($_SESSION["idemp"] != "") {
                        //    echo 'document.frmSelect.lstEmpleado.value=' . $_SESSION["idemp"] . '; ';
                        //}
                        
                        $idemp = mysqli_real_escape_string($cn,$_POST["idemp"]);
                        $_SESSION = $idemp;
                        ?>
                    </script>                  
                </td>
                <td style="width:10%">

                </td>
            </tr>
        </table>
    </body>
</html>

