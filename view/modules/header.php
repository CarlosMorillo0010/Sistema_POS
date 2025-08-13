<?php
$recordatorios = array(); 

if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
    $recordatorios = ControllerCuentasPagar::ctrMostrarRecordatoriosDeuda();
}
?>

<header class="main-header">

    <!--=====================================
    LOGOTIPO
    ======================================-->
    <a href="inicio" class="logo">
        <span class="logo-lg">
            <!-- Tu logo aquí -->
        </span>
    </a>

    <!--=====================================
    BARRA DE NAVEGACION
    ======================================-->
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <!-- Botones de acceso rápido -->
        <a href="caja" type="button" class="btn btn-info pos-menu hidden-xs">
            <i class="fas fa-cash-register" aria-hidden="true"></i>  POS
        </a>
        <a href="inicio" type="button" target="_self" class="btn btn-info pos-panel">
            <i class="fa fa-dashboard" aria-hidden="true"></i>  PANEL
        </a>

        <!-- Menú de la derecha de la barra de navegación -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!--=====================================
                NOTIFICACIONES DE PAGO (CORREGIDO)
                ======================================-->
                <li class="dropdown notifications-menu">
                    
                    <!-- 1. El enlace <a> que activa el dropdown -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="far fa-bell"></i>
                        <?php if (count($recordatorios) > 0): ?>
                            <span class="label label-warning"><?php echo count($recordatorios); ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- 2. El div.dropdown-menu que contiene todo el menú desplegable -->
                    <ul class="dropdown-menu">
                        <li class="header">Tienes <?php echo count($recordatorios); ?> recordatorios</li>
                        <li>
                            <!-- Menú interno: aquí va la lista con scroll si es necesario -->
                            <ul class="menu">
                                <?php if (empty($recordatorios)): ?>
                                    <li class="text-center" style="padding: 10px;">
                                        <i class="fas fa-check-circle text-green"></i> No hay deudas próximas.
                                    </li>
                                <?php else: ?>
                                    <?php foreach ($recordatorios as $deuda): ?>
                                        <li>
                                            <a href="cuentas-pagar">
                                                <i class="fas fa-file-invoice-dollar text-aqua"></i>
                                                <span>Factura <?php echo htmlspecialchars($deuda["factura"]); ?></span>
                                                <?php
                                                $dias = $deuda["dias_para_vencer"];
                                                if ($dias < 0) {
                                                    echo '<small class="pull-right text-red">Vencida hace ' . abs($dias) . ' días</small>';
                                                } elseif ($dias == 0) {
                                                    echo '<small class="pull-right text-yellow">Vence Hoy</small>';
                                                } else {
                                                    echo '<small class="pull-right text-light-blue">Vence en ' . $dias . ' días</small>';
                                                }
                                                ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="cuentas-pagar">Ver Todas las deudas</a></li>
                    </ul>
                </li>

                <!--=====================================
                PERFIL DE USUARIO
                ======================================-->
                <li class="dropdown user user-menu">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">
                        <span><i class="hidden-lg hidden-md hidden-sm fas fa-user" aria-hidden="true"></i></span>
                        <span class="hidden-xs"><?php echo $_SESSION["nombre"]; ?></span>
                    </a>
                    <!-- DROPDOWN -->
                    <ul class="dropdown-menu" style="border: 0 !important; box-shadow: 0 14px 38px 0 rgba(0, 0, 0, 0.6); width: 220px !important;">
                        <li class="user-header" style="height: 85px">
                            <p>
                              <span><?php echo $_SESSION["nombre"]; ?></span>
                              <span><small><?php echo $_SESSION["perfil"]; ?></small></span>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="text-center">
                                <a href="salir" class="btn btn-danger">
                                    Salir
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div> 
    </nav>
</header>