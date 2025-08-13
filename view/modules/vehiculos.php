<?php
$item = null;
$valor = null;
$perfil = ControllerPerfiles::ctrMostrarPerfil($item, $valor);
foreach ($perfil as $key => $value):
    if ($_SESSION["perfil"] == $value["perfil"] && $value["inventario"] == "N"){
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
            Administrar vehiculos
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Administrar vehiculo</li>
        </ol>
    </section>

    <section class="content">
        <div class="box" style="border-top-color: #3c8dbc;">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarVehiculo">
                    Agregar vehiculo
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
                    <thead>
                    <tr>
                        <th style="width:10px">#</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Placa</th>
                        <th>Color</th>
                        <th>Serial chasis</th>
                        <th>Serial motor</th>
                        <th>Descripción</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $item = null;
                            $valor = null;
                            $vehiculos = ControllerVehiculos::ctrMostrarVehiculos($item, $valor);
                            foreach ($vehiculos as $key => $value) {
                                echo '<tr>    
                                <td>' . ($key + 1) . '</td>    
                                <td>' . $value["marca"] . '</td>   
                                <td>' . $value["modelo"] . '</td>   
                                <td>' . $value["ano"] . '</td>        
                                <td>' . $value["placa"] . '</td>    
                                <td>' . $value["color"] . '</td>    
                                <td>' . $value["serial_chasis"] . '</td>  
                                <td>' . $value["serial_motor"] . '</td>  
                                <td>' . $value["descripcion"] . '</td>  
                                <td>' . $value["feregistro"] . '</td>   
                                <td>    
                                  <div class="btn-group">

                                    <button class="btn btn-dark btnVerVehiculo" data-toggle="modal" data-target="#modalVerVehiculo" idVehiculo="' . $value["id_vehiculo"] . '"><i class="fa fa-eye"></i></button>

                                    <button class="btn btn-warning btnEditarVehiculo" data-toggle="modal" data-target="#modalEditarVehiculo" idVehiculo="' . $value["id_vehiculo"] . '"><i class="fa fa-pencil"></i></button>

                                    <button class="btn btn-danger btnEliminarVehiculo" idVehiculo="' . $value["id_vehiculo"] . '"><i class="fa fa-times"></i></button>

                                  </div>      
                                </td>    
                              </tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!--=====================================
MODAL AGREGAR VEHICULO
======================================-->
<div id="modalAgregarVehiculo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar vehiculo</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                        <!-- MARCA -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Marca:</small>
                                    <select class="form-control select2" name="nuevaMarca" style="width: 100%;" required>
                                        <option value="">Elige una marca</option>
                                            <?php
                                                $item = null;
                                                $valor = null;

                                                $marcas = ControllerMarcas::ctrMostrarMarca($item, $valor);
                                                foreach ($marcas as $key => $value) {
                                                    echo '<option value="' . $value["marca"] . '">' . $value["marca"] . '</option>';
                                                }
                                            ?>
                                    </select>
                                </label>
                            </div>
                        </div>


                        <!-- MODELO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Modelo:</small>
                                    <select class="form-control select2" name="nuevoModelo" style="width: 100%;" required>
                                        <option value="">Elige un modelo</option>
                                            <?php
                                                $item = null;
                                                $valor = null;

                                                $modelos = ControllerModelos::ctrMostrarModelos($item, $valor);
                                                foreach ($modelos as $key => $value) {
                                                    echo '<option value="' . $value["modelo"] . '">' . $value["modelo"] . '</option>';
                                                }
                                            ?>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL AÑO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Año:</small>
                                    <input type="text" min="0" class="form-control input-lg" name="nuevoAno"
                                        placeholder="Ingresa el año" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA PLACA -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Placa:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaPlaca"
                                        placeholder="Ingresar placa" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL COLOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Color:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoColor"
                                        placeholder="Ingresa el color">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SERIAL DEL CHASIS -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Serial del chasis:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoSerialChasis"
                                        placeholder="Ingresa el serial" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SERIAL DEL MOTOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Serial del motor:</small>
                                    <input type="text" class="form-control input-lg" name="nuevoSerialMotor"
                                        placeholder="Ingresa el serial" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA DESCRIPCION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label><small style="color: #000;">Descripción:</small>
                                    <input type="text" class="form-control input-lg" name="nuevaDescripcion"
                                        placeholder="Ingresa una descripción (opcional)">
                                </label>
                            </div>
                        </div>
                    </div>

                    <span style="color: red;">( * ) Campo obligatorio</span>

                </div>

                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                
            </form>

            <?php
                $crearVehiculo = new ControllerVehiculos();
                $crearVehiculo -> ctrCrearVehiculo();
            ?>
            
        </div>
    </div>
</div>

<!--=====================================
MODAL EDITAR VEHICULO
======================================-->
<div id="modalEditarVehiculo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar vehiculo</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                         <!-- MARCA -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Marca:</small>
                                    <select class="form-control input-lg" name="editarMarca" style="width: 100%;" required>
                                        <option id="editarMarca"></option>
                                    </select>
                                </label>
                            </div>
                        </div>


                        <!-- MODELO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Modelo</small>
                                    <select class="form-control input-lg" name="editarModelo" style="width: 100%;" required>
                                        <option id="editarModelo"></option>
                                    </select>
                                </label>
                            </div>
                        </div>


                        <!-- ENTRADA PARA EL AÑO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Año:</small>
                                    <input type="text" min="0" class="form-control input-lg" id="editarAno" name="editarAno"
                                        placeholder="Ingresa el año" required>
                                    <input type="hidden" id="idVehiculo" name="idVehiculo">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA PLACA -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label style="color: red;"> * <small style="color: #000;">Placa:</small>
                                    <input type="text" class="form-control input-lg" id="editarPlaca" name="editarPlaca"
                                        placeholder="Ingresar placa" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL COLOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Color:</small>
                                    <input type="text" class="form-control input-lg" id="editarColor" name="editarColor"
                                        placeholder="Ingresa el color">
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SERIAL DEL CHASIS -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Serial del chasis:</small>
                                    <input type="text" class="form-control input-lg" id="editarSerialChasis" name="editarSerialChasis"
                                        placeholder="Ingresa el serial" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SERIAL DEL MOTOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Serial del motor:</small>
                                    <input type="text" class="form-control input-lg" id="editarSerialMotor" name="editarSerialMotor"
                                        placeholder="Ingresa el serial" required>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA DESCRIPCION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label><small style="color: #000;">Descripción:</small>
                                    <input type="text" class="form-control input-lg" id="editarDescripcion" name="editarDescripcion"
                                        placeholder="Ingresa una descripción (opcional)">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--=====================================
                PIE DEL MODAL
                ======================================-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>

            <?php
                $editarVehiculo = new ControllerVehiculos();
                $editarVehiculo -> ctrEditarVehiculo();
            ?>
            
        </div>
    </div>
</div>

<!---------------------------------------
MODAL VISUALIZAR VEHICULO
---------------------------------------->
<div id="modalVerVehiculo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post">
                <!--=====================================
                CABEZA DEL MODAL
                ======================================-->
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Vehiculo</h4>
                </div>
                <!--=====================================
                CUERPO DEL MODAL
                ======================================-->
                <div class="modal-body">
                    <div class="box-body">

                         <!-- MARCA -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Marca:</small>
                                    <input type="text" min="0" class="form-control input-lg" id="verMarca" name="verMarca" required readonly>
                                </label>
                            </div>
                        </div>


                        <!-- MODELO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Modelo:</small>
                                    <input type="text" min="0" class="form-control input-lg" id="verModelo" name="verModelo" required readonly>
                                </label>
                            </div>
                        </div>


                        <!-- ENTRADA PARA EL AÑO -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Año:</small>
                                    <input type="text" min="0" class="form-control input-lg" id="verAno" name="verAno"
                                        placeholder="Ingresa el año" required readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA PLACA -->
                        <div class="form-group col-lg-3">
                            <div class="input-group">
                                <label><small style="color: #000;">Placa:</small>
                                    <input type="text" class="form-control input-lg" id="verPlaca" name="verPlaca"
                                        placeholder="Ingresar placa" required readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL COLOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Color:</small>
                                    <input type="text" class="form-control input-lg" id="verColor" name="verColor"
                                        placeholder="Ingresa el color" readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SERIAL DEL CHASIS -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Serial del chasis:</small>
                                    <input type="text" class="form-control input-lg" id="verSerialChasis" name="verSerialChasis"
                                        placeholder="Ingresa el serial" required readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA EL SERIAL DEL MOTOR -->
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <label><small style="color: #000;">Serial del motor:</small>
                                    <input type="text" class="form-control input-lg" id="verSerialMotor" name="verSerialMotor"
                                        placeholder="Ingresa el serial" required readonly>
                                </label>
                            </div>
                        </div>

                        <!-- ENTRADA PARA LA DESCRIPCION -->
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <label><small style="color: #000;">Descripción:</small>
                                    <input type="text" class="form-control input-lg" id="verDescripcion" name="verDescripcion"
                                        placeholder="Ingresa una descripción (opcional)" readonly>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
    $borrarVehiculo = new ControllerVehiculos();
    $borrarVehiculo -> ctrBorrarVehiculo();
?>





