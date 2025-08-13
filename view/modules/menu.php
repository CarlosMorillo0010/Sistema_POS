<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="inicio">
                    <i class="fas fa-home"></i>
                    <span>INICIO</span>
                </a>
            </li>
            <!--    MANTENIMIENTO   -->
            <li class="treeview">
                <?php
                foreach ($perfil as $key => $value):
                    if ($_SESSION["perfil"] == $value["perfil"] && $value["mantenimiento"] == "S") {
                        echo '
                    <a href="#">
                        <i class="fas fa-refresh"></i>
                        <span>MANTENIMIENTO</span>
                        <span class="pull-right-container">
                            <i class="fas fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="formas-pago">
                                <i class="far fa-circle"></i>
                                <span>Formas de pago</span>
                            </a>
                        </li>
                        <li>
                            <a href="impuestos">
                                <i class="far fa-circle"></i>
                                <span>Impuestos</span>
                            </a>
                        </li>
                        <!-- <li>
                            <a href="almacenes">
                                <i class="far fa-circle"></i>
                                <span>Almacenes</span>
                            </a>
                        </li>
                        <li>
                            <a href="sucursales">
                                <i class="far fa-circle"></i>
                                <span>Sucursales</span>
                            </a>
                        </li>
                        <li>
                            <a href="bancos">
                                <i class="far fa-circle"></i>
                                <span>Bancos</span>
                            </a>
                        </li>
                        <li>
                            <a href="unidades-medida">
                                <i class="far fa-circle"></i>
                                <span>Unidades de medida</span>
                            </a>
                        </li> -->
                        <li>
                            <a href="marcas">
                                <i class="far fa-circle"></i>
                                <span>Marcas</span>
                            </a>
                        </li>
                        <li>
                            <a href="modelos">
                                <i class="far fa-circle"></i>
                                <span>Modelos</span>
                            </a>
                        </li>
                    </ul>
                ';
                    }
                endforeach;
                ?>
            </li>
            <!--    INVENTARIO   -->
            <li>
                <a href="inventario">
                    <i class="fas fa-box"></i>
                    <span>INVENTARIO</span>
                </a>
            </li>
            <li class="treeview">
                <?php
                foreach ($perfil as $key => $value):
                    if ($_SESSION["perfil"] == $value["perfil"] && $value["inventario"] == "S") {
                        echo '
                        <a href="#">
                            <i class="fas fa-boxes"></i>
                            <span>ALMACÉN</span>
                            <span class="pull-right-container">
                                <i class="fas fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="categorias">
                                    <i class="far fa-circle"></i>
                                    <span>Categorias</span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="servicios">
                                    <i class="far fa-circle"></i>
                                    <span>Servicios</span>
                                </a>
                            </li> -->
                            <!-- <li>
                                <a href="mermas">
                                    <i class="far fa-circle"></i>
                                    <span>Merma</span>
                                </a>
                            </li> -->
                            <!-- <li>
                                <a href="producto-compuesto">
                                    <i class="far fa-circle"></i>
                                    <span>Productos Compuestos</span>
                                </a>
                            </li> -->
                            <li>
                                <a href="vehiculos">
                                    <i class="far fa-circle"></i>
                                    <span>Vehiculos</span>
                                </a>
                            </li>
                            <li>
                                <a href="productos">
                                    <i class="far fa-circle"></i>
                                    <span>Productos</span>
                                </a>
                            </li>
                             <!-- <li>
                                <a href="ajuste-inventario">
                                    <i class="far fa-circle"></i>
                                    <span>Ajuste Inventario</span>
                                </a>
                            </li> 
                            <li>
                                <a href="traslado-almacen">
                                    <i class="far fa-circle"></i>
                                    <span>Traslado Almacen</span>
                                </a>
                            </li> -->
                        </ul>
                    ';
                    }
                endforeach;
                ?>
            </li>
            <!--    COMPRAS   -->
            <li class="treeview">
                <?php
                foreach ($perfil as $key => $value):
                    if ($_SESSION["perfil"] == $value["perfil"] && $value["compras"] == "S") {
                        echo '
                <a href="#">
                    <i class="fas fa-cart-arrow-down"></i>
                    <span>COMPRAS</span>
                    <span class="pull-right-container">
                        <i class="fas fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="proveedores">
                            <i class="far fa-circle"></i>
                            <span>Proveedores</span>
                        </a>
                    </li>
                    <li>
                        <a href="cuentas-pagar">
                            <i class="far fa-circle"></i>
                            <span>Cuentas por Pagar</span>
                        </a>
                    </li>
                    <li>
                        <a href="libro-compras">
                            <i class="far fa-circle"></i>
                            <span>Libro de compras</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="notas-entrega">
                            <i class="far fa-circle"></i>
                            <span>Notas de Entrega</span>
                        </a>
                    </li>
                    <li>
                        <a href="orden-compra">
                            <i class="far fa-circle"></i>
                            <span>Orden de compra</span>
                        </a>
                    </li>
                    <li>
                        <a href="orden-pago">
                            <i class="far fa-circle"></i>
                            <span>Orden de Pago</span>
                        </a>
                    </li>
                    <li>
                        <a href="facturas-compra">
                            <i class="far fa-circle"></i>
                            <span>Facturas de Compra</span>
                        </a>
                    </li>
                    <li>
                        <a href="facturas-gastos">
                            <i class="far fa-circle"></i>
                            <span>Facturas de Gastos</span>
                        </a>
                    </li>
                    <li>
                        <a href="anticipos">
                            <i class="far fa-circle"></i>
                            <span>Anticipos</span>
                        </a>
                    </li>
                    <li>
                        <a href="devolucion-compra">
                            <i class="far fa-circle"></i>
                            <span>Devolución de Compra</span>
                        </a>
                    </li> -->
                </ul>
                ';
                    }
                endforeach;
                ?>
            </li>
            <!--    VENTAS   -->
            <li class="treeview">
                <?php
                foreach ($perfil as $key => $value):
                    if ($_SESSION["perfil"] == $value["perfil"] && $value["ventas"] == "S") {
                        echo '
                <a href="#">
                    <i class="fas fa-shopping-cart"></i>
                    <span >VENTAS</span>
                    <span class="pull-right-container">
                        <i class="fas fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="clientes">
                            <i class="far fa-circle"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="pedidos">
                            <i class="far fa-circle"></i>
                            <span>Pedidos</span>
                        </a>
                    </li>
                    <li>
                        <a href="facturas">
                            <i class="far fa-circle"></i>
                            <span>Facturas</span>
                        </a>
                    </li>
                    <li>
                        <a href="ingresos">
                            <i class="far fa-circle"></i>
                            <span>Ingresos</span>
                        </a>
                    </li>
                    <li>
                        <a href="notas-entrega-venta">
                            <i class="far fa-circle"></i>
                            <span>Notas de Entrega</span>
                        </a>
                    </li>
                    <li>
                        <a href="cuentas-cobrar">
                            <i class="far fa-circle"></i>
                            <span>Cuentas por Cobrar</span>
                        </a>
                    </li>
                    <li>
                        <a href="nota-credito">
                            <i class="far fa-circle"></i>
                            <span>Notas de Crédito</span>
                        </a>
                    </li>
                    <li>
                        <a href="presupuesto">
                            <i class="far fa-circle"></i>
                            <span>Presupuesto</span>
                        </a>
                    </li> -->
                    <li>
                        <a href="ventas">
                            <i class="far fa-circle"></i>
                            <span>Ventas</span>
                        </a>
                    </li>
                    <li>
                        <a href="libro-ventas">
                            <i class="far fa-circle"></i>
                            <span>Libro de ventas</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="caja">
                            <i class="far fa-circle"></i>
                            <span>Crear Venta</span>
                        </a>
                    </li> -->
                </ul>
                ';
                    }
                endforeach;
                ?>
            </li>
                        <!--    REPORTES DE COMPRAS   -->
            <li class="treeview">
                <?php
                foreach ($perfil as $key => $value):
                    if ($_SESSION["perfil"] == $value["perfil"] && $value["ventas"] == "S") {
                        echo '<!--
                <a href="#">
                    <i class="fas fa-bar-chart"></i>
                    <span>REPORTE DE COMPRA</span>
                    <span class="pull-right-container">
                            <i class="fas fa-angle-left pull-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="reportes-ventas">
                            <i class="far fa-circle"></i>
                            <span>Reporte General Compra</span>
                        </a>
                    </li>
                </ul>
                -->';
                    }
                endforeach;
                ?>
            </li>
            <!--    REPORTES DE VENTAS   -->
            <li>
                <?php
                foreach ($perfil as $key => $value):
                    if ($_SESSION["perfil"] == $value["perfil"] && $value["reporte_venta"] == "S") {
                        echo '
                        <a href="reportes-ventas">
                            <i class="fa fa-chart-pie"></i>
                            <span>REPORTES DE VENTAS</span>
                        </a>
                        ';
                    }
                endforeach;
                ?>
            </li>
            <!--    CONFIGURACIONES GENERALES   -->
            <li>
                <?php
                foreach ($perfil as $key => $value):
                    if ($_SESSION["perfil"] == $value["perfil"] && $value["configuracion"] == "S") {
                        echo '
                <a href="configuracion">
                    <i class="fas fa-cogs"></i>
                    <span>CONFIGURACIÓN</span>
                </a>
                ';
                    }
                endforeach;
                ?>
            </li>
        </ul>
    </section>
</aside>