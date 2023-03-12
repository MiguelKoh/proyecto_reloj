<?php
include('../conex.php');
$cn=ConectaBD();
if (!empty($_POST['accion_Semestre'])) {
    switch ($_POST['accion_Semestre']) {
    case "guardar_semestre":{
        if ((!empty($_POST['periodoInicial']))&&(!empty($_POST['periodoFinal']))&&(!empty($_POST['idcursoescolar']))) {
            $semestre=$_POST['numsemestre'];
            $idcursoescolar=$_POST['idcursoescolar'];
            $periodoInicial=$_POST['periodoInicial'];
            $periodoFinal=$_POST['periodoFinal'];

            $sql="INSERT INTO semestre(idsemestre,descripcion,idcurso,fecha_inicio,fecha_fin) VALUES (' ','".$semestre."','".$idcursoescolar."','".$periodoInicial."','".$periodoFinal."')";
            $queryguardar=mysqli_query($cn, $sql);
        }
        break;
        }
        case "eliminar_Semestre":{
            if (!empty($_POST['idSemestre'])) {
                $eliminar="DELETE FROM semestre WHERE idsemestre='".$_POST['idSemestre']."'";
                $queryeliminar=mysqli_query($cn, $eliminar);
            }
    break;
    }
    }
}
if (!empty($_POST['accion_periodos'])) {
    switch ($_POST['accion_periodos']) {
        case "mostrar_periodo":{
            $periodo = $_POST['opcion'];
            $sql ="SELECT idperiodo,fecha_inicio,fecha_fin,reinicio_contador,id_curso,id_semestre
            FROM periodos where fecha_inicio LIKE '%$periodo'";
            $query=mysqli_query($cn, $sql);

            echo "
            <table id='table_periodos' class='table table-striped table_periodos table_diseño' >
            <tbody>
                <tr class='table-active'>
                    <th scope='col'>ID</th>
                    <th scope='col' colspan='1'>Fecha inicio</th>
                    <th scope='col' colspan='1'>Fecha final</th>
                    <th scope='col' colspan='1'>Contador</th>
                    <th scope='col'>ID curso</th>
                    <th scope='col' colspan='1'>ID semestre</th>
                    <th scope='col'>Acciones</th>

                </tr>

        ";
                            while ($datos=mysqli_fetch_assoc($query)) {
                                echo "
                    <tr>
                    <td>{$datos['idperiodo']} </td>
                    <td>{$datos['fecha_inicio']}</td>
                    <td> {$datos['fecha_fin']}</td>
                    <td>{$datos['reinicio_contador']}</td>
                    <td>{$datos['id_curso']}</td>
                    <td>{$datos['id_semestre']}</td>
                    <td>

                <a accion='editar' title='editar' id='btneditar_periodo' class='btneditar_periodo editar_elemento'>
                    <img class ='img_btn_editar' id='editar' src='img/editar.svg'/>
                    </a>

                    <a accion='eliminar' title='eliminar' id='btndelete_periodo' class='btndelete_periodo eliminar_elemento'>
                        <img class ='img_btn_borrar' id='eliminar'  src='img/borrar.svg'/>
                        </a>


                    </td>

                </tr>

                ";
                            }

                            break;
                }/*FIN CASE MOSTRAR PERIODO*/


 /* COMIENZA CASE GUARDAR PERIODO */
case "guardar_periodo":{
        if ((!empty($_POST['fechainicio_periodo']))&&(!empty($_POST['fechafinal_periodo']))&&
        (!empty($_POST['id_cursoescolar']))&&(!empty($_POST['id_semestre']))) {
            $sql_ultcontador="SELECT reinicio_contador FROM periodos ORDER BY idPeriodo DESC LIMIT 1";
            $query_contador=mysqli_query($cn, $sql_ultcontador);
            while ($contador=mysqli_fetch_array($query_contador)) {
                $reinicio_contador=$contador['reinicio_contador'];
            }

            if ($reinicio_contador==0) {
                $reinicio_contador=1;
            } elseif ($reinicio_contador==1) {
                $reinicio_contador=0;
            }
            $fechainicio_periodo=strtr($_POST['fechainicio_periodo'], '-', '/');
            $fechafinal_periodo=strtr($_POST['fechafinal_periodo'], '-', '/');
            $id_cursoescolar=$_POST['id_cursoescolar'];
            $id_semestre=$_POST['id_semestre'];

            $sql="INSERT INTO periodos(idperiodo,fecha_inicio,fecha_fin,reinicio_contador,id_curso,id_semestre) VALUES (' ','".$fechainicio_periodo."','".$fechafinal_periodo."','".$reinicio_contador."','".$id_cursoescolar."','".$id_semestre."')";
            $queryguardar=mysqli_query($cn, $sql);
        }
            break;
} /* TERMINA CASE GUARDAR PERIODO */

/* COMIENZA CASE EDITAR PERIODO */
case "editar_periodo":
            {
              $fechainicio_periodo=strtr($_POST['update_fechainicio'], '-', '/');
              $fechafinal_periodo=strtr($_POST['update_fechafinal'], '-', '/');
                    $editar="UPDATE periodos set fecha_inicio='".$fechainicio_periodo."',fecha_fin='".$fechafinal_periodo ."',id_curso='".$_POST['update_idcursoescolar']."',id_semestre='".$_POST['update_idsemestre']."' WHERE idperiodo='".$_POST['update_idperiodo']."'";
                    $queryeditar=mysqli_query($cn,$editar);
                      break;
                }
case "eliminar_periodo":
{
                    if (!empty($_POST['idPeriodo'])) {
                        $eliminar="DELETE FROM periodos WHERE idPeriodo='".$_POST['idPeriodo']."'";
                        $queryeliminar=mysqli_query($cn, $eliminar);
                    }
              break;
            }
                    }  /* FIN SWITCH PERIDOS */
            } /* FIN VALIDACION IF DE CAMPO VACIO ACCION_PERIODOS */
?>
