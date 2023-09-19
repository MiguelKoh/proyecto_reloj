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

const obtenerPermisos = async (idDepto,idEmpleado,fechaInicio,fechaFin,horaInicio,minInicio,horaFin,minFin,tipoPermiso,descPermiso) => {
   
   try {
     
     const permiso = await fetch(`./guardar-permisos.php?idDepto=${idDepto}&idEmp=${idEmpleado}&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}&horaInicio=${horaInicio}
      &horaFin=${horaFin}&minInicio=${minInicio}&minFin=${minFin}&tipoPermiso=${tipoPermiso}&descPermiso=${descPermiso}`);
     const respuesta = await permiso.json();
     console.log(respuesta)

  } catch(error){
    console.log(error);
  }

}

btnGuardar.addEventListener('click', () => {
  obtenerPermisos(idDepto, idEmpleado, fechaInicio, fechaFin, horaInicio, minInicio, horaFin, minFin, tipoPermiso, descPermiso);
});
