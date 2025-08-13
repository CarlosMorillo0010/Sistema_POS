/**=====================================
EDITAR CUENTA POR COBRAR
======================================**/

$(".btnEditarCuentaCobrar").click(function(){

	var idCuentaCobrar = $(this).attr("idCuentaCobrar");

	var datos = new FormData();
	datos.append("idCuentaCobrar", idCuentaCobrar);

	$.ajax({
		url: "ajax/cuentas-cobrar.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData:false,
		dataType: "json",
		success:function(respuesta){

			$("#idCuentaCobrar").val(respuesta["id_cuentas_cobrar"]);
	        $("#editarCodigo").val(respuesta["codigo"]);
	        $("#editarProveedor").val(respuesta["proveedor"]);
	        $("#editarFechaCuenta").val(respuesta["fecha_cuenta"]);
	        $("#editarFechaAno").val(respuesta["fecha_ano"]);
	        $("#editarFechaFactura").val(respuesta["fecha_factura"]);
	        $("#editarNombre").val(respuesta["nombre"]);
	        $("#editarTipoDocumento").val(respuesta["tipo_documento"]);
	        $("#editarDocumento").val(respuesta["documento"]);
	        $("#editarMonto").val(respuesta["monto"]);
	        $("#editarSaldo").val(respuesta["saldo"]);
	        $("#editarFecha").val(respuesta["fecha"]);
	        $("#editarDescripcion").val(respuesta["descripcion"]);
	        $("#editarEstatus").val(respuesta["estatus"]);

		}

	})

})

/**=====================================
VER CUENTA POR COBRAR
======================================**/

$(".btnVerCuentaCobrar").click(function(){

	var idCuentaCobrar = $(this).attr("idCuentaCobrar");

	var datos = new FormData();
	datos.append("idCuentaCobrar", idCuentaCobrar);

	$.ajax({
		url: "ajax/cuentas-cobrar.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData:false,
		dataType: "json",
		success:function(respuesta){

			$("#idCuentaCobrar").val(respuesta["id_cuentas_cobrar"]);
	        $("#verCodigo").val(respuesta["codigo"]);
	        $("#verProveedor").val(respuesta["proveedor"]);
	        $("#verFechaCuenta").val(respuesta["fecha_cuenta"]);
	        $("#verFechaAno").val(respuesta["fecha_ano"]);
	        $("#verFechaFactura").val(respuesta["fecha_factura"]);
	        $("#verNombre").val(respuesta["nombre"]);
	        $("#verTipoDocumento").val(respuesta["tipo_documento"]);
	        $("#verDocumento").val(respuesta["documento"]);
	        $("#verMonto").val(respuesta["monto"]);
	        $("#verSaldo").val(respuesta["saldo"]);
	        $("#verFecha").val(respuesta["fecha"]);
	        $("#verDescripcion").val(respuesta["descripcion"]);
	        $("#verEstatus").val(respuesta["estatus"]);

		}

	})

})

/**=====================================
 ELIMINAR CUENTAS POR COBRAR
======================================**/

$(".btnEliminarCuentaCobrar").click(function(){

  	var idCuentaCobrar = $(this).attr("idCuentaCobrar");

  	swal({
  		title: '¿Está seguro de borrar la cuenta?',
	 	text: "¡Si no lo está puede cancelar la acción!",
	 	type: 'warning',
	 	showCancelButton: true,
	 	confirmButtonColor: '#3085d6',
	 	cancelButtonColor: '#d33',
	 	cancelButtonText: 'Cancelar',
	 	confirmButtonText: 'Si, borrar cuenta!'
	}).then((result) => {
		if (result.value){

			window.location = "index.php?ruta=cuentas-cobrar&idCuentaCobrar="+idCuentaCobrar;

		}

	})

})