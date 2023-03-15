<?php
  include('conex.php');
  $cn = ConectaBD();
  
  $tipo_empleado="";
  $esEmpleado="";
  $errores="";
  
if (isset($_POST['guardar'])) {
    if(!empty($_POST['tipoempleado'])){
      $tipo_empleado=$_POST['tipoempleado'];
    }else{
    $errores.= 'Favor de agregar el nombre del tipo de empleado';
    }
    if(!empty($_POST['esEmpleado'])){
      $esEmpleado=$_POST['esEmpleado'];
    }else{
    $errores.= 'Favor de agregar si es empleado o no';
    }

   

/*  if (empty($errores)&&(!empty($_POST['tipoempleado']))&&(!empty($_POST['esEmpleado']))){
$guardar = "INSERT INTO tipoempleado (idTipo,Descripcion,esEmpleado) VALUES ('','".$_POST['tipoempleado']."','".$esEmpleado."')";
$queryguardar=mysqli_query($cn,$guardar);
}  */
 
/* if(isset($_POST['esEmpleado'])){
  $tipo_empleado=$_POST('esEmpleado');
}else{
echo 'Favor de agregar si es un empleado';
} */
}
if (!empty($_GET['idTipo'])&& !empty($_GET['accion']) && $_GET['accion']=='borrar') {
    $borrar="DELETE FROM tipoempleado WHERE idTipo='".$_GET['idTipo']."'";
    $queryborrar = mysqli_query($cn, $borrar);
}


?>
<?php
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado


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
    <!-- Custom styles for this template -->
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet"> 
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-************" crossorigin="anonymous" />
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-************" crossorigin="anonymous" />
    <link href="assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">


    <title>Tipos de empleados</title>

    
</head>

  

    <body>
      

    <div class="global-wrap">

       
         <div id="wrapper">

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

        <main id=contenido_principal>
    <h3><b>Tipos de empleados</b></h3>

    <section>
    <!-- Button trigger modal -->

    <a href="listar_catalogos.php" class="btn btn-secondary">Regresar</a>
<button type="button" class="btn btn-primary btn_agregar" data-bs-toggle="modal" data-bs-target="#modalRegistro">
  Nuevo
</button>

<!-- Modal Agregar-->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRegistroLabel">Agregar tipo de empleado</h5>
        <div class="validacion_form"></div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" id="form_tipoempleado"> 
      <!-- Para eliminar y editar-->
        <input type="hidden" name="editar" value="<?php echo $edicion;?>">
        <input type="hidden" name="idTipo" value="<?php echo $idTipo;?>">

          <label for="tipoempleado">Tipo de empleado</label>
          <input type="text" id="tipoempleado" name="tipoempleado" class='form-control input-sm' autocomplete="off" required>
          <label for="esEmpleado">Es empleado</label>
          <select class="form-select" name="esEmpleado" id="esEmpleado">
            <option value="S" >Si</option>
            <option value="N">No</option>
          </select>
      
        <!-- <input type="text" id="esEmpleado" name="esEmpleado" class='form-control input-sm'> --> 
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrar_modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="guardar" id="guardar_tipoempleado" >Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>  <!-- Fin modal agregar -->

<!-- Modal editar-->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar tipo de empleado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="" method="POST" id="form_tipoempleado"> 
      <!-- Para eliminar y editar-->
        <input type="hidden" name="editar" value="<?php echo $edicion;?>">
        <input type="hidden" name="update_idTipo" id="update_idTipo" value="<?php echo $idTipo;?>">

          <label for="tipoempleado">Tipo de empleado</label>
          <input type="text" id="Descripcion" name="Descripcion" class='form-control input-sm' autocomplete="off" required>
          <label for="esEmpleado">Es empleado</label>
          <select class="form-select" name="esEmpleado" id="esempleado">
            <option value="S" >Si</option>
            <option value="N">No</option>
          </select>
      
        <!-- <input type="text" id="esEmpleado" name="esEmpleado" class='form-control input-sm'> --> 
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrar_modaleditar">Cerrar</button>
        <button type="submit" class="btn btn-primary actualizar_datos" name="actualizar_datos" id="actualizar_datos" >Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
        <table id="table_tipoempleados" class="table table-striped table_tipoempleados table_diseño" >
            <tbody>
                <tr class="table-active">
                    <th scope="col">ID</th>    
                    <th scope="col" colspan="1">Tipo de empleado</th>
                    <th scope="col">Es empleado</th>
                    <th scope="col">Acciones</th>
                    
                </tr>
                <?php
                  $sql="SELECT idTipo,Descripcion,esEmpleado FROM tipoempleado ORDER BY idTipo ASC";
                  $resultado= mysqli_query($cn, $sql);

                  while ($datos=mysqli_fetch_assoc($resultado)) {
                      ?>

                <tr>
                    <td><?php echo $datos['idTipo']?></td>
                    <td><?php echo $datos['Descripcion']?></td>
                    <td><?php echo $datos['esEmpleado']?></td>
                    <td>
                   <!--  href="tipos_empleados.php?idTipo=<?/*php echo $datos['idTipo']*/?> -->
        <a accion="editar" title="editar" id="editar_tipoempleado" class="editar_tipoempleado">
        <img class ="img_btn_editar" id='editar' src='img/editar.svg'/>
        </a>

        
          <a href="tipos_empleados.php?idTipo=<?php echo $datos['idTipo']?>&accion=borrar" title="Eliminar" class="eliminar_tipoempleado">
          <img class ="img_btn_borrar"id='borrar' src='img/borrar.svg'/>
       
                  </a>
          
       
      </td>
                </tr>

                
                <?php
                  }?>
                <tr>

                </tr>
            </tbody>
     </table>
     
    </section>
</main>

</div>
        <!-- webpage content -->

    <!-- Scripts queries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap_5.0/js/bootstrap.min.js"></script>
   <script>
        $(function(){
            $("#includedHeader").load("templates/header/header.html"); 
            $("#includedContent").load("assets/prueba.html"); 
            $("#includedFooter").load("templates/footer/footer.html"); 
        });

            $("#wrapper").toggleClass("toggled");

      
    </script>
    
<!--Funciones script -->
<!--Aviso de confirmacion al eliminar-->
<script src="assets/plugins/sweetAlert2/dist/sweetalert2.all.min.js"></script>
<script src='js/funciones.js'></script>
<!---->


</body>


</html>

