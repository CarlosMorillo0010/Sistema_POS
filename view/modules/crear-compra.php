<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Administrar Compras
      <small>Historial de compras registradas</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar Compras</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Historial de Compras</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive tablaCompras" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>N° Compra</th>
              <th>Proveedor</th>
              <th>Fecha</th>
              <th>Monto Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $compras = ControllerCompras::ctrMostrarCompras(null, null);

              if (!empty($compras)) {
                foreach ($compras as $key => $value) {
                  echo '<tr>
                          <td>'.($key+1).'</td>
                          <td>'.$value["id_compra"].'</td>';

                  $proveedor = ControllerProveedores::ctrMostrarProveedores("id_proveedor", $value["id_proveedor"]);

                  echo '<td>'.$proveedor["nombre"].'</td>
                        <td>'.date('d/m/Y', strtotime($value["fecha_compra"])).'</td>
                        <td>'.number_format($value["monto_total"], 2).'</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-info btnImprimirCompra" idCompra="'.$value["id_compra"].'" style="background-color: #00c0ef !important;"><i class="fa fa-print"></i></button>
                          </div>
                        </td>
                      </tr>';
                }
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<script>
$(document).ready(function() {
    $('.tablaCompras').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        }
    });

    $('.tablaCompras').on('click', '.btnImprimirCompra', function() {
        var idCompra = $(this).attr('idCompra');
        window.open('pdf/reporte-compra.php?idCompra=' + idCompra, '_blank');
    });
});
</script>
