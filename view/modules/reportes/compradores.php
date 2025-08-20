<?php
$item = null;
$valor = null;

$ventas = ControllerVentas::ctrMostrarVenta($item, $valor);
$clientes = ControllerClients::ctrMostrarClientes($item, $valor);

$arrayClientes = array();
$arrayListaClientes = array();
foreach ($ventas as $key => $valueVentas):
    foreach ($clientes as $key => $valueClientes){
        if ($valueClientes["id"] == $valueVentas["id_cliente"]){
            /*=====================================
             CAPTURAMOS VENDEDORES EN UN ARRAY
            ======================================*/
            array_push($arrayClientes, $valueClientes["nombre"]);
            /*=====================================
             CAPTURAMOS NOMBRE Y VALORES NETO EN UN ARRAY
            ======================================*/
            $arrayListaClientes = array($valueClientes["nombre"] => $valueVentas["subtotal_bs"]);
        }
        /*=====================================
         SUMAR LOS NETO DE CADA CLIENTE
        ======================================*/
        foreach ($arrayListaClientes as $key => $value) {
            $sumaTotalClientes[$key] += $value;
        }
    }
endforeach;
/*=====================================
 EVITAMOS REPETIR NOMBRES
======================================*/
$noRepetirNombres = array_unique($arrayClientes);
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">
            Clientes
        </h3>
    </div>
    <div class="box-body">
        <div class="chart-responsive">
            <div class="chart" id="bar-chart2" style="height: 260px;">

            </div>
        </div>
    </div>
</div>

<script>
    var bar = new Morris.Bar({
        element: 'bar-chart2',
        resize: true,
        data: [
            <?php
            foreach ($noRepetirNombres as $value){
                echo "
                        {y: '".$value."', a: '".$sumaTotalClientes[$value]."'},
                    ";
            }
            ?>
        ],
        barColors: ['#f6a'],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['ventas'],
        preUnits: '$',
        hideHover: 'auto'
    });
</script>