<?php
$item = null;
$valor = null;

$ventas = ControllerVentas::ctrMostrarVenta($item, $valor);
$usuarios = ControllerUsers::ctrMostrarUsuario($item, $valor);

$arrayVendedores = array();
$arrayListaVendedores = array();
foreach ($ventas as $key => $valueVentas):
    foreach ($usuarios as $key => $valueUsuarios){
        if ($valueUsuarios["id_usuario"] == $valueVentas["id_vendedor"]){
            /*=====================================
             CAPTURAMOS VENDEDORES EN UN ARRAY
            ======================================*/
            array_push($arrayVendedores, $valueUsuarios["nombre"]);
            /*=====================================
             CAPTURAMOS NOMBRE Y VALORES NETO EN UN ARRAY
            ======================================*/
            $arrayListaVendedores = array($valueUsuarios["nombre"] => $valueVentas["subtotal_bs"]);
        }
        /*=====================================
         SUMAR LOS NETO DE CADA VENDEDOR
        ======================================*/
        foreach ($arrayListaVendedores as $key => $value) {
            $sumaTotalVendedores[$key] += $value;
        }
    }
endforeach;
    /*=====================================
     EVITAMOS REPETIR NOMBRES
    ======================================*/
    $noRepetirNombres = array_unique($arrayVendedores);
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">
            Vendedores
        </h3>
    </div>
    <div class="box-body">
        <div class="chart-responsive">
            <div class="chart" id="bar-chart1" style="height: 260px;">

            </div>
        </div>
    </div>
</div>

<script>
    var bar = new Morris.Bar({
        element: 'bar-chart1',
        resize: true,
        data: [
            <?php
                foreach ($noRepetirNombres as $value){
                    echo "
                        {y: '".$value."', a: '".$sumaTotalVendedores[$value]."'},
                    ";
                }
            ?>
        ],
        barColors: ['#0af'],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['ventas'],
        preUnits: '$',
        hideHover: 'auto'
    });
</script>