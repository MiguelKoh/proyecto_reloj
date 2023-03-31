function modificarPregunta(){
	$(document).ready(function(){
		var id_semestre=$("#idSemestre").val();
        var id_empleado=$("#idEmp").val();

		var datos={
        "id_semestre":id_semestre,
        "id_empleado":id_empleado
        };
		//Función de ajax
		$.ajax({
			url:"tablaHorarios.php",
			dataType:"json",
			type:"get",
			data:datos,
			success:function(datos){
				if(datos.respuesta=="ok"){
					$("#tablaHorarios").html(datos.html);
				}//Fin del if
				//console.log(datos);
			},
			error:function(datos){
				console.log(datos);
			}//Fin de error
		});//Fin de Ajax
	});//Fin de document
}//Fin de modificarPregunta