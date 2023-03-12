<?php 
  include('../conex.php');
  $cn = ConectaBD();
switch ($_GET['accion']) {
    case "guardar_tipoempleado":
        {
          if (!empty($_GET['tipoEmpleado'])) {
              $guardar = "INSERT INTO tipoempleado (idTipo,Descripcion,esEmpleado) VALUES ('','".$_GET['tipoEmpleado']."','".$_GET['esEmpleado']."')";
              $queryguardar=mysqli_query($cn, $guardar);
            
          }
          break;
        }

    case "editar_tipoempleado":
      {
        if(!empty($_GET['tipoEmp'])){
          $editar="UPDATE tipoempleado set Descripcion='".$_GET['tipoEmp']."',esEmpleado='".$_GET['esEmp']."' WHERE idTipo='".$_GET['update_idTipo']."'";
          $queryeditar=mysqli_query($cn,$editar);
        }
        break;
    }
  }
?>