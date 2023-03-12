<?php
session_start();

include("conex.php");
include("funciones_reloj.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Control de asistencias 1.0</title>
      
       <!-- <link href="css/estilos.css" rel="stylesheet" type="text/css"/> -->
        <link rel="stylesheet" type="text/css" href="select_dependientes.css">
        <link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />            
        <link rel="stylesheet" type="text/css" href="lib/thickbox.css" />        
        <link rel="stylesheet" href="date_input.css" type="text/css"> 
         <script language='javascript' src="js/popcalendar.js"></script>
        <script type="text/javascript">
            function validarSelect(){
                  document.frmMainSerSoc.submit();
                }
        </script>
        <script type="text/javascript">
            $(function() {
                $("#dFechaInicio").date_input();
                $("#dFechaFin").date_input();
            });
        </script> 
          <script language="javascript">

        function validate(){
        var fecha = document.getElementById("fechaInicio").value;
        var fechaF = document.getElementById("fechaFin").value;
                if( fecha == null || fecha.length == 0 || /^\s+$/.test(fecha) ) {
                        alert("Debes proporcionar la fecha de inicio");	
                        return false;
                }	

                        if( fechaF == null || fechaF.length == 0 || /^\s+$/.test(fechaF) ) {
                        alert("Debes proporcionar la fecha final");	
                        return false;
                }


                return true;
        }
        </script> 
        <script src="js/jquery.validationEngine-en.js" type="text/javascript"></script>
        <script src="js/jquery.validationEngine.js" type="text/javascript"></script>
        <script src="js/jquery.hotkeys-0.7.9.js"></script>        
    </head>
    <body style="text-align: center">
        <?php
     $variables=Request("tipoRep,fechaInicio,fechaFin");
 
    if ($variables[1][2]) {$tipoRep    = $variables[1][1];} else {$tipoRep = 0;}       
    if ($variables[2][2]) {$fechaInicio    = $variables[2][1];} else {$fechaInicio = "";} 
    if ($variables[3][2]) {$fechaFin    = $variables[3][1];} else {$fechaFin = "";} 
    ?>
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
                        <form action="" name="frmTipo" id="frmTipo" method="post">
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><h3>Asistencia del personal de Servicio Social </h3></td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <a href='Menu_reportes.php'><img src='imagen/regresar2.jpg' alt='Menu de reportes' width='50' height='50' border='0' /></a>
                                    <a href='Menu.php'><img src='imagen/home.png' alt='Menu principal' width='50' height='50' border='0' /></a>
                                </td>      
                            </tr>                               
                            <tr>
                                <td style="text-align:center;vertical-align:middle"><b>Seleccione los datos que se piden </b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>     
                            <tr><td style="text-align:center;vertical-align:middle">
                                 <input type="radio" name="tipoRep" value="1" onclick="submit();" <?php if ($tipoRep ==1) { echo "checked"; } ?>/>Todos los registros
                                 <input type="radio" name="tipoRep" value="2" onclick="submit();" <?php if ($tipoRep ==2) { echo "checked"; } ?>/>Periodo Quincenal<br>
                         </td>
                            </tr> 
                        </form>                        
                        <form action="acceso.php" name="frmMainSerSoc" id="frmMainSerSoc" method="post">
                                                  
                            <tr>
                                <td style="text-align:center;vertical-align:middle">Empleados de Servicio Social:
                                  &nbsp;&nbsp;&nbsp;
                                    <select name="lstEmpleado" id="lstServ_Social">                                        
                                        <?php
                                        $resultado = listaServicioSocial();
                                        echo $resultado;

                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php if ($tipoRep ==1) { ?>
                            <tr>
                                <td style="text-align:center;">Fecha inicio: <input name="fechaInicio" type="text" id="fechaInicio" onClick="popUpCalendar(this, frmMainSerSoc.fechaInicio, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaInicio);?>"></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">Fecha fin:&nbsp;&nbsp;&nbsp;&nbsp; <input name="fechaFin" type="text" id="fechaFin" onClick="popUpCalendar(this, frmMainSerSoc.fechaFin, 'yyyy-mm-dd');" size="10" value="<?php echo html_entity_decode($fechaFin);?>"></td>
                            </tr> 
                            <?php } ?>
                            <?php if ($tipoRep ==2) { ?>
                            <tr>
                                <td style="text-align:center;vertical-align:middle">Periodo quincenal
                                    <select name="lstPeriodo" id="lstPeriodo">                                        
                                        <?php
                                        $resultado = listaPeriodos();
                                        echo $resultado;

                                        ?>
                                    </select>
                                </td>
                            </tr>
                             <?php } else { ?> <input type="hidden" name="lstPeriodo" value="-1" /> <?php } ?>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                                                       
                            <tr>
                                <td style="text-align:center;vertical-align:middle">
                                    <input type="hidden" name="chkop" value="4" />
                                    <input type="hidden" name="lstDepto" value="1000" />
                                    <input type="button" value="Procesar" id="btnRegis" name="btnProceso" onclick="validarSelect()"/>
                                </td>
                            </tr>
                            <?php
                            ?>
                        </form>
                    </table>                
                </td>
            </tr>
        </table>
    </body>
</html>

