/*======================================
  VISUALIZAR VEHICULO
======================================**/
$(document).on("click", ".btnVerVehiculo", function(){
  var idVehiculo = $(this).attr("idVehiculo");
  var datos = new FormData();
  datos.append("idVehiculo", idVehiculo);
  $.ajax({
    url: "ajax/vehiculos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData:false,
    dataType: "json",
    success:function(respuesta){

      $("#idVehiculo").val(respuesta["id_vehiculo"]);
      $("#verMarca").val(respuesta["marca"]);
      $("#verMarca").html(respuesta["marca"]);
      $("#verModelo").val(respuesta["modelo"]);
      $("#verModelo").html(respuesta["modelo"]);
      $("#verAno").val(respuesta["ano"]);
      $("#verPlaca").val(respuesta["placa"]);
      $("#verColor").val(respuesta["color"]);
      $("#verSerialChasis").val(respuesta["serial_chasis"]);
      $("#verSerialMotor").val(respuesta["serial_motor"]);
      $("#verDescripcion").val(respuesta["descripcion"]);

    }
  })
})

/*======================================
  EDITAR VEHICULO
======================================**/
$(document).on("click", ".btnEditarVehiculo", function(){
  var idVehiculo = $(this).attr("idVehiculo");
  var datos = new FormData();
  datos.append("idVehiculo", idVehiculo);
  $.ajax({
    url: "ajax/vehiculos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData:false,
    dataType: "json",
    success:function(respuesta){

      $("#idVehiculo").val(respuesta["id_vehiculo"]);
      $("#editarMarca").val(respuesta["marca"]);
      $("#editarMarca").html(respuesta["marca"]);
      $("#editarModelo").val(respuesta["modelo"]);
      $("#editarModelo").html(respuesta["modelo"]);
      $("#editarAno").val(respuesta["ano"]);
      $("#editarPlaca").val(respuesta["placa"]);
      $("#editarColor").val(respuesta["color"]);
      $("#editarSerialChasis").val(respuesta["serial_chasis"]);
      $("#editarSerialMotor").val(respuesta["serial_motor"]);
      $("#editarDescripcion").val(respuesta["descripcion"]);

    }
  })
})

/**=====================================
 ELIMINAR VEHICULO
 ======================================**/
$(document).on("click", ".btnEliminarVehiculo", function(){
  var idVehiculo = $(this).attr("idVehiculo");
  Swal.fire({
    title: "¿Está seguro de borrar el vehiculo?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar vehiculo!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=vehiculos&idVehiculo=" + idVehiculo;
    }
  });
});