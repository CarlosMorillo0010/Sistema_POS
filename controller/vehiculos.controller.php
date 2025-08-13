<?php

class ControllerVehiculos{
    /*=============================================
    MOSTRAR VEHICULOS
    =============================================*/
    static public function ctrMostrarVehiculos($item, $valor)
    {
        $tabla = "vehiculos";
        $respuesta = ModelVehiculos::mdlMostrarVehiculos($tabla, $item, $valor);
        return $respuesta;
    }

   /*=============================================
    CREAR VEHICULO
    =============================================*/
    static public function ctrCrearVehiculo(){

        if (isset($_POST["nuevaMarca"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaMarca"]) &&
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoModelo"]) &&
                preg_match('/^[0-9]+$/', $_POST["nuevoAno"]) &&
                preg_match('/^[a-zA-Z0-9 ]+$/', $_POST["nuevaPlaca"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoSerialChasis"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoSerialMotor"])) {
                    
                $tabla = "vehiculos";

                date_default_timezone_set('America/Caracas');

                $fecha = date('Y-m-d h:i:s');

                $datos = array(

                    "id_usuario" => $_SESSION["id_usuario"],
                    "marca" => $_POST["nuevaMarca"],
                    "modelo" => $_POST["nuevoModelo"],
                    "ano" => $_POST["nuevoAno"],
                    "placa" => $_POST["nuevaPlaca"],
                    "color" => $_POST["nuevoColor"],
                    "serial_chasis" => $_POST["nuevoSerialChasis"],
                    "serial_motor" => $_POST["nuevoSerialMotor"],
                    "descripcion" => $_POST["nuevaDescripcion"],
                    "feregistro" => $fecha
                );

                $respuesta = ModelVehiculos::mdlIngresarVehiculo($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                          type: "success",
                          title: "El vehiculo ha sido guardado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                    if (result.value) {
                                    window.location = "vehiculos";
                                    }
                                })
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                          type: "error",
                          title: "¡El vehiculo no puede ir vacío o llevar caracteres especiales!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                            if (result.value) {
                            window.location = "vehiculos";
                            }
                        })
                </script>';
            }
        }
    }

    /*=============================================
    EDITAR VEHICULO
    =============================================*/
    static public function ctrEditarVehiculo()
    {
        if (isset($_POST["editarMarca"])) {
            
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarMarca"]) &&
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarModelo"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarAno"]) &&
                preg_match('/^[a-zA-Z0-9 ]+$/', $_POST["editarPlaca"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["editarSerialChasis"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["editarSerialMotor"])) {

                $tabla = "vehiculos";

                $datos = array(

                    "id_vehiculo" => $_POST["idVehiculo"],
                    "marca" => $_POST["editarMarca"],
                    "modelo" => $_POST["editarModelo"],
                    "ano" => $_POST["editarAno"],
                    "placa" => $_POST["editarPlaca"],
                    "color" => $_POST["editarColor"],
                    "serial_chasis" => $_POST["editarSerialChasis"],
                    "serial_motor" => $_POST["editarSerialMotor"],
                    "descripcion" => $_POST["editarDescripcion"],

                );
                $respuesta = ModelVehiculos::mdlEditarVehiculo($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                          type: "success",
                          title: "El vehiculo ha sido editado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                    if (result.value) {
                                    window.location = "vehiculos";
                                    }
                                })
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                          type: "error",
                          title: "¡El vehiculo no puede ir vacío o llevar caracteres especiales!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                            if (result.value) {
                            window.location = "vehiculos";
                            }
                        })
                </script>';
            }
        }
    }

    /*=============================================
    BORRAR VEHICULO
    =============================================*/
    static public function ctrBorrarVehiculo()
    {
        if(isset($_GET["idVehiculo"])){
            $tabla = "vehiculos";
            $datos = $_GET["idVehiculo"];
            $respuesta = ModelVehiculos::mdlBorrarVehiculo($tabla, $datos);
            if ($respuesta == "ok"){
                echo'<script>
                    swal({
                          type: "success",
                          title: "El vehiculo ha sido borrado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                    if (result.value) {
                                    window.location = "vehiculos";
                                    }
                                })
                    </script>';
            }
        }
    }
}