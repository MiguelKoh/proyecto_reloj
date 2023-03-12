<?php
   session_start();

include("conex.php");

        $opcionSeleccionada=$_GET["opcion"];
        
	$cn = ConectaBD(); 
        
        $consulta=mysqli_query($cn,"SELECT idEmp, Nombre FROM empleado WHERE Estatus is Null AND  iddepto='$opcionSeleccionada' ORDER BY Nombre") or die(mysqli_error());

         mysqli_close($cn);
	
        // Comienzo a imprimir el select

	echo "<select onChange='test(this.value)' name='lstEmpleado' id='lstEmpleado'>";
	echo "<option value='0'>TODOS</option>";
        
	while($registro=mysqli_fetch_array($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);

		// Imprimo las opciones del select
		echo "<option value='".$registro['idEmp']."'>".$registro['idEmp'].' - '.utf8_encode($registro['Nombre'])."</option>";
	}			
	echo "</select>";                       

?>