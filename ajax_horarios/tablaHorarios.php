<?php 
include('../conex.php');
$cn = ConectaBD();


if(empty($_GET['id_semestre'])){
    $id_semestre="";
}else{
    $id_semestre=$_GET['id_semestre'];
}//Fin del else...


if(empty($_GET['id_empleado'])){
    $id_empleado="";
}else{
    $id_empleado=$_GET['id_empleado'];
}//Fin del else...


$html="";
$datos=Array();
$respuesta="";



if($id_semestre!="" && $id_empleado!=""){

//construccion de la tabla 
$recuperar="SELECT * FROM reg_respuestas";
$query= mysqli_query($cn,$recuperar);
$contador=0;

$html.= "
    <table class='table col-lg-6 col-sm-4 col-md-12 text-white'>
    <tr>
    <td scope='col'class='weight'>#</td>
    <td scope='col'class='weight'>Pregunta</td>
    <td scope='col'class='weight'>Tipo de respuesta</td>
    <td scope='col'class='weight'>Accion</td>
    </tr>";

while($fila=mysqli_fetch_array($query)){
$contador++;

        $html.="<tr>";
        $html.="<td>".$contador."</td>";
        $html.="<td>".$fila['pregunta']."</td>";
        $html.="<td>".$fila['tipoencuesta']."</td>";
        $idResp=$fila['id_resp'];

        $html.="</tr>";

 }//fin del while
 
$html.= "</table>";


}//fin del if 
else{
    $html.="<p>No hay horarios disponibles</p>";
}


$datos["html"]=$html;

echo json_encode($datos);

 ?>