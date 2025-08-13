<?php
session_start();

/**
 * Función para "Cache Busting".
 * Genera una URL para un asset (CSS/JS) con un número de versión
 * basado en la fecha de última modificación del archivo.
 * Esto fuerza al navegador a descargar la nueva versión si el archivo ha cambiado.
 * @param string $path La ruta al archivo desde la raíz del proyecto.
 * @return string La ruta completa con el parámetro de versión.
 */
function versionAsset($path)
{
    if (!file_exists($path)) {
        return $path; // Devuelve la ruta original si el archivo no se encuentra
    }
    $version = filemtime($path);
    return $path . '?v=' . $version;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Sistema | POS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="view/img/template/icono.png">

    <!--=====================================
        PLUGINS CSS CON VERSIONADO AUTOMÁTICO
    ======================================-->
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/bower_components/fontawesome-free/css/all.css'); ?>">
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/font-awesome/css/font-awesome.css'); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/bower_components/Ionicons/css/ionicons.min.css'); ?>">
    <!-- Main Theme style -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/dist/css/template.css'); ?>">
    <!-- Main style -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/dist/css/main.css'); ?>">
    <!-- Caja -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/dist/css/caja.css'); ?>">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/datatables-responsive/css/responsive.bootstrap4.min.css'); ?>">
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/datatables-buttons/css/buttons.bootstrap4.min.css'); ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'); ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/bower_components/iCheck/all.css'); ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/bower_components/select2/dist/css/select2.min.css'); ?>">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/bower_components/morris.js/morris.css'); ?>">
    <!-- Bootstrap Select -->
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/bootstrap/dist/css/bootstrap-select.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/dist/css/AdminLTE.css'); ?>">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="<?php echo versionAsset('view/dist/css/skins/skin-blue.css'); ?>">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="<?php echo versionAsset('view/bower_components/bootstrap-daterangepicker/daterangepicker.css'); ?>">

    <!--=====================================
        PLUGINS JAVASCRIPT CON VERSIONADO AUTOMÁTICO
    ======================================-->
    <!-- jQuery 3 -->
    <script src="<?php echo versionAsset('view/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo versionAsset('view/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo versionAsset('view/bower_components/fastclick/lib/fastclick.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo versionAsset('view/dist/js/adminlte.min.js'); ?>"></script>
    <!-- DataTables -->
    <script src="<?php echo versionAsset('view/bower_components/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-buttons/js/dataTables.buttons.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-buttons/js/buttons.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo versionAsset('view/bower_components/jszip/jszip.min.js'); ?>"></script>
    <script src="<?php echo versionAsset('view/bower_components/pdfmake/pdfmake.min.js'); ?>"></script>
    <script src="<?php echo versionAsset('view/bower_components/pdfmake/vfs_fonts.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-buttons/js/buttons.print.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
    <!-- bootstrap datepicker -->
    <script
        src="<?php echo versionAsset('view/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'); ?>"></script>
    <!-- SWEETALERT 2 -->
    <script src="<?php echo versionAsset('view/plugins/sweetalert2/sweetalert2.all.js'); ?>"></script>
    <!-- iCheck 1.0.1 -->
    <script src="<?php echo versionAsset('view/bower_components/iCheck/icheck.min.js'); ?>"></script>
    <!-- By default SweetAlert2 doesn't support IE. To enable IE 11 support, include Promise polyfill:-->
    <script src="<?php echo versionAsset('view/plugins/core.js'); ?>"></script>
    <!-- InputMask -->
    <script src="<?php echo versionAsset('view/plugins/input-mask/jquery.inputmask.js'); ?>"></script>
    <script src="<?php echo versionAsset('view/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
    <script src="<?php echo versionAsset('view/plugins/input-mask/jquery.inputmask.extensions.js'); ?>"></script>
    <!-- jQueryNumber -->
    <script src="<?php echo versionAsset('view/plugins/jqueryNumber/jquerynumber.min.js'); ?>"></script>
    <!-- daterangepicker http://www.daterangepicker.com/-->
    <script src="<?php echo versionAsset('view/bower_components/moment/min/moment.min.js'); ?>"></script>
    <script
        src="<?php echo versionAsset('view/bower_components/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
    <!-- Morris.js charts http://morrisjs.github.io/morris.js/-->
    <script src="<?php echo versionAsset('view/bower_components/raphael/raphael.min.js'); ?>"></script>
    <script src="<?php echo versionAsset('view/bower_components/morris.js/morris.min.js'); ?>"></script>
    <!-- ChartJS http://www.chartjs.org/-->
    <script src="<?php echo versionAsset('view/bower_components/chart.js/Chart.js'); ?>"></script>
    <!-- Select2 -->
    <script src="<?php echo versionAsset('view/bower_components/select2/dist/js/select2.full.min.js'); ?>"></script>
</head>

<!--=====================================
    BODY DOCUMENT
======================================-->

<body class="hold-transition skin-blue sidebar-mini sidebar-collapse del-sidebar-collapse">

    <?php
    // Solo mostramos este div si el usuario ha iniciado sesión,
    // para no exponer datos si está en la página de login.
    if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
        // PREPARAMOS LAS VARIABLES DE CONFIGURACIÓN
        $tasaBCV = $_SESSION['config']['tasa_bcv'] ?? 0;
        $ivaPorcentaje = $_SESSION['config']['iva_porcentaje'] ?? 16.00;
        $monedaPrincipal = $_SESSION['config']['moneda_principal'] ?? 'USD';
        ?>
        <!-- =============================================
            CONTENEDOR DE VARIABLES DE CONFIGURACIÓN GLOBALES
            Este div oculto pasa las variables de PHP a JavaScript.
            Nuestro script config-updater.js leerá estos datos al cargar la página.
            ============================================== -->
        <div id="config-vars" data-tasa-bcv="<?php echo htmlspecialchars($tasaBCV, ENT_QUOTES, 'UTF-8'); ?>"
            data-iva-porcentaje="<?php echo htmlspecialchars($ivaPorcentaje, ENT_QUOTES, 'UTF-8'); ?>"
            data-moneda-principal="<?php echo htmlspecialchars($monedaPrincipal, ENT_QUOTES, 'UTF-8'); ?>"
            style="display: none;">
        </div>
        <?php
    }
    ?>

    <!-- Site wrapper -->
    <?php
    if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
        echo '<div class="wrapper">';

        /*=====================================
          HEADER TEMPLATE
        ======================================**/
        include "modules/header.php";
        /*=====================================
         MENU TEMPLATE
        ======================================**/
        include "modules/menu.php";
        /*=====================================
         CONTENT TEMPLATE
        ======================================**/
        if (isset($_GET["ruta"])) {
            if (
                $_GET["ruta"] == "inicio" ||
                $_GET["ruta"] == "usuarios" ||
                $_GET["ruta"] == "perfil" ||
                $_GET["ruta"] == "crear-perfil" ||
                // $_GET["ruta"] == "anticipos" ||
                $_GET["ruta"] == "divisas" ||
                $_GET["ruta"] == "bancos" ||
                // $_GET["ruta"] == "almacenes" ||
                $_GET["ruta"] == "inventario" ||
                $_GET["ruta"] == "formas-pago" ||
                $_GET["ruta"] == "crear-usuario" ||
                $_GET["ruta"] == "categorias" ||
                $_GET["ruta"] == "productos" ||
                $_GET["ruta"] == "vehiculos" ||
                // $_GET["ruta"] == "producto-compuesto" ||
                // $_GET["ruta"] == "sucursales" ||
                $_GET["ruta"] == "clientes" ||
                $_GET["ruta"] == "ventas" ||
                $_GET["ruta"] == "marcas" ||
                $_GET["ruta"] == "modelos" ||
                $_GET["ruta"] == "impuestos" ||
                $_GET["ruta"] == "crear-venta" ||
                $_GET["ruta"] == "reportes-ventas" ||
                $_GET["ruta"] == "unidades-medida" ||
                $_GET["ruta"] == "configuracion" ||
                $_GET["ruta"] == "config-empresa" ||
                $_GET["ruta"] == "config-usuarios" ||
                $_GET["ruta"] == "caja" ||
                $_GET["ruta"] == "libro-ventas" ||
                $_GET["ruta"] == "libro-compras" ||
                // $_GET["ruta"] == "servicios" ||
                // $_GET["ruta"] == "ajuste-inventario" ||
                // $_GET["ruta"] == "traslado-almacen" ||
                $_GET["ruta"] == "orden-compra" ||
                // $_GET["ruta"] == "orden-pago" ||
                // $_GET["ruta"] == "facturas-compra" ||
                // $_GET["ruta"] == "facturas-gastos" ||
                // $_GET["ruta"] == "devolucion-compra" ||
                // $_GET["ruta"] == "pedidos" ||
                // $_GET["ruta"] == "facturas" ||
                // $_GET["ruta"] == "notas-entrega" ||
                // $_GET["ruta"] == "notas-entrega-venta" ||
                // $_GET["ruta"] == "nota-credito" ||
                // $_GET["ruta"] == "presupuesto" ||
                // $_GET["ruta"] == "crear-presupuesto" ||
                // $_GET["ruta"] == "crear-nota-entrega" ||
                // $_GET["ruta"] == "crear-nota-entrega-venta" ||
                // $_GET["ruta"] == "crear-nota-credito" ||
                // $_GET["ruta"] == "crear-factura-compra" ||
                // $_GET["ruta"] == "crear-factura-venta" ||
                $_GET["ruta"] == "crear-orden-compra" ||
                $_GET["ruta"] == "editar-orden-compra" ||
                // $_GET["ruta"] == "crear-devolucion-compra" ||
                // $_GET["ruta"] == "crear-factura-gasto" ||
                // $_GET["ruta"] == "crear-factura" ||
                // $_GET["ruta"] == "crear-pedido" ||
                // $_GET["ruta"] == "cuentas-cobrar" ||
                $_GET["ruta"] == "cuentas-pagar" ||
                $_GET["ruta"] == "proveedores" ||
                $_GET["ruta"] == "config-perfil" ||
                $_GET["ruta"] == "configurar-perfiles" ||
                $_GET["ruta"] == "config-divisas" ||
                $_GET["ruta"] == "ingresos" ||
                /* $_GET["ruta"] == "mermas" || */
                $_GET["ruta"] == "salir"
            ) {
                include "modules/" . $_GET["ruta"] . ".php";
            } else {
                include "modules/404.php";
            }
        } else {
            include "modules/inicio.php";
        }
        /*=====================================
         FOOTER TEMPLATE
        ======================================**/
        include "modules/footer.php";

        echo '</div>';
    } else {
        include "modules/login.php";
    }
    ?>
    <!--=====================================
        SCRIPTS JS
    ======================================-->
    <?php
    
        // Función para cargar scripts (definición movida aquí)
        function loadModuleScripts($module, $versionAsset) {
            $moduleScripts = [
                'perfil' => 'perfiles.js',
                'config-usuarios' => 'usuarios.js',
                'config-perfil' => 'perfiles.js',
                'categorias' => 'categorias.js',
                'clientes' => 'clientes.js',
                'productos' => 'productos.js',
                'unidades-medida' => 'productos.js',
                'impuestos' => 'impuestos.js',
                'inventario' => 'inventario.js',
                'ingresos' => 'inventario.js',
                'divisas' => 'divisas.js',
                'config-divisas' => 'divisas.js',
                'bancos' => 'bancos.js',
                'formas-pago' => 'pagos.js',
                'pagos' => 'pagos.js',
                'marcas' => 'marca.js',
                'modelos' => 'modelos.js',
                'proveedores' => 'proveedores.js',
                'caja' => 'caja.js',
                'cuentas-pagar' => 'cuentas-pagar.js',
                'cuentas-cobrar' => 'cuentas-cobrar.js',
                'libro-ventas' => 'libro-ventas.js',
                'libro-compras' => 'libro-compras.js',
                'configuracion' => 'configuracion.js',
                'config-empresa' => 'configuracion.js',
                'crear-nota-entrega' => 'nota-entrega.js',
                'crear-nota-entrega_venta' => 'nota-entrega_venta.js',
                'crear-factura-compra' => 'facturas-compras.js',
                'crear-factura-gasto' => 'facturas-gastos.js',
                'crear-orden-compra' => 'orden-compra.js',
                'editar-orden-compra' => 'orden-compra.js',
                'devolucion-compra' => 'devolucion-compra.js',
                'pedidos-ventas' => 'pedidos-ventas.js',
                'crear-factura-venta' => 'factura-venta.js',
                'ventas' => 'ventas.js',
                'crear-venta' => 'ventas.js',
                'crear-nota-credito' => 'nota-credito.js',
                'presupuesto' => 'presupuesto.js',
                'reportes' => 'reportes.js',
                'reportes-ventas' => 'reportes.js',
                'vehiculos' => 'vehiculos.js'
            ];

            $globalScripts = [
                'config-updater.js',
                'template.js',
                'validaciones.js'
            ];

            foreach ($globalScripts as $script) {
                echo '<script src="' . $versionAsset('view/js/' . $script) . '"></script>' . "\n";
            }

            if (isset($moduleScripts[$module])) {
                echo '<script src="' . versionAsset('view/js/' . $moduleScripts[$module]) . '"></script>' . "\n";
            }

            if ($module == 'inicio' || empty($module)) {
                echo '<script src="' . versionAsset('view/js/inicio.js') . '"></script>' . "\n";
            }
        }
        
        if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
            $currentModule = isset($_GET["ruta"]) ? $_GET["ruta"] : 'inicio';
            loadModuleScripts($currentModule, 'versionAsset'); 
        }
    ?>

    <!-- <script src="view/js/compuestos.js"></script>
    <script src="view/js/unidades-medidas.js"></script> -->
    <!-- <script src="view/js/sucursales.js"></script> -->
    <!-- <script src="view/js/almacenes.js"></script> -->
    <!-- <script src="view/js/ajuste-inventario.js"></script> -->
    <!-- <script src="view/js/servicios.js"></script> -->
    <!-- <script src="view/js/anticipos.js"></script> -->
    <!-- <script src="view/js/traslado-almacen.js"></script> -->

</body>

</html>