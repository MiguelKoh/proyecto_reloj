<?php
error_reporting(-1);
//require_once("config.inc.php");
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
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



    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

	
	<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
	<link type="text/css" rel="stylesheet" media="all" href="calendario/estilos.css" />

		<script type="text/javascript">
		function generar_calendario(mes,anio)
		{
			
			var agenda=$("#agenda");
			agenda.html("<img src='calendario/images/loading.gif'>");
			$.ajax({
				type: "GET",
				url: "calendario/ajax_calendario.php",
				cache: false,
				data: { mes:mes,anio:anio,accion:"generar_calendario" }
			}).done(function( respuesta ) 
			{
				agenda.html(respuesta);
				$('a.modal').bind("click",function(e) 
				{
					e.preventDefault();
					var id = $(this).data('evento');
					var fecha = $(this).attr('rel');
					if (fecha!="") 
					{
						$("#evento_fecha").val(fecha);
						$("#que_dia").html(fecha);
					}
					var maskHeight = $(document).height();
					var maskWidth = $(window).width();
				
					$('#mask').css({'width':maskWidth,'height':maskHeight});
					
					$('#mask').fadeIn(1000);
					$('#mask').fadeTo("slow",0.8);	
				
					var winH = $(window).height();
					var winW = $(window).width();
						  
					$(id).css('top',  winH/2-$(id).height()/2);
					$(id).css('left', winW/2-$(id).width()/2);
				
					$(id).fadeIn(200); 
				
				});
		
				$('.close').bind("click",function (e) 
				{
					var fecha=$(this).attr("rel");
					var nueva_fecha=fecha.split("-");
					e.preventDefault();
					$('#mask').hide();
					$('.window').hide();
					generar_calendario(nueva_fecha[1],nueva_fecha[0]);
				});
		
				//guardar evento
				$('.enviar').bind("click",function (e) 
				{
					e.preventDefault();
					$("#respuesta_form").html("<img src='calendario/images/loading.gif'>");
					var evento=$("#evento_titulo").val();
					var fecha=$("#evento_fecha").val();
					var tipo=$("#tipo").val();
					var inicial=$("#inicial").val();
					var final=$("#final").val();
					$.ajax({
						type: "GET",
						url: "calendario/ajax_calendario.php",
						cache: false,
						data: { evento:evento,fecha:fecha,tipo:tipo,inicial:inicial,final:final,accion:"guardar_evento" }
					}).done(function( respuesta2 ) 
					{
						$("#respuesta_form").html(respuesta2);
						var evento=$("#evento_titulo").val("");
					});
				});
				
				//eliminar evento
				$('.eliminar_evento').bind("click",function (e) 
				{
					e.preventDefault();
					var current_p=$(this);
					$(".respuesta").html("<img src='calendario/images/loading.gif'>");
					var id=$(this).attr("rel");
					$.ajax({
						type: "GET",
						url: "calendario/ajax_calendario.php",
						cache: false,
						data: { id:id,accion:"borrar_evento" }
					}).done(function( respuesta2 ) 
					{
						$(".respuesta").html(respuesta2);
						current_p.parent("p").fadeOut();
					});
				});
				
				$(".anterior,.siguiente").bind("click",function(e)
				{
					e.preventDefault();
					var datos=$(this).attr("rel");
					var nueva_fecha=datos.split("-");
					generar_calendario(nueva_fecha[1],nueva_fecha[0]);
				})

				$(window).resize(function () 
				{
				 	var box = $('#boxes .window');
			 		var maskHeight = $(document).height();
					var maskWidth = $(window).width();
					$('#mask').css({'width':maskWidth,'height':maskHeight});
					var winH = $(window).height();
					var winW = $(window).width();
					box.css('top',  winH/2 - box.height()/2);
					box.css('left', winW/2 - box.width()/2);
				});
			});
		}
		$(document).ready(function()
		{
			/* GENERAMOS CALENDARIO CON FECHA DE HOY */
			generar_calendario("<?php if (isset($_GET["mes"])) echo $_GET["mes"]; ?>","<?php if (isset($_GET["anio"])) echo $_GET["anio"]; ?>");
			
			setTimeout(function() {$('#mensaje').fadeOut('fast');}, 3000);
		});
		
		</script>
		  
	</head>
    <body>

    <div class="global-wrap">
          
         <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <img src="assets/img/logo_uady-gray.png" alt="Mountain View" style="margin-left:-4px;width:170px;height:auto;">
              <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="index.php">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="listar_catalogos.php">Catálogos</a>
                </li>
                <li>
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
                <table style="text-align:center;background-color: white" width="90%" border="0">
            <tr>
                <td align="center">
                                         
                            <tr >
                                <td colspan="4" style="text-align:center;vertical-align:middle;"><h2><b>Alta de Eventos</b></h2></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">
                                    <a href='listar_catalogos.php'><img src='imagen/catalogos.png' alt='Alta de Empleados' width='30' height='30' border='0' /></a>
                                </td>      
                            </tr> 
                           
                            </td>
                            </tr>
                            </table>

	<div id="agenda"></div>
	<div id="mask"></div>
	

	
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
            $("#includedContent").load("assets/prueba.html"); 
            $("#includedFooter").load("emplates/footer/footer.html"); 
        });

       
            $("#wrapper").toggleClass("toggled");
       
    </script>
    
</body>
</html>