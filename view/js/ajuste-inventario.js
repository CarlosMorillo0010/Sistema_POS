/*======================================
 AGREGANDO PRODUCTOS AJUSTE DE INVENTARIO
 ======================================**/
$(".btnAjusteInventario").click(function(){
	var datos = new FormData();
	datos.append("traerArticulos", "OK");

	$.ajax({
		url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){
			$(".nuevoAjusteProducto").append(
			'<div class="row" style="padding:5px 15px">'+
				'<!-- Descripción del producto -->'+
				'<div class="col-lg-3">'+
				  '<div class="input-group" style="padding:5px 0">'+
					'<select class="form-control nuevaDescripcionProducto" idProducto name="nuevaDescripcionProducto" required>'+
					'<option></option>'+
					'</select>'+
				  '</div>'+
				'</div>'+
				'<!-- Cantidad actual -->'+
				'<div class="col-lg-2 ingresarCantidadActual">'+
					'<div class="input-group" style="padding:5px 0">'+
						'<input type="text" class="form-control cantidadActual" name="cantidadActual" readonly required>'+
					'</div>' +
				'</div>'+
				'<!-- tipo de ajuste -->'+
				'<div class="col-lg-3">'+
					'<div class="input-group" style="padding:5px 0">'+
						'<select class="form-control tipoAjuste" name="" required>'+
							'<option></option>'+
							'<option value="incremento">Incremento</option>'+
							'<option value="decremento">Disminución</option>'+
						'</select>'+
				  	'</div>'+
				'</div>'+
				'<!-- Cantidad -->'+
				'<div class="col-lg-2">'+
					'<div class="input-group" style="padding:5px 0">'+
						'<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="0" value="0" stock nuevoStock required>'+
					'</div>' +
				'</div>'+
				'<!-- Cantidad final -->'+
				'<div class="col-lg-2 ingresarCantidadFinal">'+
					'<div class="input-group" style="padding:5px 0">'+
						'<input type="text" class="form-control cantidadFinal" name="cantidadFinal" readonly required>'+
					'</div>' +
				'</div>'+
			'</div>')
			// AGREGAR LOS PRODUCTOS AL SELECT
			respuesta.forEach(funcionForEach);
			function funcionForEach(item, index){
				if(item.stock != 0){
					$(".nuevaDescripcionProducto").append(
					   '<option idProducto="'+item.id_producto+'" value="'+item.descripcion+'">'+item.descripcion+'</option>'
					)
				}
			}
		}
	})
})

/*=======================================
 SELECCIONAR PRODUCTO
======================================**/
 $(".formulario__AjusteInventario").on("change", "select.nuevaDescripcionProducto", function () {
	 $("#nuevoAlmacen").attr("readonly", true);

	var nombreProducto = $(this).val();
	var cantidadActual = $(this).parent().parent().parent().children(".ingresarCantidadActual").children().children(".cantidadActual");
	var cantidadFinal = $(this).parent().parent().parent().children(".ingresarCantidadFinal").children().children(".cantidadFinal");

	var datos = new FormData();
	datos.append("nombreProducto", nombreProducto);
	$.ajax({
		url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){
			$(cantidadActual).val(respuesta["stock"]);
			$(cantidadFinal).val(respuesta["stock"]);
		}
	})
 })

/*=============================================
 AUMENTAR O DISMINUIR CANTIDAD
=============================================*/
$(".formulario__AjusteInventario").on("keyup", "input.nuevaCantidadProducto", function () {
	var cantidad_Actual = $(this).parent().parent().parent().children(".ingresarCantidadActual").children().children(".cantidadActual");
	var cantidad_Final = $(this).parent().parent().parent().children(".ingresarCantidadFinal").children().children(".cantidadFinal");

	if ($(".tipoAjuste").val() == "incremento") {
		var cantidad_Suma = Number($(this).val()) + Number(cantidad_Actual.val());
		cantidad_Final.val(cantidad_Suma);
	} else if ($(".tipoAjuste").val() == "decremento") {
		var cantidad_Resta = Number(cantidad_Actual.val()) - Number($(this).val());
		cantidad_Final.val(cantidad_Resta);
		if (cantidad_Resta <= 0){
			$(this).val(0);
			cantidad_Final.val(cantidad_Actual.val());
			swal({
				title: "La cantidad supera al Stock Disponible",
				text: "¡Para poder realizar el ajuste de inventario!",
				type: "error",
				confirmButtonText: "Cerrar"
			});
		}
	}else{
		$(this).val(0);
		swal({
			title: "Debe seleccionar una opción",
			text: "¡Para poder realizar el ajuste de inventario!",
			type: "error",
			confirmButtonText: "Cerrar"
		});
	}
})