<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["reporte_venta"] == "N"){
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
            Administrar reportes de ventas
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar reportes de ventas</li>
        </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <div class="input-group">
                    <button type="button" class="btn btn-default" id="daterange-btn-reportes">
                    <span>
                        <i class="fa fa-calendar"></i> Rango de Fecha
                    </span>
                    </button>
                </div>
                <div class="box-tools pull-right">
                    <?php
                    if (isset($_GET["fechaInicial"])) {
                        echo '<a href="view/modules/descargar-reporte.php?reporte=reporte&fechaInicial=' . $_GET["fechaInicial"] . '&fechaFinal=' . $_GET["fechaFinal"] . '">';
                    } else {
                        echo '<a href="view/modules/descargar-reporte.php?reporte=reporte">';
                    }
                    ?>
                    <button class="btn btn-success" style="margin-top: 5px;">Reporte en Excel</button>
                    </a>

                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                        include "reportes/grafico-ventas.php";
                        ?>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <?php
                        include "reportes/productos-mas-vendidos.php";
                        ?>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <?php
                        include "reportes/vendedores.php";
                        ?>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <?php
                        include "reportes/compradores.php";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>