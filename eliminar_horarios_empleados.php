<?php 
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();

	if ((isset($_GET['idhorario'])) && ($_GET['idhorario'] != "")) {

			$SQLpermisos="SELECT p.id,p.idemp,p.id_curso,p.id_semestre
            FROM horarios_semestre p 
            WHERE p.id = ".$_GET['idhorario']."";
            $querypermisos=mysqli_query($cn,$SQLpermisos);
            $permi=mysqli_fetch_array($querypermisos);
             $idEmp=$permi['idemp'];
             $idCurso=$permi['id_curso'];
             $idSemestre=$permi['id_semestre'];
            mysqli_free_result($querypermisos);

            $SQLpermisos="SELECT p.idDepto,p.idemp
            FROM empleado p 
            WHERE p.idEmp = ".$idEmp."";
            $querypermisos=mysqli_query($cn,$SQLpermisos);
            $permi=mysqli_fetch_array($querypermisos);
             $idDepto=$permi['idDepto'];
            mysqli_free_result($querypermisos);

            $sente = "DELETE FROM horarios_semestre WHERE id = ".$_GET['idhorario'];
            $result = mysqli_query($cn,$sente);
            
            //echo '<script>alert ("El horario seleccionado se borro correctamente")</script>'; "         
            echo '<script>location.href="reporte_horarios_empleados.php?idEmp='.$idEmp.'&idDepto='.$idDepto.'&idCurso='.$idCurso.'&idSemestre='.$idSemestre.'"</script>';
}
?>