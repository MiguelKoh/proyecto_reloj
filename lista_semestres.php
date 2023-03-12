<?php
include('conex.php');
$cn = ConectaBD();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Aid wear" />
    <meta name="description" content="App Reloj">
    <meta name="author" content="Reloj">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap core CSS -->
    <link class="bootstrap" href="assets/vendor/bootstrap/bootstrap_5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <!--Plugins css-->
    <link href="assets/plugins/animatecss/animate.min.css" rel="stylesheet">
    <link href="assets/plugins/sweetAlert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!--Hoja de estilos -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet"> 
    <!--JQUERY-->
    <script src="assets/vendor/jquery/jquery.js"></script>
    

    <title>Semestres</title>

</head>
<body>


    
<div class="global-wrap"> <!--INICIO CONTENEDOR GLOBAL-->

<main id="contenido_principal">  <!--INICIO CONTENIDO PRINCIPAL-->
         <div id="wrapper"> <!--INICIO WRAPPER-->

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
            </div> <!-- /#sidebar-wrapper -->
<!-- webpage content -->

<h3><b>Semestres</b></h3>
   <!-- Button Modal Agregar-->
<button type="button" id="btnAgregar_semestre" class="btn btn-primary btn_agregar" data-bs-toggle="modal" data-bs-target="#modalAgregarSemetre">
  Nuevo
</button>

<!-- Modal Agregar-->
<div class="modal fade" id="modalAgregarSemetre" tabindex="-1" aria-labelledby="modalAgregarSemetreLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarSemetreLabel">Agregar semestre</h5>
        <div class="validacion_form"></div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" id="form_cursoEscolar"> 
      <!-- Para eliminar y editar-->
        
        <input type="hidden" id="idSemestre" class ="idSemestre "name="idSemestre" value="<?php echo $idSemestre;?>">
        <label for="NumSemestre">Semestre</label>
        <select name="NumSemestre" id="NumSemestre" class="form-select NumSemestre">
            <option value="1er semestre">Primer semestre</option>
            <option value="2do semestre">Segundo semestre</option>
        </select>
        <label for="idcursoEscolar">ID curso escolar</label>
        <input type="text" class="form-control" id="idcursoEscolar" class="idcursoEscolar" required autocomplete="off">

        <p>Fecha inicial del semestre: <input type="text"class="form-control fecha_inicioSemestre" id="fecha_inicioSemestre" autocomplete="off"></p>
      <p>Fecha final del semestre: <input type="text" class="form-control fecha_finalSemestre" id="fecha_finalSemestre" autocomplete="off"></p>
          
        <!-- <input type="text" id="esEmpleado" name="esEmpleado" class='form-control input-sm'> --> 
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrar_modalSemestre">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="guardar" id="guardar_Semestre" >Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>  <!-- Fin modal agregar -->

    <!-- LISTAR CURSOS EN TABLA -->
        <table id="table_Semestre" class="table table-striped table_diseño table_DiseñoCursos">
       
                <tbody >
                    
                    <tr class="table-active">
                        <th>ID</th>
                        <th>Semestre</th>
                        <th>Id curso</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de termino</th>
                        <th>Acciones</th>
                    </tr>
                    <?php 
                $sql_semestre="SELECT idsemestre,descripcion,idcurso,fecha_inicio,fecha_fin FROM semestre";
                $query_semestre=mysqli_query($cn,$sql_semestre);

                while ($semestre=mysqli_fetch_assoc($query_semestre)) {
                    ?>
                    <tr>
                        <td><?php echo $semestre['idsemestre']?></td>
                        <td><?php echo $semestre['descripcion']?></td>
                        <td><?php echo $semestre['idcurso']?></td>
                        <td><?php echo $semestre['fecha_inicio']?></td>
                        <td><?php echo $semestre['fecha_fin']?></td>
                      
                            <!-- BOTONES DE ELIMINAR Y EDITAR -->
                       <!--  <a accion="editar" title="editar" id="editar_tipoempleado" class="editar_tipoempleado">
                        <img class ="img_btn_editar" id='editar' src='img/editar.svg'/>
                        </a> -->

                        <td>
                        <a title="Eliminar"  class="eliminar_Semestre eliminar_elemento" id="eliminar_Semestre">
                        <img class ="img_btn_borrar"id='borrar' src='img/borrar.svg'/>
                        </a>
                    
                        </td>
                   
                    </tr>
                
                
                    <?php   
                        }
                        ?>
                </tbody>
        </table>
    
     </div> <!-- FIN WRAPPER -->
        </main>  <!-- FIN CONTENIDO PRINCIPAL--> 
    
                    </div><!-- FIN GLOBAL WRAPPER-->
<!-- Bootstrap core JavaScript -->

        
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap_5.0/js/bootstrap.min.js"></script>
    <!--bootstrap datepicker-->
   
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet"> 
   <!--  <link rel="stylesheet" href="assets/plugins/jqueryui/jquery-ui.css">
     <script src="assets/plugins/jqueryui/jquery-ui.js"></script>
     -->
    
   <script>
        $(function(){
            $("#includedHeader").load("templates/header/header.html"); 
            $("#includedContent").load("assets/prueba.html"); 
            $("#includedFooter").load("templates/footer/footer.html"); 
        });

            $("#wrapper").toggleClass("toggled");

      
    </script>
    
   <!--PLUGIN SWEETALERT-->
   <script src="assets/plugins/sweetAlert2/dist/sweetalert2.all.min.js"></script>
   <script src="js/funciones.js"></script>
</body>
 <!--Funciones script -->
<!--Aviso de confirmacion al eliminar-->


<!---->

</html>


