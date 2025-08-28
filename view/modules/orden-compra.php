<?php
// Código de seguridad y permisos...
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Administrar Órdenes de Compra
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar Órdenes</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <a href="crear-orden-compra">
                    <button class="btn btn-primary">
                        Nueva Orden de Compra
                    </button>
                </a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tabla-ordenes-compra" width="100%">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Código</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $ordenes = ControllerOrdenesCompras::ctrMostrarOrdenCompra(null, null);

                    foreach ($ordenes as $key => $orden) {
                        $proveedor = ControllerProveedores::ctrMostrarProveedores("id_proveedor", $orden["id_proveedor"]);
                        echo '<tr>';
                        echo '<td>'.($key + 1).'</td>';
                        echo '<td>'.str_pad($orden["codigo"], 8, '0', STR_PAD_LEFT).'</td>';
                        echo '<td>'.($proveedor ? $proveedor["nombre"] : "N/A").'</td>';
                        echo '<td>'.date("d/m/Y", strtotime($orden["fecha"])).'</td>';
                        echo '<td>'.number_format($orden["total"], 2).'</td>';
                        echo '<td>'.getStatusButton($orden["estado"]).'</td>';
                        echo '<td>'.getActionButtons($orden).'</td>';
                        echo '</tr>';
                    }

                    function getStatusButton($status) {
                        switch ($status) {
                            case 'Borrador':
                                return '<button class="btn btn-default">Borrador</button>';
                            case 'Enviada':
                                return '<button class="btn btn-primary">Enviada</button>';
                            case 'Completada':
                                return '<button class="btn btn-success">Completada</button>';
                            case 'Cancelada':
                                return '<button class="btn btn-danger">Cancelada</button>';
                            default:
                                return '<button class="btn btn-default">'.$status.'</button>';
                        }
                    }

                    function getActionButtons($orden) {
                        $buttons = '<div class="btn-group">';
                        $idOrden = $orden["id_orden_compra"];

                        // Botón de Ver siempre disponible
                        $buttons .= '<button class="btn btn-info btnVerOrdenCompra" idOrden="'.$idOrden.'" data-toggle="modal" data-target="#modalVerOrdenCompra"><i class="fa fa-eye"></i></button>';

                        switch ($orden["estado"]) {
                            case 'Borrador':
                                $buttons .= '<a href="index.php?ruta=crear-orden-compra&idOrden='.$idOrden.'" class="btn btn-warning"><i class="fa fa-pencil"></i></a>';
                                $buttons .= '<button class="btn btn-success btnEnviarOrden" idOrden="'.$idOrden.'"><i class="fa fa-send"></i></button>';
                                $buttons .= '<button class="btn btn-danger btnEliminarOrdenCompra" idOrden="'.$idOrden.'"><i class="fa fa-times"></i></button>';
                                break;
                            case 'Enviada':
                                $buttons .= '<button class="btn btn-primary btnImprimirOrden" idOrden="'.$idOrden.'"><i class="fa fa-print"></i></button>';
                                $buttons .= '<button class="btn btn-danger btnCancelarOrden" idOrden="'.$idOrden.'"><i class="fa fa-ban"></i></button>';
                                break;
                            case 'Completada':
                            case 'Cancelada':
                                $buttons .= '<button class="btn btn-primary btnImprimirOrden" idOrden="'.$idOrden.'"><i class="fa fa-print"></i></button>';
                                break;
                        }

                        $buttons .= '</div>';
                        return $buttons;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- MODAL PARA VER DETALLE DE ORDEN DE COMPRA -->
<div id="modalVerOrdenCompra" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detalles de la Orden de Compra</h4>
            </div>
            <div class="modal-body" id="detalleOrdenBody">
                <!-- Contenido se carga con AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<?php
// Lógica para eliminar, cancelar, etc.
$borrarOrdenCompra = new ControllerOrdenesCompras();
$borrarOrdenCompra->ctrBorrarOrdenCompra();
?>

<script>
$(document).ready(function() {
    $('.tabla-ordenes-compra').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        }
    });

    // Evento para Enviar Orden
    $(".tabla-ordenes-compra").on("click", ".btnEnviarOrden", function(){
        var idOrden = $(this).attr("idOrden");
        cambiarEstadoOrden(idOrden, "Enviada");
    });

    // Evento para Cancelar Orden
    $(".tabla-ordenes-compra").on("click", ".btnCancelarOrden", function(){
        var idOrden = $(this).attr("idOrden");
        cambiarEstadoOrden(idOrden, "Cancelada");
    });

    // Evento para ver el detalle de la orden
    $(".tabla-ordenes-compra").on("click", ".btnVerOrdenCompra", function(){
        var idOrden = $(this).attr("idOrden");
        var datos = new FormData();
        datos.append("idOrdenVer", idOrden);

        $.ajax({
            url: "ajax/ordenes-compras.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "html",
            success: function(respuesta){
                $("#detalleOrdenBody").html(respuesta);
            }
        });
    });

    // Evento para Imprimir Orden
    $(".tabla-ordenes-compra").on("click", ".btnImprimirOrden", function(){
        var idOrden = $(this).attr("idOrden");
        window.open("view/modules/pdf-orden-compra.php?idOrden=" + idOrden, "_blank");
    });

    function cambiarEstadoOrden(idOrden, nuevoEstado) {
        Swal.fire({
            title: '¿Estás seguro de cambiar el estado de la orden?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar estado!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append("idOrdenEstado", idOrden);
                datos.append("nuevoEstado", nuevoEstado);

                $.ajax({
                    url: "ajax/ordenes-compras.ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(respuesta){
                        if(respuesta == "ok"){
                            Swal.fire('¡Hecho!', 'El estado de la orden ha sido actualizado.', 'success').then(function(){
                                window.location.reload();
                            });
                        }
                    }
                });
            }
        });
    }
});
</script>
