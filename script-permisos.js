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

const obtenerPermisos = async (idDepto, idEmpleado, fechaInicio, fechaFin, horaInicio, minInicio, horaFin, minFin, tipoPermiso, descPermiso) => {
  try {
    const permiso = await fetch(`./guardar-permisos.php?idDepto=${idDepto}&idEmp=${idEmpleado}&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}&horaInicio=${horaInicio}&horaFin=${horaFin}&minInicio=${minInicio}&minFin=${minFin}&tipoPermiso=${tipoPermiso}&descPermiso=${descPermiso}`);
    const respuesta = await permiso.json();
    
    // Obtén el elemento donde insertar la tabla
    let divPermisos = document.getElementById("permisosGuardados");
    
    // Crear la tabla
    let tablaHTML = '<table border="1"><tr><th>Fecha</th><th>Día</th><th>Hora Inicio</th><th>Hora Fin</th><th>Tipo de Permiso</th><th>Motivo</th><th>Minutos</th></tr>';

    // Llenar la tabla con datos de la respuesta JSON
    respuesta.forEach((permiso) => {
      tablaHTML += `<tr><td>${permiso.fecha}</td><td>${permiso.dia}</td><td>${permiso.hora_inicio}</td><td>${permiso.hora_fin}</td><td>${permiso.tipo_permiso}</td><td>${permiso.motivo}</td><td>${permiso.Minutos}</td></tr>`;
    });

    tablaHTML += '</table>';

    // Insertar la tabla en el elemento divPermisos
    divPermisos.innerHTML = tablaHTML;
  } catch (error) {
    console.log(error);
  }
}

btnGuardar.addEventListener('click', () => {
  obtenerPermisos(idDepto, idEmpleado, fechaInicio, fechaFin, horaInicio, minInicio, horaFin, minFin, tipoPermiso, descPermiso);
});
