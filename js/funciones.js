
  /*Validacion del campo tipo empleado */

/* function confirmarEliminacion(){   */
   $(".eliminar_tipoempleado").click(function(e){
     e.preventDefault();
    var urlToRedirect = this.getAttribute('href')

console.log(urlToRedirect)
   Swal.fire({
        title: '¿Estas seguro que deseas eliminar el registro?',
        text: "Esta acción no sera capaz de revertirse!",
        icon: 'Advertencia',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Cancelar',
        confirmButtonText: 'Si, eliminar!'
      }).then((result) => {

        if (result.isConfirmed) {
          swal.fire({
          type: "success",
          icon: "success",
          title: "¡Eliminado!",
          text: "El registro ha sido eliminado",
          allowEnterKey:true,

        }).then(okay => {
          if(okay){
            window.location.href=urlToRedirect
          }

        })
        /* window.location= this.getAttribute('href') */
        }
      })


});


/* GUARDAR TIPO EMPLEADO */
 $('#guardar_tipoempleado').bind("click",function (e)
				{

					e.preventDefault();
					var tipoEmpleado=$("#tipoempleado").val();
					var esEmpleado=$("#esEmpleado").val();
					$.ajax({
						type: "GET",
						url: "ajax_catalogos/tipo_emp_pruebas.php",
						cache: false,
						data: { tipoEmpleado:tipoEmpleado,esEmpleado:esEmpleado,accion:"guardar_tipoempleado",

          }
					}).done(function( respuesta2 )
					{
            if(tipoEmpleado!=''){
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'El tipo empleado ha sido registrado',
              showConfirmButton: false,
              timer:1500
            })

            setTimeout(function () {
              window.location.href="tipos_empleados.php"; //will redirect to your blog page (an ex: blog.html)
           }, 2000); //will call the function after 2 secs.
          }else{
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'Faltan campos por llenar',
              showConfirmButton: false,
              timer:1500
            })
          }


            /* $('#table_tipoempleados').load('tipos_empleados.php table tbody tr'); */



					});

				});

$('.editar_tipoempleado').on("click",function(){
$('#modalEditar').modal('show');
$tr = $(this).closest('tr');
var data =$tr.children("td").map(function(){

  return $(this).text();
}).get();
  console.log(data);
  $('#update_idTipo').val(data[0]);
  $('#Descripcion').val(data[1]);
  $('#esempleado').val(data[2]);

});
/**EDITAR TIPO EMPLEADO */
$('.actualizar_datos').click(function(e){
e.preventDefault();
var update_idTipo=$('#update_idTipo').val();
var tipoEmp=$('#Descripcion').val();
var esEmp=$('#esempleado').val();
$.ajax({
  type:"GET",
  url:"ajax_catalogos/tipo_emp_pruebas.php",
  cache:false,
  data: {tipoEmp:tipoEmp,esEmp:esEmp,update_idTipo:update_idTipo,accion:"editar_tipoempleado"}
}).done(function(respuesta){
  if(!empty(tipoEmp)){

    Swal.fire({
      position: 'center',
      icon: 'success',
      title: 'Registro actualizado exitosamente',
      showConfirmButton:false,
      timer:1500
    })
     setTimeout(function(){
      window.location.href="tipos_empleados.php";
    },2000)
  }else{
    Swal.fire({
      position:'center',
      icon:'error',
      text:'Faltan campos por llenar',
      showConfirmButton:false,
      Timer:1000
    })
  }
});
});

/***FUNCIONES lista_cursoEscolar.php */

$(function(){
  $('#periodoInicial').datepicker({
    format:"yyyy",
    viewMode:"years",
    minViewMode:"years",

  });
})

$(function(){
  $('#periodoFinal').datepicker({
    format:"yyyy",
    viewMode:"years",
    minViewMode:"years"
  })
})

$('#guardar_cursoEscolar').bind("click",function(e){
e.preventDefault();
var periodoInicial=$("#periodoInicial").val();
var periodoFinal=$("#periodoFinal").val();
$.ajax({
  type:"POST",
  url:"ajax_catalogos/cursoEscolar_ajax.php",
  cache:false,
  data:{periodoInicial:periodoInicial,periodoFinal:periodoFinal,accion_cursoEscolar:"guardar_cursoEscolar"}
,success:function(response){
   Swal.fire({
     icon: 'success',
     title:'Curso escolar registrado con exito',
     showCancelButton:false,
     showConfirmButton:false,
     timer:1500
   })
   setTimeout(function(){
     window.location.href="lista_cursoEscolar.php";
   },2000)
}
});
});

/* $('#btn_prueba').bind("click",function(e){
  e.preventDefault();
  alert("exitoso");
}) */

/* ELIMINAR CURSO ESCOLAR */

$('.eliminar_cursoEscolar').bind("click",function(e){
  e.preventDefault();
  /**OBTENEMOS EL DATO DE ID Y LO ASIGNAMOS AL INPUT HIDDEN */
  $tr = $(this).closest('tr');
  var data =$tr.children("td").map(function(){

    return $(this).text();
  }).get();
    console.log(data);
    $('#id_cursoescolar').val(data[0]);
/**ASIGNAMOS EL VALOR DEL ID A UNA VARIABLE Y UTILIZAMOS AJAX */
  var idCurso=$('#id_cursoescolar').val();

      Swal.fire({
        title: '¿Estas seguro que deseas eliminar el registro?',
        text: "Esta acción no sera capaz de revertirse!",
        icon: 'Advertencia',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Cancelar',
        confirmButtonText: 'Si, eliminar!'
      }).then((result) => {

        if (result.isConfirmed) {
          $.ajax({
            type:"POST",
            url:"ajax_catalogos/cursoEscolar_ajax.php",
            cache:false,
            data:{idCurso:idCurso,accion_cursoEscolar:"eliminar_cursoEscolar"},
            success:function(response){
          swal.fire({
          type: "success",
          icon: "success",
          title: "¡Eliminado!",
          text: "El registro ha sido eliminado",
          allowEnterKey:true,

        }).then(okay => {
          if(okay){
            setTimeout(function(){
              window.location.href="lista_cursoEscolar.php";
            },500)
          }

        })
        /* window.location= this.getAttribute('href') */
        }
      })
    }
  });
});


/* FUNCIONES SEMESTRE.PHP */
$(function(){
  $('#fecha_inicioSemestre').datepicker({
    format:"yyyy-mm-dd",
    viewMode:"date",
    minViewMode:"date",

  });
})

$(function(){
  $('#fecha_finalSemestre').datepicker({
    format:"yyyy-mm-dd",
    viewMode:"date",
    minViewMode:"date"
  })
})


$('#guardar_Semestre').bind("click",function(e){
  e.preventDefault();
  var numsemestre = $('#NumSemestre').val();
  var idcursoescolar=$('#idcursoEscolar').val();
  var periodoInicial=$("#fecha_inicioSemestre").val();
  var periodoFinal=$("#fecha_finalSemestre").val();


  $.ajax({
    type:"POST",
    url:"ajax_catalogos/funciones_ajax.php",
    cache:false,
    data:{
      numsemestre:numsemestre,
      idcursoescolar:idcursoescolar,
      periodoInicial:periodoInicial,
      periodoFinal:periodoFinal,
      accion_Semestre:"guardar_semestre"}
  ,success:function(response){
     Swal.fire({
       icon: 'success',
       title:'Semestre registrado con exito',
       showCancelButton:false,
       showConfirmButton:false,
       timer:1500
     })
     setTimeout(function(){
       window.location.href="lista_semestres.php";
     },2000)
  }
  });
  });


$('.eliminar_Semestre').bind("click",function(e){
  e.preventDefault();
  /**OBTENEMOS EL DATO DE ID Y LO ASIGNAMOS AL INPUT HIDDEN */
  $tr = $(this).closest('tr');
  var data =$tr.children("td").map(function(){

    return $(this).text();
  }).get();
    console.log(data);
    $('#idSemestre').val(data[0]);
/**ASIGNAMOS EL VALOR DEL ID A UNA VARIABLE Y UTILIZAMOS AJAX */
  var idSemestre=$('#idSemestre').val();
      Swal.fire({
        title: '¿Estas seguro que deseas eliminar el registro?',
        text: "Esta acción no sera capaz de revertirse!",
        icon: 'Advertencia',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Cancelar',
        confirmButtonText: 'Si, eliminar!'
      }).then((result) => {

        if (result.isConfirmed) {
          $.ajax({
            type:"POST",
            url:"ajax_catalogos/funciones_ajax.php",
            cache:false,
            data:{idSemestre:idSemestre,accion_Semestre:"eliminar_Semestre"},
                   success:function(response){
                    swal.fire({
                      type: "success",
                      icon: "success",
                      title: "¡Eliminado!",
                      text: "El registro ha sido eliminado",
                      allowEnterKey:true,

                    }).then(okay => {
                  if(okay){
                    window.location.href="lista_semestres.php";
                  }
        })

        }
        /* window.location= this.getAttribute('href') */
        })
      }
    })
  })


/* FUNCIONES PARA PERIODOS.PHP */

$(function(){
  $('#fechainicio_periodo').datepicker({
    format:"dd-mm-yyyy",
    viewMode:"date",
    minViewMode:"date",

  });
})

$(function(){
  $('#fechafinal_periodo').datepicker({
    format:"dd-mm-yyyy",
    viewMode:"date",
    minViewMode:"date"
  })
})


function mostrarPeriodo(){
  var opcion = $('.select_periodos').val();

  $.ajax({
    type:'POST',
    url:'ajax_catalogos/funciones_ajax.php',
    cache:false,
    data:{
      opcion:opcion,
      accion_periodos:"mostrar_periodo"},
      success:function(response){

        $('.divtable_periodos').html(response);
        $('.divtable_periodos').css('display','block');
      }
    })
  }


  $('#guardar_periodo').bind("click",function(e){
    e.preventDefault();
    var fechainicio_periodo = $('#fechainicio_periodo').val();
    var fechafinal_periodo=$('#fechafinal_periodo').val();
    var id_cursoescolar=$("#id_cursoescolar").val();
    var id_semestre=$("#id_semestre").val();

    if((fechainicio_periodo!='')&&
    (fechafinal_periodo!='')&&
    (id_cursoescolar!='')&&
    (update_idcursoescolar!='')){

    $.ajax({
      type:"POST",
      url:"ajax_catalogos/funciones_ajax.php",
      cache:false,
      data:{
        fechainicio_periodo:fechainicio_periodo,
        fechafinal_periodo:fechafinal_periodo,
        id_cursoescolar:id_cursoescolar,
        id_semestre:id_semestre,
        accion_periodos:"guardar_periodo"}
    ,success:function(response){
       Swal.fire({
         icon: 'success',
         title:'Periodo registrado con exito',
         showCancelButton:false,
         showConfirmButton:false,
         timer:1500
       })
       setTimeout(function(){
         window.location.href="periodos.php";
       },2000)
    }

    });
  }else{
    Swal.fire({
      position:'center',
      icon:'error',
      text:'Faltan campos por llenar',
      showConfirmButton:false,
      timer:1000
    })
  }
    });

  /**EDITAR PERIODO */
  /* cuando agregamos algo de contenido html dinámicamente, no se vincula,
  es por eso que el evento de clic no actúa. en ese caso usamos $(document) */

  $(function(){
    $('#update_fechainicio').datepicker({
      format:"dd-mm-yyyy",
      viewMode:"date",
      minViewMode:"date",
    });
  })

  $(function(){
    $('#update_fechafinal').datepicker({
      format:"dd-mm-yyyy",
      viewMode:"date",
      minViewMode:"date"
    })
  })


  $(document).on('click', '.btneditar_periodo', function(){
$('#modalEditarPeriodo').modal('show');
  $tr = $(this).closest('tr');
  var data =$tr.children("td").map(function(){

    return $(this).text();
  }).get();
    console.log(data);
    $('#update_idperiodo').val(data[0]);
    $('#update_fechainicio').val(data[1]);
    $('#update_fechafinal').val(data[2]);
    $('#update_idcursoescolar').val(data[4]);
    $('#update_idsemestre').val(data[5]);
  });

  $('#actualizar_periodo').click(function(e){
  e.preventDefault();
  var update_idperiodo=$('#update_idperiodo').val();
  var update_fechainicio=$('#update_fechainicio').val();
  var update_fechafinal=$('#update_fechafinal').val();
  var update_idcursoescolar=$('#update_idcursoescolar').val();
  var update_idsemestre=$('#update_idsemestre').val();



    if((update_idperiodo!='')&&
    (update_fechainicio!='')&&
    (update_fechafinal!='')&&
    (update_idcursoescolar!='')&&
    (update_idsemestre!='')){


      $.ajax({
        type:"POST",
        url:"ajax_catalogos/funciones_ajax.php",
        cache:false,
        data: {update_idperiodo:update_idperiodo,update_fechainicio:update_fechainicio,
          update_fechafinal:update_fechafinal,update_idcursoescolar:update_idcursoescolar,
          update_idsemestre:update_idsemestre,accion_periodos:"editar_periodo"},
          success:function(response){
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Registro actualizado exitosamente',
        showConfirmButton:false,
        timer:1500
      })
       setTimeout(function(){
        window.location.href="periodos.php";
      },2000)
    }
  })
    }else{
      Swal.fire({
        position:'center',
        icon:'error',
        text:'Faltan campos por llenar',
        showConfirmButton:false,
        timer:1000
      })
    }
    })



  $(document).on('click', '.btndelete_periodo', function(){

    
      /**OBTENEMOS EL DATO DE ID Y LO ASIGNAMOS AL INPUT HIDDEN */
      $tr = $(this).closest('tr');
      var data =$tr.children("td").map(function(){

        return $(this).text();
      }).get();
        console.log(data);
        $('#delete_periodo').val(data[0]);
    /**ASIGNAMOS EL VALOR DEL ID A UNA VARIABLE Y UTILIZAMOS AJAX */
      var idPeriodo=$('#delete_periodo').val();
          Swal.fire({
            title: '¿Estas seguro que deseas eliminar el registro?',
            text: "Esta acción no sera capaz de revertirse!",
            icon: 'Advertencia',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText:'Cancelar',
            confirmButtonText: 'Si, eliminar!'
          }).then((result) => {

            if (result.isConfirmed) {
              $.ajax({
                type:"POST",
                url:"ajax_catalogos/funciones_ajax.php",
                cache:false,
                data:{idPeriodo:idPeriodo,accion_periodos:"eliminar_periodo"},
                       success:function(response){
                        swal.fire({
                          type: "success",
                          icon: "success",
                          title: "¡Eliminado!",
                          text: "El registro ha sido eliminado",
                          allowEnterKey:true,

                        }).then(okay => {
                      if(okay){
                        window.location.href="periodos.php";
                      }
            })

            }
            /* window.location= this.getAttribute('href') */
            })
          }
        })


    });
