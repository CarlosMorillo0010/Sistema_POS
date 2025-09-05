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

			$("#verDocumento").val(respuesta["tipo_documento"] + respuesta["documento"]);
			$("#verNombre").val(respuesta["nombre"]);

            // Parse and display telefono
            var telefono = respuesta["telefono"] || "";
            var tel1 = "";
            var tel2 = "";
            if (telefono.includes("Cel: ")) {
                var parts = telefono.split(" / ");
                tel1 = parts[0].replace("Cel: ", "");
                if (parts.length > 1 && parts[1].includes("Fijo: ")) {
                    tel2 = parts[1].replace("Fijo: ", "");
                }
            } else if (telefono.includes("Fijo: ")) {
                tel2 = telefono.replace("Fijo: ", "");
            }
            $("#verTelefono1").val(tel1);
            $("#verTelefono2").val(tel2);

            // Parse and display direccion
            var direccionCompleta = respuesta["direccion"] || "";
            var direccionParts = direccionCompleta.split(", ");
            var estado = "";
            var ciudad = "";
            var direccion = "";
            if (direccionParts.length >= 3) {
                estado = direccionParts[0];
                ciudad = direccionParts[1];
                direccion = direccionParts.slice(2).join(", ");
            } else if (direccionParts.length == 2) {
                estado = direccionParts[0];
                ciudad = direccionParts[1];
            } else if (direccionParts.length == 1) {
                direccion = direccionParts[0];
            }
            $("#verEstado").val(estado);
            $("#verCiudad").val(ciudad);
            $("#verDireccion").val(direccion);
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
			$("#editarNacionalidadSelect").val(respuesta["tipo_documento"]);
			$("#editarDocumento").val(respuesta["documento"]);
			$("#editarNombre").val(respuesta["nombre"]);
	        
            // Parse and populate telefono
            var telefono = respuesta["telefono"] || "";
            var tel1 = "";
            var tel2 = "";
            if (telefono.includes("Cel: ")) {
                var parts = telefono.split(" / ");
                tel1 = parts[0].replace("Cel: ", "");
                if (parts.length > 1 && parts[1].includes("Fijo: ")) {
                    tel2 = parts[1].replace("Fijo: ", "");
                }
            } else if (telefono.includes("Fijo: ")) {
                tel2 = telefono.replace("Fijo: ", "");
            }
            $("#editarTelefono1").val(tel1);
            $("#editarTelefono2").val(tel2);

            // Parse and populate direccion
            var direccionCompleta = respuesta["direccion"] || "";
            var direccionParts = direccionCompleta.split(", ");
            var estado = "";
            var ciudad = "";
            var direccion = "";
            if (direccionParts.length >= 3) {
                estado = direccionParts[0];
                ciudad = direccionParts[1];
                direccion = direccionParts.slice(2).join(", ");
            } else if (direccionParts.length == 2) {
                estado = direccionParts[0];
                ciudad = direccionParts[1];
            } else if (direccionParts.length == 1) {
                direccion = direccionParts[0];
            }
            $("#editarEstado").val(estado);
            $("#editarCiudad").val(ciudad);
            $("#editarDireccion").val(direccion);
		}
	})
})

  /**=====================================
  ELIMINAR CLIENTE
  ======================================**/
$(document).on("click", ".btnEliminarCliente", function(){
    var idCliente = $(this).attr("idCliente");
    var totalCompras = parseInt($(this).attr("data-compras"));

    if(totalCompras > 0){
        Swal.fire({
            title: 'Acción no permitida',
            text: "Este cliente no se puede eliminar porque tiene compras registradas.",
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    } else {
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
        });
    }
})
