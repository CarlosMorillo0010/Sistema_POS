<?php

/**=====================================
    CONTROLLERS
======================================**/
require_once "controller/template.controller.php";
require_once "controller/users.controller.php";
require_once "controller/categories.controller.php";
require_once "controller/products.controller.php";
require_once "controller/compuests.controller.php";
require_once "controller/clients.controller.php";
require_once "controller/sales.controller.php";
require_once "controller/perfiles.controller.php";
require_once "controller/impuestos.controller.php";
require_once "controller/unidades.controller.php";
require_once "controller/divisas.controller.php";
require_once "controller/libro-compras.controller.php";
require_once "controller/bancos.controller.php";
require_once "controller/pagos.controller.php";
require_once "controller/unidades.controller.php";
require_once "controller/marcas.controller.php";
require_once "controller/modelos.controller.php";
require_once "controller/sucursales.controller.php";
require_once "controller/almacenes.controller.php";
require_once "controller/proveedores.controller.php";
require_once "controller/cuentas-pagar.controller.php";
require_once "controller/cuentas-cobrar.controller.php";
require_once "controller/services.controller.php";
require_once "controller/pedidos.controller.php";
require_once "controller/anticipos.controller.php";
require_once "controller/configuraciones.controller.php";
require_once "controller/config-divisas.controller.php";
require_once "controller/paises.controller.php";
require_once "controller/nota-entregas.controller.php";
require_once "controller/nota-entrega-ventas.controller.php";
require_once "controller/facturas-compras.controller.php";
require_once "controller/facturas-gastos.controller.php";
require_once "controller/ordenes-compras.controller.php";
require_once "controller/devoluciones-compras.controller.php";
require_once "controller/facturas-ventas.controller.php";
require_once "controller/nota-creditos.controller.php";
require_once "controller/presupuestos.controller.php";
require_once "controller/inventarios.controller.php";
require_once "controller/ventas-controller.php";
require_once "controller/vehiculos.controller.php";
require_once "controller/ajustes.controller.php";
require_once "controller/compras.controller.php";

/**=====================================
    MODELS
======================================**/
require_once "model/users.model.php";
require_once "model/categories.model.php";
require_once "model/products.model.php";
require_once "model/compuests.model.php";
require_once "model/clients.model.php";
require_once "model/sales.model.php";
require_once "model/perfiles.model.php";
require_once "model/impuestos.model.php";
require_once "model/unidades.model.php";
require_once "model/divisas.model.php";
require_once "model/libro-compras.model.php";
require_once "model/bancos.model.php";
require_once "model/pagos.model.php";
require_once "model/unidades.model.php";
require_once "model/marcas.model.php";
require_once "model/modelos.model.php";
require_once "model/sucursales.model.php";
require_once "model/almacenes.model.php";
require_once "model/proveedores.model.php";
require_once "model/cuentas-pagar.model.php";
require_once "model/cuentas-cobrar.model.php";
require_once "model/services.model.php";
require_once "model/pedidos.model.php";
require_once "model/anticipos.model.php";
require_once "model/configuraciones.model.php";
require_once "model/config-divisas.model.php";
require_once "model/paises.model.php";
require_once "model/nota-entregas.model.php";
require_once "model/nota-entrega-ventas.model.php";
require_once "model/facturas-compras.model.php";
require_once "model/facturas-gastos.model.php";
require_once "model/ordenes-compras.model.php";
require_once "model/devoluciones-compras.model.php";
require_once "model/facturas-ventas.model.php";
require_once "model/nota-creditos.model.php";
require_once "model/presupuestos.model.php";
require_once "model/inventarios.model.php";
require_once "model/ventas-model.php";
require_once "model/vehiculos.model.php";
require_once "model/ajustes.model.php";
require_once "model/compras.model.php";

$template = new ControllerTemplate();
$template -> ctrTemplate();