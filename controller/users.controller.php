<?php

/*======================================
 USERS CONTROLLER
======================================**/

class ControllerUsers
{
    /*======================================
     INGRESO DE USUARIOS
    ======================================**/
    static public function ctrIngresarUsuario()
    {
        if (isset($_POST["usuario"])) {
            if (preg_match('/^[0-9]+$/', $_POST["usuario"]) &&
                preg_match('/^[a-z0-9]+$/', $_POST["password"])
            ) {
                $encriptar = crypt($_POST["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                $tabla = "usuarios";
                $item = "documento";
                $valor = $_POST["usuario"];

                $respuesta = ModelUsers::mdlMostrarUsuarios($tabla, $item, $valor);

                if ($respuesta["documento"] == $_POST["usuario"] && $respuesta["password"] == $encriptar && $respuesta["ESTADO"] == "A") {
                    if ($respuesta["status"] == 1) {
                        $_SESSION["iniciarSesion"] = "ok";
                        $_SESSION["id_usuario"] = $respuesta["id_usuario"];
                        $_SESSION["nombre"] = $respuesta["nombre"];
                        $_SESSION["perfil"] = $respuesta["perfil"];
                        /**=====================================
                         * REGISTRAR FECHA PARA SABER EL ULTIMO LOGIN
                         * ======================================**/
                        date_default_timezone_set('America/Caracas');
                        $fecha = date('Y-m-d');
                        $hora = date('H:i:s');

                        $fechaActual = $fecha . ' ' . $hora;
                        $item1 = "festamp";
                        $valor1 = $fechaActual;
                        $item2 = "id_usuario";
                        $valor2 = $respuesta["id_usuario"];

                        $ultimoLogin = ModelUsers::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);

                        /*=============================================================
                        CARGAR CONFIGURACIÓN GLOBAL Y TASA DE CAMBIO EN LA SESIÓN
                        =============================================================*/
                        $configuracion = ControllerConfiguraciones::ctrMostrarConfiguracion();
                        $tasa_usd = ModelDivisas::mdlMostrarDivisasConTasa("divisas", "tasas_cambio");
                        
                        $tasa_actual = 0;
                        foreach($tasa_usd as $tasa){
                            if($tasa['codigo'] == 'USD'){
                                $tasa_actual = $tasa['ultima_tasa'];
                                break;
                            }
                        }
                        
                        $_SESSION["config"] = [
                            "nombre_empresa" => $configuracion["nombre"],
                            "rif" => $configuracion["rif"],
                            "logo" => $configuracion["logo"],
                            "iva_porcentaje" => $configuracion["iva"],
                            "igtf_porcentaje" => $configuracion["igtf"],
                            "tasa_bcv" => (float)$tasa_actual
                        ];

                        if ($ultimoLogin == "ok") {
                            echo '<script>                    
                                    window.location = "inicio";
                                  </script>';
                        }
                    } else {
                        echo '
                            <script>
                                swal({
                                    type: "error",
                                    title: "¡El usuario aún no está activado!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    closeOnConfirm: false
                                    }).then((result) => {
                                        if(result.value){
                                            window.location = "ingreso";
                                        }
                                    });
                            </script>';
                        return;
                    }
                } else {
                    echo '
                            <script>
                                swal({
                                    type: "error",
                                    title: "¡Error al ingresar, vuelve a intentarlo!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    closeOnConfirm: false
                                    }).then((result) => {
                                        if(result.value){
                                            window.location = "ingreso";
                                        }
                                    });
                            </script>';
                    return;
                }
            }
        }
    }

    /*======================================
     CREAR USUARIOS
    ======================================**/
    static public function ctrCrearUsuario()
    {
        if (isset($_POST["nuevoDocumento"])) {
            if (preg_match('/[a-zA-Z]+$/', $_POST["nuevaNacionalidad"]) &&
                preg_match('/[0-9]+$/', $_POST["nuevoDocumento"]) &&
                preg_match('/[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/[a-zA-Z0-9]+$/', $_POST["password"]) &&
                preg_match('/[a-zA-Z0-9]+$/', $_POST["nuevoTelefono"]) &&
                preg_match('/[a-zA-Z0-9]+$/', $_POST["nuevoEmail"]) &&
                preg_match('/[a-zA-Z]+$/', $_POST["nuevoGenero"]) &&
                preg_match('/[a-zA-Z]+$/', $_POST["nuevoPerfil"])
            ) {

                $tabla = "usuarios";
                $encriptar = crypt($_POST["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario_adm" => $_SESSION["id_usuario"],
                    "nacionalidad" => $_POST["nuevaNacionalidad"],
                    "documento" => $_POST["nuevoDocumento"],
                    "nombre" => $_POST["nuevoNombre"],
                    "password" => $encriptar,
                    "telefono" => $_POST["nuevoTelefono"],
                    "correo" => $_POST["nuevoEmail"],
                    "genero" => $_POST["nuevoGenero"],
                    "perfil" => $_POST["nuevoPerfil"],
                    "fecha" => $fecha
                );

                $respuesta = ModelUsers::mdlIngresarUsuario($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡El Usuario ha sido guardado Correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "config-usuarios";
                            }
                        });
                </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El Usuario no puede ir vacio o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "config-usuarios";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     MOSTRAR USUARIOS
    ======================================**/
    static public function ctrMostrarUsuario($item, $valor)
    {
        $tabla = "usuarios";
        $respuesta = ModelUsers::mdlMostrarUsuarios($tabla, $item, $valor);
        return $respuesta;
    }

    /*======================================
     EDITAR USUARIOS
    ======================================**/
    static public function ctrEditarUsuario()
    {
        if (isset($_POST["editarDocumento"])) {
            if (preg_match('/[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])) {
                $tabla = "usuarios";
                if ($_POST["editarPassword"] != "") {
                    if (preg_match('/[a-zA-Z0-9]+$/', $_POST["editarPassword"])) {
                        $encriptar = crypt($_POST["editarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                    } else {
                        echo '
                            <script>
                                swal({
                                    type: "error",
                                    title: "¡La contraseña no puede ir vacia!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    closeOnConfirm: false
                                    }).then((result) => {
                                        if(result.value){
                                            window.location = "config-usuarios";
                                        }
                                    });
                            </script>';
                        return;
                    }
                } else {
                    $encriptar = $_POST["passwordActual"];
                }
                $datos = array(
                    "nombre" => $_POST["editarNombre"],
                    "documento" => $_POST["editarDocumento"],
                    "password" => $encriptar,
                    "telefono" => $_POST["editarTelefono"],
                    "correo" => $_POST["editarEmail"],
                    "genero" => $_POST["editarGenero"],
                    "perfil" => $_POST["editarPerfil"]
                );
                $respuesta = ModelUsers::mdlEditarUsuario($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '
                            <script>
                                swal({
                                    type: "success",
                                    title: "¡El Usuario ha sido editado correctamete!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    closeOnConfirm: false
                                    }).then((result) => {
                                        if(result.value){
                                            window.location = "config-usuarios";
                                        }
                                    });
                            </script>';
                }
            } else {
                echo '
                <script>
                    swal({
                        type: "error",
                        title: "¡El nombre no puede ir vacio o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "config-usuarios";
                            }
                        });
                </script>';
            }
        }
    }

    /*======================================
     BORRAR USUARIOS
    ======================================**/
    static public function ctrBorrarUsuario()
    {
        if (isset($_GET["idUsuario"])) {
            $tabla = "usuarios";
            $datos = $_GET["idUsuario"];

            $respuesta = ModelUsers::mdlBorrarUsuario($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡El Usuario ha sido borrado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then((result) => {
                            if(result.value){
                                window.location = "config-usuarios";
                            }
                        });
                </script>';
            }
        }
    }
}