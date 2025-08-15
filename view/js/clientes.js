/*======================================
  VISUALIZAR CLIENTE
======================================**/
$(document).on("click", ".btnVerCliente", function(){
	var idCliente = $(this).attr("idCliente");
	var datos = new FormData();
	datos.append("idCliente", idCliente);
	$.ajax({
		url: "ajax/clients.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData:false,
		dataType: "json",
		success:function(respuesta){

			$("#verCodigo").val(respuesta["codigo"]);
			$("#verNacionalidad").val(respuesta["tipo_documento"]);
			$("#verNacionalidad").html(respuesta["tipo_documento"]);
			$("#verDocumento").val(respuesta["documento"]);
			$("#verNombre").val(respuesta["nombre"]);
	        $("#verEmail").val(respuesta["email"]);
	        $("#verTelefono").val(respuesta["telefono"]);
	        $("#verDireccion").val(respuesta["direccion"]);
		}
	})
})

/*======================================
  EDITAR CLIENTE
======================================**/
$(document).on("click", ".btnEditarCliente", function(){
	var idCliente = $(this).attr("idCliente");
	var datos = new FormData();
	datos.append("idCliente", idCliente);
	$.ajax({
		url: "ajax/clients.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData:false,
		dataType: "json",
		success:function(respuesta){

			$("#idCliente").val(respuesta["id"]);
			$("#editarCodigo").val(respuesta["codigo"]);
			$("#editarNacionalidad").val(respuesta["tipo_documento"]);
			$("#editarNacionalidad").html(respuesta["tipo_documento"]);
			$("#editarDocumento").val(respuesta["documento"]);
			$("#editarNombre").val(respuesta["nombre"]);
	        $("#editarEmail").val(respuesta["email"]);
	        $("#editarTelefono").val(respuesta["telefono"]);
	        $("#editarDireccion").val(respuesta["direccion"]);

		}
	})
})

  /**=====================================
  ELIMINAR CLIENTE
  ======================================**/
$(document).on("click", ".btnEliminarCliente", function(){
  	var idCliente = $(this).attr("idCliente");
  	Swal.fire({
  		title: '¿Está seguro de borrar el cliente?',
	 	text: "¡Si no lo está puede cancelar la acción!",
	 	icon: 'warning',
	 	showCancelButton: true,
	 	confirmButtonColor: '#3085d6',
	 	cancelButtonColor: '#d33',
	 	cancelButtonText: 'Cancelar',
	 	confirmButtonText: 'Si, borrar cliente!'
	}).then((result) => {
		if (result.value){
			window.location = "index.php?ruta=clientes&idCliente="+idCliente;
		}
	})
})