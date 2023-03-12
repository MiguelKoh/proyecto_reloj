<?php 
include('../conex.php');
$cn=ConectaBD();

switch($_POST['accion_cursoEscolar']){
    case "guardar_cursoEscolar":{
        if((!empty($_POST['periodoInicial']))&&(!empty($_POST['periodoFinal']))){
            $periodoInicial=$_POST['periodoInicial'];
            $periodoFinal=$_POST['periodoFinal'];
            $periodo=$periodoInicial."-".$periodoFinal;
            $sql="INSERT INTO curso_escolar(idcurso,descripcion) VALUES ('','".$periodo."')";
            $queryguardar=mysqli_query($cn,$sql);
        }
        break; 
        }
    case "eliminar_cursoEscolar":{
            if(!empty($_POST['idCurso'])){
                $eliminar="DELETE FROM curso_escolar WHERE idCurso='".$_POST['idCurso']."'";
                $queryeliminar=mysqli_query($cn,$eliminar);
    }
    break;
    }
}

?>