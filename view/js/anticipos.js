/**=====================================
EDITAR ANTICIPO
======================================**/

$(".btnEditarAnticipo").click(function(){

	var idAnticipo = $(this).attr("idAnticipo");

	var datos = new FormData();
	datos.append("idAnticipo", idAnticipo);

	$.ajax({
		url: "ajax/anticipos.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData:false,
		dataType: "json",
		success:function(respuesta){

			$("#idAnticipo").val(respuesta["id_anticipo"]);
	        $("#editarCodigo").val(respuesta["codigo"]);
	        $("#editarMonto").val(respuesta["monto"]);
	        $("#editarSaldo").val(respuesta["saldo"]);
	        $("#editarFecha").val(respuesta["fecha"]);
	        $("#editarDescripcion").val(respuesta["descripcion"]);
	        $("#editarEstatus").val(respuesta["estatus"]);

		}

	})

})

  /**=====================================
  ELIMINAR ANTICIPO
  ======================================**/

$(".btnEliminarAnticipo").click(function(){

  	var idAnticipo = $(this).attr("idAnticipo");

  	swal({
  		title: '¿Está seguro de borrar el anticipo?',
	 	text: "¡Si no lo está puede cancelar la acción!",
	 	type: 'warning',
	 	showCancelButton: true,
	 	confirmButtonColor: '#3085d6',
	 	cancelButtonColor: '#d33',
	 	cancelButtonText: 'Cancelar',
	 	confirmButtonText: 'Si, borrar anticipo!'
	}).then((result) => {
		if (result.value){

			window.location = "index.php?ruta=anticipos&idAnticipo="+idAnticipo;

		}

	})

})