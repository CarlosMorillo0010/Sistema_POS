/*=====================================
    TABLA DINAMICA DE ARTICULOS
======================================**/

// $.ajax({
//     url: "ajax/datatable-articulos.ajax.php",
//     success: function(respuesta){
//         console.log("respuesta", respuesta);
//     }
// })

$('.tablaArticulos').DataTable( {
    "ajax": "ajax/datatable-articulos.ajax.php",
        "deferRender": true,
        "retrieve": true,
        "processing": true,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    }
);

/*=====================================
    PRECIO DE VENTA
======================================**/
$("#nuevoPrecioCompra, #editarPrecioCompra").change(function(){
	if($(".porcentaje").prop("checked")){
		var valorPorcentaje = $(".nuevoPorcentaje").val();		
		var porcentaje = Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());
		var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

		$("#nuevoPrecioVenta").val(porcentaje);
		$("#nuevoPrecioVenta").prop("readonly",true);

		$("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true);
	}
})

/*=====================================
    CAMBIO DE PORCENTAJE
======================================**/
$(".nuevoPorcentaje").change(function(){
    if($(".porcentaje").prop("checked")){
		var valorPorcentaje = $(this).val();
		var porcentaje = Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());
		var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

		$("#nuevoPrecioVenta").val(porcentaje);
		$("#nuevoPrecioVenta").prop("readonly",true);

		$("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true);
	}
})

$(".porcentaje").on("ifUnchecked",function(){
	$("#nuevoPrecioVenta").prop("readonly",false);
	$("#editarPrecioVenta").prop("readonly",false);
})

$(".porcentaje").on("ifChecked",function(){
	$("#nuevoPrecioVenta").prop("readonly",true);
	$("#editarPrecioVenta").prop("readonly",true);
})

/*=============================================
SUBIENDO LA FOTO DEL ARTICULO
=============================================*/

$(".imagenArticulo").change(function(){
    var imagen = this.files[0];

    /*=============================================
      VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
      =============================================*/
    if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
        $(".imagenArticulo").val("");
        swal({
            title: "Error al subir la imagen",
            text: "¡La imagen debe estar en formato JPG o PNG!",
            type: "error",
            confirmButtonText: "¡Cerrar!"
        });
    }else if(imagen["size"] > 2000000){
        $(".imagenArticulo").val("");
        swal({
            title: "Error al subir la imagen",
            text: "¡La imagen no debe pesar más de 2MB!",
            type: "error",
            confirmButtonText: "¡Cerrar!"
        });
    }else{
        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);
        $(datosImagen).on("load", function(event){
            var rutaImagen = event.target.result;
            $(".previsualizar").attr("src", rutaImagen);
        })
    }
})

/*=============================================
     EDITAR ARTICULO
=============================================*/
$(".tablaArticulos tbody").on("click", "button.btnEditarArticulo", function(){
    var idArticulo = $(this).attr("idArticulo");
    var datos = new FormData();
    datos.append("idArticulo", idArticulo);
    $.ajax({
        url:"ajax/articulos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){
            var datosMarca = new FormData();
            datosMarca.append("idMarca",respuesta["id"]);
            $.ajax({
                url:"ajax/marcas.ajax.php",
                method: "POST",
                data: datosMarca,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){
                    $("#editarMarca").val(respuesta["id_marca"]);
                    $("#editarMarca").html(respuesta["marca"]);
                }
            })
            $("#editarArticulo").val(respuesta["articulo"]);
            $("#editarUnidades").val(respuesta["unidades"]);
            $("#editarPrecioCompra").val(respuesta["precio_compra"]);
            $("#editarPrecioVenta").val(respuesta["precio_venta"]);
            if(respuesta["imagen"] != ""){
                $("#imagenActual").val(respuesta["imagen"]);
                $(".previsualizar").attr("src",  respuesta["imagen"]);
            }
        }
    })
})

/*=============================================
     ELIMINAR ARTICULO
=============================================*/
$(".tablaArticulos tbody").on("click", "button.btnEliminarArticulo", function(){

    var idArticulo = $(this).attr("idArticulo");
    var imagen = $(this).attr("imagen");

    swal({

        title: '¿Está seguro de borrar el articulo?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar articulo!'
    }).then(function(result) {
        if (result.value) {
            window.location = "index.php?ruta=articulos&idArticulo="+idArticulo+"&imagen="+imagen;
        }
    })
})