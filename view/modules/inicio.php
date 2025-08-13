<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Tablero
            <small>Panel de Control</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <?php
            if($_SESSION["perfil"] == "ADMINISTRADOR")
            {
                include "inicio/cajas-superiores.php";
            }
            ?>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                if($_SESSION["perfil"] == "ADMINISTRADOR") {
                    include "reportes/grafico-ventas.php";
                }else{
                    echo '
                    <div class="box box-primary">
                        <div class="box-header">
                            <h1>BIENVENIDO ' .$_SESSION["perfil"]. '</h1>
                        </div>              
                    </div>';
                }
                ?>
            </div>
            <div class="col-lg-6">
                <?php
                if($_SESSION["perfil"] == "ADMINISTRADOR") {
                    include "reportes/productos-mas-vendidos.php";
                }
                ?>
            </div>

            <div class="col-lg-6">
                <?php
                if($_SESSION["perfil"] == "ADMINISTRADOR") {
                    include "reportes/vendedores.php";
                    include "reportes/compradores.php";
                }
                ?>
            </div>
        </div>
    </section>
</div>