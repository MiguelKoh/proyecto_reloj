<?php 
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();

	if ((isset($_GET['idPermisos'])) && ($_GET['idPermisos'] != "")) {

			 $SQLpermisos="SELECT p.idPermisos,p.idemp
            FROM permisos p 
            WHERE p.idPermisos = ".$_GET['idPermisos']."";
            $querypermisos=mysqli_query($cn,$SQLpermisos);
            $permi=mysqli_fetch_array($querypermisos);
             $idEmp=$permi['idemp'];
            mysqli_free_result($querypermisos);

            $SQLpermisos="SELECT p.idDepto,p.idemp
            FROM empleado p 
            WHERE p.idEmp = ".$idEmp."";
            $querypermisos=mysqli_query($cn,$SQLpermisos);
            $permi=mysqli_fetch_array($querypermisos);
             $idDepto=$permi['idDepto'];
            mysqli_free_result($querypermisos);



            $sente = "DELETE FROM permisos WHERE idPermisos = ".$_GET['idPermisos'];
            $result = mysqli_query($cn,$sente);
            
            //echo '<script>alert ("El permiso se borro correctamente")</script>';
           echo '<script>location.href="reporte_permisos.php?idEmp='.$idEmp.'&idDepto='.$idDepto.'&fechaInicio='.$_GET['fechaini'].'&fechaFin='.$_GET['fechaFin'].'"</script>';
}
?>