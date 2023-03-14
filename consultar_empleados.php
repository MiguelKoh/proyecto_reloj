<?php

    session_start();
    include('conex.php'); 
    include('funciones_reloj.php');
    $cn = ConectaBD();
  
  
global $editFormAction, $mensajes, $s, $pag, $searchtype,$cadena ;


$myData = Request("search,searchtype,searchby,txt2search,orderby");
if (!$myData[2][2]) {
  $searchtype = "simple";
} else {
  $searchtype = $myData[2][1];
}
$shr = "";
if (($myData[1][2]) and (strlen($myData[4][1]) < 1)) {$shr = 1;}
  if (($myData[1][2]) and (strlen($myData[4][1]) > 0)) {
    $showresults = true;
    switch ($searchtype) {
      case "simple":
        $searchby = $myData[3][1];
        $txt2search = $myData[4][1];
        if (!$myData[5][2]) {
          $orderby = "Nombre";
        } else {
          $orderby = $myData[5][1];
        }
        switch ($searchby) {
          case "1":
          $cadena = "idEmp LIKE '%".$txt2search."%'";          
            break;
          case "2":
          $cadena = "Nombre LIKE '%".$txt2search."%'";
            break;        
        }   
    
     //echo $SQL;
    
        
    
    }
  } else {
    $showresults = false;
  }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Control de Asistencia v2.0</title>
    <!-- meta info -->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="keywords" content="Aid wear" />
    <meta name="description" content="App Reloj">
    <meta name="author" content="Reloj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon_uady.ico">

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
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


<body>

    <div class="global-wrap">
          
         <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">

        <img src="assets/img/logo-uady-blanco.png" alt="Mountain View" class="logoUady">

            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="index.php">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="listar_catalogos.php">Catálogos</a>
                </li>
                <li class="FondoNav">
                    <a href="consultar_empleados.php">Empleados</a>
                </li>
                <li>
                    <a href="reporte_horarios.php">Horarios</a>
                </li>
                <li>
                    <a href="captura_permisos.php">Permisos</a>
                </li>
                <li>
                    <a href="importar.php">Entradas/Salidas</a>
                </li>
                <li>
                    <a href="main.php">Reportes</a>
                </li>
                <li>
                    <a href="login.php">Salir</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">

                <!-- //////////////////////////////////
                //////////////START PAGE CONTENT/////////
                ////////////////////////////////////-->
                <div style="margin-left: -80px">

        <table style="text-align:center;background-color: white" width="100%" border="0">
            <tr>
                <td align="center">
                    <table align="center" width="70%" cellpadding="1" cellspacing="1" border="0">                       
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle"><h3><b>Consultar Empleados </b></h3></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='alta_Empleados.php'><img src='imagen/addempleado.png' alt='Alta de Empleados' width='30' height='30' border='0' /></a>
                                    <a href='consultar_empleados.php'><img src='imagen/buscar.gif' alt='Actualizar Empleado' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr>                                                                           
 
 </table>
 <form action="consultar_empleados.php" method="post" name="pm" id="pm">
<input name="search" type="hidden" value="yes">
  <input name="searchtype" type="hidden" value="<?PHP echo $searchtype; ?>">
  <table border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td class="LetraGris14BoldForm"><b>Buscar:</b>
        <input name="txt2search" type="text" id="txt2search">
        </td>
      <td class="LetraGris14BoldForm"><b>por:</b>
        <select name="searchby" size="1" id="searchby">
              <option value="1">ClaveEmpleado</option>
              <option value="2">Nombre / Apellidos</option>
                        
            </select>
      </td>
      <td><label>
        <input type="submit" name="Submit" value="Buscar">
      </label></td>
    </tr>
  </table>

<br/>
  <table align="center">
  <tr>
  <td>
  <center>
    [<a href="consultar_empleados.php?mostrar=A&mensajes=""">A</a>]
    [<a href="consultar_empleados.php?mostrar=B&mensajes=""">B</a>]
    [<a href="consultar_empleados.php?mostrar=C&mensajes=""">C</a>]
    [<a href="consultar_empleados.php?mostrar=D&mensajes=""">D</a>]
    [<a href="consultar_empleados.php?mostrar=E&mensajes=""">E</a>]
    [<a href="consultar_empleados.php?mostrar=F&mensajes=""">F</a>]
    [<a href="consultar_empleados.php?mostrar=G&mensajes=""">G</a>]
    [<a href="consultar_empleados.php?mostrar=H&mensajes=""">H</a>]
    [<a href="consultar_empleados.php?mostrar=I&mensajes=""">I</a>]
    [<a href="consultar_empleados.php?mostrar=J&mensajes=""">J</a>]
    [<a href="consultar_empleados.php?mostrar=K&mensajes=""">K</a>]
    [<a href="consultar_empleados.php?mostrar=L&mensajes=""">L</a>]
    [<a href="consultar_empleados.php?mostrar=M&mensajes=""">M</a>]
    [<a href="consultar_empleados.php?mostrar=N&mensajes=""">N</a>]
    [<a href="consultar_empleados.php?mostrar=O&mensajes=""">O</a>]
    [<a href="consultar_empleados.php?mostrar=P&mensajes=""">P</a>]
    [<a href="consultar_empleados.php?mostrar=Q&mensajes=""">Q</a>]
    [<a href="consultar_empleados.php?mostrar=R&mensajes=""">R</a>]
    [<a href="consultar_empleados.php?mostrar=s&mensajes=""S">S</a>]
    [<a href="consultar_empleados.php?mostrar=t&mensajes=""T">T</a>]
    [<a href="consultar_empleados.php?mostrar=U&mensajes=""">U</a>]
    [<a href="consultar_empleados.php?mostrar=V&mensajes=""">V</a>]
    [<a href="consultar_empleados.php?mostrar=W&mensajes=""">W</a>]
    [<a href="consultar_empleados.php?mostrar=X&mensajes=""">X</a>]
    [<a href="consultar_empleados.php?mostrar=Y&mensajes=""">Y</a>]
    [<a href="consultar_empleados.php?mostrar=Z&mensajes=""">Z</a>]
  </center>  </td>
  </tr>
  </table>
<?php 
$var=Request("mostrar");
if ($var[1][2])
    $letra = $var[1][1];
else
    $letra="A";
?>
  <br />
  <table width="380"  border='0' cellpadding='0' cellspacing='0' bordercolor="#333366">
  <tr>
  <td align="right">
  <p class="LetraGris14Bold">Personal inician con la letra<strong>[</strong><?php echo $letra;?><strong>]</strong></p>  </td>
  </tr>
  </table>
  </form>
                                                                       
  <?php
  if($cadena!=""){
    $SQLSearch = "SELECT * FROM empleado WHERE ".$cadena."  ORDER BY ".$orderby;   
  }else{
     $SQLSearch="SELECT idEmp, Nombre FROM empleado WHERE Estatus is null AND Nombre LIKE '".$letra."%' ORDER BY Nombre";
}
    $resultadoSearch = mysqli_query($cn,$SQLSearch) or die(mysql_error());
$cont = mysqli_num_rows($resultadoSearch);
if ($cont > 0) {
 ?> 
                         
  <br />

<table width="90%"   border="0" align="center" cellpadding='0' cellspacing='0' >
<tr bgcolor="#000066" style="color:#FFF">
  <th width="16%">Clave Empleado</th>
  <th width="50%">Nombre</th>
  <th width="13%">Acciones</th>      
</tr>
  <?php 
while ($registro=mysqli_fetch_array($resultadoSearch))
    {
        $s = $s + 1;
        $valor = Par_Non($s);
        switch ($valor) {
          case "PAR": $bg = "#DBE0E5";
            break;
          case "NON": $bg = "#F4F6F7";
            break;
    }        
?>
<tr bgcolor="<?PHP echo $bg;?>" ></tr>
<tr bgcolor="<?PHP echo $bg;?>">
        <th class="LetraGris14Bold"><?php echo $registro['idEmp'];?></th>
        <th class="LetraGris14Bold" align="left">
         <?php echo utf8_encode($registro['Nombre']); ?>
          
         </th>            
      <th bgcolor="<?PHP echo $bg;?>">
      <a href="mant_empleados.php?idEmp=<?php echo $registro['idEmp'] ?>">
        <img src="imagen/editarempleado.png" title="Editar Registro" alt="¡EDITAR REGISTRO!" width="16" height="16" border="0" />
      </a> 
      <a href="reporte_permisos_empleados.php?idEmp=<?php echo $registro['idEmp'] ?>">
        <img src="imagen/permiso.gif" title="Consultar Permisos" alt="¡CONSULTAR PERMISOS!" width="25" height="25" border="0" />
      </a>
      <a href="reporte_horarios_empleados.php?idEmp=<?php echo $registro['idEmp'] ?>">
        <img src="imagen/horario.gif" title="Consultar Horarios" alt="¡CONSULTAR HORARIOS!" width="20" height="20" border="0" />
      </a> 
      <a href="checadas.php?idEmp=<?php echo $registro['idEmp'] ?>">
        <img src="imagen/excel.gif" title="Consultar Registros" alt="¡CONSULTAR REGISTROS!" width="20" height="20" border="0" />
      </a>       
          </th>
        </tr>  
    <?php }?>
    <tr><td colspan="4" align="center">&nbsp;</td>
      </tr> 
  </table>
<?php } else { ?>
     <p class="noEncontradas">NO SE ENCONTRARON REGISTROS</p>
<?php } 
mysqli_free_result($resultadoSearch)
;?> 
              
                </div>   

                 <!-- //////////////////////////////////
                //////////////END PAGE CONTENT/////////
                ////////////////////////////////////-->

                <div id="includedFooter"></div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
   
    </div>

    <!-- Scripts queries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        $(function(){
            $("#includedHeader").load("templates/header/header.html"); 
            $("#includedFooter").load("templates/footer/footer.html"); 
        });

        window.onload=function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        };
    </script>
    
</body>
</html>
