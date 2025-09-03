<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["compras"] == "N"){
        echo '
            <script>
            window.location = "inicio";
            </script>
        ';
        return;
    }
endforeach;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Administrar Devoluciones en Compras
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar Devoluciones en Compras</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <a href="crear-devolucion-compra">
                    <button class="btn btn-primary">
                        Crear Devolución en Compra
                    </button>
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablaDevolucionesCompras" width="100%">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Cód. Compra</th>
                        <th>Proveedor</th>
                        <th>Monto Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('.tablaDevolucionesCompras').DataTable({
        "ajax": "ajax/datatable-devoluciones-compras.ajax.php",
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
});
</script>
