
<?php

    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("idParcial");
 
    if ($variables[1][2]) {$idParcial    = $variables[1][1];} else {$idParcial = 0;}            
    
    global $fechaInicio,$fechaFin;
    
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Control de asistencias 1.0</title> 
        <script type="text/javascript" src="js/ajax.js"></script>
        <script language='javascript' src="js/popcalendar.js"></script>
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
    </head>
    <body style="text-align: center">

        <table style="text-align:center;background-color: white" width="100%" border="0">
            <tr>
                <td colspan="3">
                    <img alt="Preparatoria Dos - UADY" src="imagen/logo2.jpg" >

                </td>
            </tr>
            <tr>
                <td align="center">
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">
                        <form action="" name="frmMaestrosParciales" id="frmMaestrosParciales" method="post">
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle"><b>Maestros que cuidar&aacute;n ex&aacute;menes </b></td>
                            </tr>
                            <tr>
                                <td colspan="4"align="right">
                                    <a href='OpParciales.php'><img src='imagen/regresar2.jpg' alt='Menu parciales' width='50' height='50' border='0' /></a>
                                    <a href='Menu.php'><img src='imagen/home.png' alt='Menu principal' width='50' height='50' border='0' /></a>
                                </td>   
                            </tr>                                                                           
                            <tr>
                              <td>Periodo: </td>
                                <td> 
                                    <select name="idParcial" id="idParcial" onChange="javascript:submit()">
                                      <?php 
                                           $SQLp="SELECT * FROM parciales";
                                           $queryA = mysql_query($SQLp,$cn);
                                      ?>
                                      <option value="0">Seleccione un periodo</option>
                                      <?php 
                                          while( $rsA=mysql_fetch_array($queryA) ) { 
                                              if ($idParcial == $rsA["idParcial"]) {
                                                  $selected = " selected";
                                                  } 
                                              else {
                                                  $selected = "";
                                                  } 
                                      ?>
                                      <option value="<?php echo $rsA["idParcial"]; ?>"<?php echo $selected;?>>
                                          <?php echo $rsA["descripcion"];?>                
                                      </option>
                                      <?php } mysql_free_result($queryA);?>
                                    </select>
                                </td>
                               
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="buscaMaestros" id="buscaMaestros" value="BUSCAR"></td>
                                </td>
                            </tr>
                            <?php
                                if(isset($_POST['buscaMaestros'])){  
                                        //obtengo la lista de los permisos que capturados en la fecha seleccionada
                                        $sente = "SELECT a.idEmp,b.Nombre,a.numAula,a.dia,a.fecha,a.hora_ini,a.hora_fin,a.asignatura ".
                                                 "FROM maestros_parciales as a left outer join empleado as b ".
                                                 "ON a.idEmp = b.idEmp " .
                                                 "WHERE a.idParcial = " . $idParcial . 
                                                 " order by STR_TO_DATE(a.fecha,'%d/%m/%Y'),a.hora_ini,a.numAula,a.idEmp";            
                                        $result = mysql_query($sente,$cn);
                                        //echo "<tr><td>". $sente ."</td></tr>";
                            ?>
                                <table width='70%' cellpadding='1' cellspacing='1' border='1'>
                                    <tr>
                                        <th>Fecha examen</th>
                                        <th>Cve. Empleado</th>
                                        <th>Nombre</th>
                                        <th>Dia semana</th>
                                        <th>Aula</th>
                                        <th>Asignatura</th>
                                        <th>Hora inicio</th>
                                        <th>Hora Fin</th>
                                    </tr>
                                    <?php
                                    while ($row = mysql_fetch_array($result)){
                                        $dia_semana = $row['dia'];
                                        $nombre_dia = nombre_dia($dia_semana);
                                        
                                        //modulo libre
                                        $nombre_empleado = $row['Nombre'];
                                        if ($row['idEmp'] == 8000){
                                            $nombre_empleado = "----- MODULO LIBRE ------";
                                        }
                                        
                                     echo "                                       
                                        <tr>
                                            <td align='center'>".$row['fecha']."</td>
                                            <td align='center'>".$row['idEmp']."</td>
                                            <td>".$nombre_empleado."</td>
                                            <td align='center'>".$nombre_dia."</td>
                                            <td align='center'>".$row['numAula']."</td>
                                            <td align='center'>".$row['asignatura']."</td>
                                            <td align='center'>".$row['hora_ini']."</td>
                                            <td align='center'>".$row['hora_fin']."</td>  
                                        </tr>";
                                    }
                                     ?>
                                </table>
                            <?php
                                         
                            }                            
                            ?>                
                        </form>
                    </table>                
                </td>            
            </tr>
        </table>
    </body>
</html>
