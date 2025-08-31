<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Recepción de Mercancía
      <small>Entrada a Almacén desde una Orden de Compra</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Recepción de Mercancía</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Órdenes de Compra Pendientes</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive tablaRecepcionMercancia" width="100%">
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
              $item = "estado";
              $valor = "Enviada";
              $ordenesCompra = ControllerOrdenesCompras::ctrMostrarMultiplesOrdenes($item, $valor);

              if (!empty($ordenesCompra)) {
                foreach ($ordenesCompra as $key => $value) {
                echo '<tr>
                        <td>'.($key+1).'</td>
                        <td>'.$value["codigo"].'</td>';

                $itemProveedor = "id_proveedor";
                $valorProveedor = $value["id_proveedor"];
                $proveedor = ControllerProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);

                echo '<td>'.$proveedor["nombre"].'</td>
                      <td>'.date('d/m/Y', strtotime($value["fecha"])).'</td>
                      <td>'.number_format($value["total"], 2).'</td>
                      <td><button class="btn btn-warning btn-xs">'.$value["estado"].'</button></td>
                      <td>
                        <div class="btn-group">
                          <button class="btn btn-primary btnRecibirOrden" idOrden="'.$value["id_orden_compra"].'"><i class="fa fa-check-square-o"></i> Recibir</button>
                        </div>
                      </td>
                    </tr>';
              }
            } // Cierre del if (!empty($ordenesCompra))
            ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--===========================================
MODAL PARA RECEPCIÓN DE MERCANCÍA
============================================-->
<div id="modalRecepcionMercancia" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form role="form" method="post" id="formRecepcion">
        <!--===========================================
        CABEZA DEL MODAL
        ============================================-->
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Recibir Orden de Compra: <span id="codigoOrden"></span></h4>
        </div>

        <!--===========================================
        CUERPO DEL MODAL
        ============================================-->
        <div class="modal-body">
          <div class="box-body">
            
            <input type="hidden" name="idOrdenCompra" id="idOrdenCompra">

            <!-- Tabla de productos -->
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Cantidad Pedida</th>
                  <th>Cantidad Recibida</th>
                </tr>
              </thead>
              <tbody id="tablaProductosRecepcion">
                <!-- Los productos se cargarán aquí dinámicamente -->
              </tbody>
            </table>

          </div>
        </div>

        <!--===========================================
        PIE DEL MODAL
        ============================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Recepción</button>
        </div>

      </form>
    </div>
  </div>
</div>
