<?php

require_once "../../controller/ventas-controller.php";
require_once "../../model/ventas-model.php";
require_once "../../controller/clients.controller.php";
require_once "../../model/clients.model.php";
require_once "../../controller/users.controller.php";
require_once "../../model/users.model.php";

$reporte = new ControllerVentas();
$reporte->ctrDescargarReporte();