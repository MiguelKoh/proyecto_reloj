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

  $('guardar_cursoEscolar').bind("click",function(e){
    e.preventDefault();
    var periodoInicial=$("#periodoInicial").val();
    var periodoFinal=$("#periodoFinal").val();
    $.ajax({
      type:"POST",
      url:"../ajax_catalogos/cursoEscolar_ajax.php",
      cache:false,
      data:{periodoInicial:periodoInicial,periodoFinal:periodoFinal,accion:"guardar_cursoEscolar"}
    }).done(function(response){
       alert("curso guardado")
    })
  });
  
$('#btn_prueba').bind("click",function(e){
  e.preventDefault();
  alert("exitoso");
})