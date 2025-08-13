<?php
    if ($_SESSION["perfil"] != "ADMINISTRADOR"){
        echo '
            <script>
            window.location = "inicio";
            </script>
        ';
        return;
    }
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Configuración
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio</a></li>
            <li class="active">Configuración</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="config content" style="min-height: 70vh">

        <div class="row col-sm-12" style="justify-content: center; align-items: center;">

            <div class="col-12 col-sm-6 col-md-3">
                <a href="config-empresa" class="text-dark config-box mb-3">
                    <span class="config-box-icon bg-aqua"><i class="fas fa-building"></i></span>

                    <div class="config-box-content">
                        <span class="config-box-text">Empresa e Impustos</span>
                            <span class="text-mini">Registrar.</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="config-perfil" class="text-dark config-box mb-3">
                    <span class="config-box-icon bg-red"><i class="fas fa-users-cog"></i></span>

                    <div class="config-box-content">
                        <span class="config-box-text">Perfiles</span>
                            <span class="text-mini">Registrar, editar y eliminar.</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="config-usuarios" class="text-dark config-box mb-3">
                    <span class="config-box-icon bg-yellow"><i class="fas fa-users"></i></span>

                    <div class="config-box-content">
                        <span class="config-box-text">Usuarios</span>
                            <span class="text-mini">Registrar, editar y eliminar.</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="config-divisas" class="text-dark config-box mb-3">
                    <span class="config-box-icon bg-green"><i class="fas fa-money-check-alt"></i></span>

                    <div class="config-box-content">
                        <span class="config-box-text">Divisas y Tasa de Cambio</span>
                            <span class="text-mini">Registrar, editar y eliminar.</span>
                    </div>
                </a>
            </div>
        
        </div>
    
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->