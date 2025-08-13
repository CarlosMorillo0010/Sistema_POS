<?php
$item = null;
$valor = null;
$orden = "codigo";
$usuarios = ControllerUsers::ctrMostrarUsuario($item, $valor);
$totalUsuarios = count($usuarios);
$clientes = ControllerClients::ctrMostrarClientes($item, $valor);
$totalClientes = count($clientes);
$proveedores = ControllerProveedores::ctrMostrarProveedores($item, $valor);
$totalProveedores = count($proveedores);
$productos = ControllerProducts::ctrMostrarProductos($item, $valor, $orden);
$totalProductos = count($productos);
$tasaUSD = ControllerDivisas::ctrObtenerTasaActual("USD");
$categoria = ControllerCategories::ctrMostrarCategoria($item, $valor);
$totalCategorias = count($categoria);
$ventas = ControllerVentas::ctrMostrarVenta($item, $valor);
?>
<!-- USUARIOS -->
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-primary">
        <div class="inner">
            <h3>
                <?php
                echo number_format($totalUsuarios);
                ?>
            </h3>

            <p>Usuarios</p>
        </div>
        <div class="icon">
            <i class="ion ion-person-add"></i>
        </div>
        <a href="config-usuarios" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- CLIENTES -->
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red">
        <div class="inner">
            <h3>
                <?php
                echo number_format($totalClientes);
                ?>
            </h3>

            <p>Clientes</p>
        </div>
        <div class="icon">
            <i class="ion-person"></i>
        </div>
        <a href="clientes" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- PROVEEDORES -->
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
        <div class="inner">
            <h3>
                <?php
                echo number_format($totalProveedores);
                ?>
            </h3>

            <p>Proveedores</p>
        </div>
        <div class="icon">
            <i class="ion-person-stalker"></i>
        </div>
        <a href="proveedores" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- PRODUCTOS -->
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
        <div class="inner">
            <h3>
                <?php
                echo number_format($totalProductos);
                ?>
            </h3>

            <p>Productos</p>
        </div>
        <div class="icon">
            <i class="ion ion-bag"></i>
        </div>
        <a href="productos" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- DIVISA -->
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-teal">
        <div class="inner">

            <h3>
                <?php
                if ($tasaUSD) {

                    echo number_format($tasaUSD, 2, ',', '.');
                } else {
                    echo "0,00";
                }
                ?>Bs.S
            </h3>

            <p>Valor divisa</p>
        </div>
        <div class="icon">
            <i class="ion ion-social-usd"></i>
        </div>
        <a href="config-divisas" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- CATEGORIA -->
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-maroon">
        <div class="inner">
            <h3>
                <?php
                echo number_format($totalCategorias);
                ?>
            </h3>

            <p>Categorias</p>
        </div>
        <div class="icon">
            <i class="ion ion-clipboard"></i>
        </div>
        <a href="categorias" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-purple">
        <div class="inner">
            <h3>
                <?php
                echo count($ventas);
                ?>
            </h3>

            <p>Total ventas</p>
        </div>
        <div class="icon">
            <i class="fa fa-credit-card"></i>
        </div>
        <a href="ventas" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>