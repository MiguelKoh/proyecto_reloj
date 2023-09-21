let idDepto = document.getElementById("idDepto").value;
let idEmpleado = document.getElementById("idEmp").value;
let fechaInicio = document.getElementById("fechaInicio").value;
let fechaFin = document.getElementById("fechaFin").value;
let horaInicio = document.getElementById("horaInicio").value;
let minInicio = document.getElementById("minInicio").value
let horaFin  = document.getElementById("horaFin").value;
let minFin = document.getElementById("minFin").value;
let tipoPermiso = document.getElementById("tipoPermiso").value;
let descPermiso = document.getElementById("descPermiso").value;

let btnGuardar = document.getElementById("grabarPermisos");


function validate(){
        var fecha = document.getElementById("fechaInicio").value;
        var fechaF = document.getElementById("fechaFin").value;
                if( fecha == null || fecha.length == 0 || /^\s+$/.test(fecha) ) {
                        alert("Debes proporcionar la fecha de inicio"); 
                        return false;
                }   

                        if( fechaF == null || fechaF.length == 0 || /^\s+$/.test(fechaF) ) {
                        alert("Debes proporcionar la fecha final"); 
                        return false;
                }


                return true;
        }



const obtenerPermisos = async (idDepto, idEmpleado, fechaInicio, fechaFin, horaInicio, minInicio, horaFin, minFin, tipoPermiso, descPermiso) => {
  try {
    const permiso = await fetch(`./guardar-permisos.php?idDepto=${idDepto}&idEmp=${idEmpleado}&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}&horaInicio=${horaInicio}&horaFin=${horaFin}&minInicio=${minInicio}&minFin=${minFin}&tipoPermiso=${tipoPermiso}&descPermiso=${descPermiso}`);
    const respuesta = await permiso.json();
    
   
    let divPermisos = document.getElementById("permisosGuardados");
    
    // Crear la tabla
    
    let tablaHTML = '<table border="1" class="tablaPermisos">' +
                '<tr>' +
                '<th>Fecha</th>' +
                '<th>Día</th>' +
                '<th>Hora Inicio</th>' +
                '<th>Hora Fin</th>' +
                '<th>Tipo de Permiso</th>' +
                '<th>Motivo</th>' +
                '<th>Minutos</th>' +
                '<th>Eliminar</th>' +
                '</tr>';
    
    respuesta.forEach((permiso) => {
      
      tablaHTML += 
     `<tr>
      <td>${permiso.fecha}</td>
      <td>${permiso.dia}</td>
      <td>${permiso.hora_inicio}</td>
      <td>${permiso.hora_fin}</td>
      <td>${permiso.tipo_permiso}</td>
      <td>${permiso.motivo}</td>
      <td>${permiso.Minutos}</td>
      <td><img src='imagen/deletepermiso.gif'</td>
      </tr>`;
    });

    tablaHTML += '</table>';


    // Insertar la tabla en el elemento divPermisos
    divPermisos.innerHTML = tablaHTML;

    console.log(respuesta)

  } catch (error) {
    console.log(error);
  }
}

btnGuardar.addEventListener('click', () => {

  var fecha = document.getElementById("fechaInicio").value;
  var fechaF = document.getElementById("fechaFin").value;
                

                if( fecha == null || fecha.length == 0 || /^\s+$/.test(fecha) ) {
                        alert("Debes proporcionar la fecha de inicio"); 
                        return;
                }   

                if( fechaF == null || fechaF.length == 0 || /^\s+$/.test(fechaF) ) {
                        alert("Debes proporcionar la fecha final"); 
                        return;
                }

                if(horaInicio=="00") {
                  alert("Debes proporcionar una hora de inicio")
                }

                if(horaFin=="00"){
                  alert("Debes proporcionar una hora final")
                }


    //que la fecha final no sea menor a la de inicio
        if (fechaInicio > fechaFin) {
            alert ("La fecha final no puede ser menor a la inicial")
        } else {
            if (horaInicio.trim() > horaFin.trim()) {
                alert("La hora final no puede ser menor a la inicial")
              
               }

          }


  obtenerPermisos(idDepto, idEmpleado, fechaInicio, fechaFin, horaInicio, minInicio, horaFin, minFin, tipoPermiso, descPermiso);



});
