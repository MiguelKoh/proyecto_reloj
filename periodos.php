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
    
    
    <!--Plugins css-->
    <link href="assets/plugins/animatecss/animate.min.css" rel="stylesheet">
    <link href="assets/plugins/sweetAlert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!--Hoja de estilos -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-************" crossorigin="anonymous" />
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    
    <!--JQUERY-->
    <script src="assets/vendor/jquery/jquery.js"></script>


    <title>Periodos</title>

</head>
<script>
$(document).ready(function(){
  $('.select_periodos').trigger('onchange');
});

</script>
<body>



<div class="global-wrap"> <!--INICIO CONTENEDOR GLOBAL-->

<main id="contenido_principal">  <!--INICIO CONTENIDO PRINCIPAL-->
         <div id="wrapper"> <!--INICIO WRAPPER-->

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
                <img src="assets/img/logo-uady-blanco.png" alt="Mountain View" class="logoUady">
            <ul class="sidebar-nav">
                
             <!-- Inicio -->
             <li>
                <div>
                  <a href="index.php">
                  <div>
                    <span><i class="fas fa-house-user"></i></i></span>
                    <span>Inicio</span>
                  </div>  
                   </a>
                </div>
                </li>
                
               
                <!-- Catalogos -->
                <li class="FondoNav">
                <div>
                  <a href="listar_catalogos.php">
                  <div>
                    <span><i class="fas fa-th-list"></i></i></span>
                    <span>Catalogos</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Empleados -->
                <li>
                <div>
                  <a href="consultar_empleados.php">
                  <div>
                    <span><i class="fas fa-users"></i></span>
                    <span>Empleados</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                
                
                <!-- Horarios -->
                <li>
                <div>
                  <a href="reporte_horarios.php">
                  <div>
                    <span><i class="fas fa-clock"></i></span>
                    <span>Horarios</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Permisos -->
                <li>
                <div>
                  <a href="captura_permisos.php">
                  <div>
                    <span><i class="fas fa-key"></i></span>
                    <span>Permisos</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Entradas/Salidas -->
                <li>
                <div>
                  <a href="importar.php">
                  <div>
                    <span><i class="fas fa-sign-in-alt"></i></span>
                    <span>Entradas/Salidas</span>
                  </div>  
                   </a>
                </div>
                </li>

                <!-- Reportes -->
                <li>
                <div>
                  <a href="main.php">
                  <div>
                    <span><i class="fas fa-check-square"></i></span>
                    <span>Reportes</span>
                  </div>  
                   </a>
                </div>
                </li>
                
                <!-- Salir -->
                <li>
                <div>
                  <a href="login.php">
                  <div>
                    <span><i class="icon fa fa-sign-out fa-fw" aria-hidden="true"></i></span>
                    <span>Salir</span>
                  </div>  
                   </a>
                </div> 
            </li>
            
        
        </ul>
        </div>
        <!-- /#sidebar-wrapper -->
<!-- webpage content -->
<style>
 
</style>
<h3><b>Periodos</b></h3>
   <!-- Button Modal Agregar-->

<a href="listar_catalogos.php" class="btn btn-secondary">Regresar</a>
<button type="button" class="btn btn-default" aria-label="Left Align">
  <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>
</button>

<button type="button" id="btnAgregar_periodo" class="btn btn-primary btn_agregar" data-bs-toggle="modal" data-bs-target="#modalAgregarperiodo">
  Nuevo
</button>


<!-- Modal Agregar-->
<div class="modal fade" id="modalAgregarperiodo" tabindex="-1" aria-labelledby="modalAgregarperiodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarperiodoLabel">Agregar periodo</h5>
        <div class="validacion_form"></div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" id="form_periodo">
      <!-- Para eliminar y editar-->

        <input type="hidden" id="id_periodop" name="idPeriodo" value="<?php echo $idPeriodo;?>">

        <p>Fecha inicial del periodo: <input type="text"class="form-control" id="fechainicio_periodo" class="fechainicio_periodo" autocomplete="off" placeholder="Ejemplo: 03-05-2021" required></p>
      <p>Fecha final del periodo: <input type="text" class="form-control" id="fechafinal_periodo" class="fechafinal_periodo" autocomplete="off" placeholder="Ejemplo: 06-05-2021" required></p>
      <p>ID del curso: <a href="lista_cursoEscolar.php" target="_blank">Ver cursos</a> <input type="text" class="form-control" id="id_cursoescolar" autocomplete="off" placeholder="Ejemplo: 2" required></p>
      <p>ID del semestre: <a href="lista_semestres.php" target="_blank">Ver semestres</a> <input type="text" class="form-control" id="id_semestre" autocomplete="off" placeholder="Ejemplo: 1" required></p>
        <!-- <input type="text" id="esEmpleado" name="esEmpleado" class='form-control input-sm'> -->

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrar_modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="guardar" id="guardar_periodo" >Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>  <!-- Fin modal agregar -->



<!-- Modal editar-->
<div class="modal fade" id="modalEditarPeriodo" tabindex="-1" aria-labelledby="modalEditarPeriodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarPeriodoLabel">Editar periodo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="" method="POST" id="form_updatePeriodo">
      <!-- Para eliminar y editar-->
        <input type="hidden" name="eliminarPeriodo" id="delete_periodo" value="<?php echo $idPeriodo;?>">
        <input type="hidden" name="update_idperiodo" id="update_idperiodo" value="<?php echo $idPeriodo;?>">


        <p>Fecha inicial del periodo: <input type="text"class="form-control" id="update_fechainicio" class="update_fechainicio" autocomplete="off" placeholder="Ejemplo: 03-05-2021" required></p>
      <p>Fecha final del periodo: <input type="text" class="form-control" id="update_fechafinal" class="update_fechafinal" autocomplete="off" placeholder="Ejemplo: 06-05-2021" required></p>
      <p>ID del curso: <a href="lista_cursoEscolar.php" target="_blank">Ver cursos</a> <input type="text" class="form-control" id="update_idcursoescolar" autocomplete="off" placeholder="Ejemplo: 2" required></p>
      <p>ID del semestre: <a href="lista_semestres.php" target="_blank">Ver semestres</a> <input type="text" class="form-control" id="update_idsemestre" autocomplete="off" placeholder="Ejemplo: 1" required></p>

        <!-- <input type="text" id="esEmpleado" name="esEmpleado" class='form-control input-sm'> -->

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrar_modaleditar">Cerrar</button>
        <button type="submit" class="btn btn-primary actualizar_periodo" name="actualizar_periodo" id="actualizar_periodo" >Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
            <?php
                $sql_selectPeriodos="SELECT fecha_inicio FROM periodos ";
                $query_selectPeriodos=mysqli_query($cn,$sql_selectPeriodos);



                ?>
                <br>
    <label for="select_periodos">Periodo a mostrar:</label>
   <select id="select_periodos" class="form-select select_periodos" aria-label=".form-select-lg example" onchange="mostrarPeriodo();">
    <?php


while (($result_selectPeriodo =mysqli_fetch_array($query_selectPeriodos)) != null)

{

  $año=strtr($result_selectPeriodo['fecha_inicio'],'/','-');


      $periodo_select= date("Y", strtotime($año));
    $array[]=$periodo_select;

    }
  $array =array_unique($array);
    foreach($array as $numero){
      echo "<option value='{$numero}'";
      echo ">{$numero}</option>";
    }


  /*   $año_periodo = date('Y',strtotime($periodo_año));
   */

?>
</select>
<!-- LISTAR PERIODOS EN TABLA -->

        <div class="divtable_periodos" id="divtable_periodos" style="display: none">

        </div>






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
