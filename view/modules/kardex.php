<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Kardex de Inventario Valorizado
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Kardex</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros de Búsqueda</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <!-- ENTRADA PARA CÓDIGO DE PRODUCTO -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Código de Producto:</label>
                            <input type="text" class="form-control" id="codigoProductoKardex" placeholder="Ingrese el código">
                            <input type="hidden" id="idProductoKardex">
                        </div>
                    </div>

                    <!-- ENTRADA PARA NOMBRE DE PRODUCTO (SOLO LECTURA) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Producto:</label>
                            <input type="text" class="form-control" id="nombreProductoKardex" readonly>
                        </div>
                    </div>

                    <!-- ENTRADA PARA RANGO DE FECHAS -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Rango de Fechas:</label>
                            <button type="button" class="btn btn-default form-control" id="daterange-btn-kardex">
                                <span>
                                <i class="fa fa-calendar"></i> Rango de fecha
                                </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>

                    <!-- BOTÓN DE BÚSQUEDA -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" id="btnBuscarKardex" class="btn btn-primary btn-block">Buscar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Reporte de Kardex</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive tablaKardex" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">#</th>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">Fecha</th>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">Documento</th>
                                <th rowspan="2" style="vertical-align: middle; text-align: center;">Concepto</th>
                                <th colspan="3" style="text-align: center;">Entradas</th>
                                <th colspan="3" style="text-align: center;">Salidas</th>
                                <th colspan="3" style="text-align: center;">Saldos</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Cant.</th>
                                <th style="text-align: center;">Costo U.</th>
                                <th style="text-align: center;">Costo T.</th>
                                <th style="text-align: center;">Cant.</th>
                                <th style="text-align: center;">Costo U.</th>
                                <th style="text-align: center;">Costo T.</th>
                                <th style="text-align: center;">Cant.</th>
                                <th style="text-align: center;">Costo U.</th>
                                <th style="text-align: center;">Costo T.</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
$(function () {
    // Rango de fechas
    var fechaInicial = moment().subtract(29, 'days');
    var fechaFinal = moment();

    $('#daterange-btn-kardex span').html(fechaInicial.format('MMMM D, YYYY') + ' - ' + fechaFinal.format('MMMM D, YYYY'));

    $('#daterange-btn-kardex').daterangepicker(
        {
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: fechaInicial,
            endDate: fechaFinal
        },
        function (start, end) {
            $('#daterange-btn-kardex span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            fechaInicial = start; // Guardamos el objeto moment completo
            fechaFinal = end;     // Guardamos el objeto moment completo
        }
    );
    
    // Inicializamos la tabla vacía por defecto.
    $('.tablaKardex').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });


    // BUSCAR PRODUCTO POR CÓDIGO
    $("#codigoProductoKardex").change(function(){
        var codigoProducto = $(this).val();

        if(codigoProducto != ""){
            var datos = new FormData();
            datos.append("validarCodigo", codigoProducto);

            $.ajax({
                url: "ajax/productos.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(respuesta){
                    if(respuesta){
                        $("#idProductoKardex").val(respuesta["id_producto"]);
                        $("#nombreProductoKardex").val(respuesta["descripcion"]);
                    } else {
                        $("#idProductoKardex").val("");
                        $("#nombreProductoKardex").val("");
                        swal({
                            title: "Producto no encontrado",
                            text: "El código de producto ingresado no existe.",
                            type: "error",
                            confirmButtonText: "¡Cerrar!"
                        });
                    }
                }
            });
        } else {
            $("#idProductoKardex").val("");
            $("#nombreProductoKardex").val("");
        }
    });

    // CARGAR DATOS DEL KARDEX AL HACER CLIC EN BUSCAR
    $("#btnBuscarKardex").click(function () {
        cargarDatosKardex();
    });

    function cargarDatosKardex() {
        var idProducto = $("#idProductoKardex").val();

        if (idProducto) {
            // Formateamos las fechas antes de enviarlas
            var fechaIni = fechaInicial.format('YYYY-MM-DD');
            var fechaFin = fechaFinal.format('YYYY-MM-DD');

            $('.tablaKardex').DataTable().destroy();
            $('.tablaKardex').DataTable({
                "ajax": {
                    "url": "ajax/datatable-kardex.ajax.php",
                    "type": "POST",
                    "data": {
                        "id_producto": idProducto,
                        "fechaInicial": fechaIni,
                        "fechaFinal": fechaFin
                    }
                },
                "deferRender": true,
                "retrieve": true,
                "processing": true,
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        } else {
            swal({
                title: "Seleccione un producto",
                text: "Por favor, ingrese un código de producto válido para realizar la búsqueda.",
                type: "warning",
                confirmButtonText: "¡Cerrar!"
            });
        }
    }
});
</script>