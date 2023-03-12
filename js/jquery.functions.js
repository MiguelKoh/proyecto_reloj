// funciones del calendario

$(document).ready(function(){
	//div donde se mostrar� calendario debe estar oculto					   
	$('#calendario').hide();
});

function update_calendar(){
	var month = $('#calendar_mes').attr('value');
	var year = $('#calendar_anio').attr('value');

	var valores='month='+month+'&year='+year;

	$.ajax({
		url: '/calendario/setvalue.php',
		type: "GET",
		data: valores,
		success: function(datos){
			$("#calendario_dias").html(datos);
		}
	});
}
	
function set_date(date){
	//input text donde debe aparecer la fecha
	$('#fecha').attr('value',date);
	show_calendar();
}

function show_calendar(){
	//div donde se mostrar� calendario
	$('#calendario').toggle(); 
}	