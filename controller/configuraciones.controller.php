<?php
class ControllerConfiguraciones {

    static public function ctrMostrarConfiguracion() {
        return ModelConfiguraciones::mdlMostrarConfiguracion("configuracion");
    }

    static public function ctrGuardarConfiguracion() {
        if (isset($_POST["nombreEmpresa"])) {
            if (!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ .,&()-]+$/', $_POST["nombreEmpresa"]) || 
                !preg_match('/^[JVEGjvge]-[0-9]{8,9}-[0-9]$/', $_POST["rifEmpresa"])) {
                echo '<script>swal({type: "error", title: "¡Error en los datos!", text: "El nombre o RIF contienen caracteres no válidos."});</script>';
                return;
            }

            $rutaLogo = $_POST["logoActual"];
            if (isset($_FILES["imagenEmpresa"]["tmp_name"]) && !empty($_FILES["imagenEmpresa"]["tmp_name"])) {
                list($ancho, $alto) = getimagesize($_FILES["imagenEmpresa"]["tmp_name"]);
                $nuevoAncho = 500; $nuevoAlto = 500;
                $directorio = "views/img/config";

                if (!is_dir($directorio)) { mkdir($directorio, 0755); }

                if (!empty($_POST["logoActual"]) && file_exists($_POST["logoActual"])) {
                    unlink($_POST["logoActual"]);
                }

                $aleatorio = mt_rand(100, 999);
                if ($_FILES["imagenEmpresa"]["type"] == "image/jpeg") {
                    $rutaLogo = $directorio . "/" . $aleatorio . ".jpg";
                    $origen = imagecreatefromjpeg($_FILES["imagenEmpresa"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagejpeg($destino, $rutaLogo);
                } else if ($_FILES["imagenEmpresa"]["type"] == "image/png") {
                    $rutaLogo = $directorio . "/" . $aleatorio . ".png";
                    $origen = imagecreatefrompng($_FILES["imagenEmpresa"]["tmp_name"]);
                    $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                    imagealphablending($destino, false);
                    imagesavealpha($destino, true);
                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                    imagepng($destino, $rutaLogo);
                } else {
                     echo '<script>swal({type: "error", title: "¡Error de formato!", text: "La imagen debe ser JPG o PNG."});</script>';
                     return;
                }
            }
            
            date_default_timezone_set('America/Caracas');
            $datos = [
                "id" => $_POST["idConfiguracion"],
                "nombre" => $_POST["nombreEmpresa"],
                "rif" => $_POST["rifEmpresa"],
                "telefono" => $_POST["telefonoEmpresa"],
                "email" => $_POST["emailEmpresa"],
                "direccion" => $_POST["direccionEmpresa"],
                "iva" => $_POST["iva"],
                "igtf" => $_POST["igtf"],
                "moneda_principal_id" => $_POST["monedaPrincipalId"],
                "logo" => $rutaLogo,
                "id_usuario_mod" => $_SESSION["id_usuario"], // Asegúrate que la variable de sesión del ID de usuario sea "id"
                "fecha_mod" => date('Y-m-d H:i:s')
            ];

            $respuesta = ModelConfiguraciones::mdlGuardarConfiguracion("configuracion", $datos);
            if ($respuesta == "ok") {
                echo '<script>
                swal({
                    type: "success", title: "¡Configuración guardada!", text: "Los datos se han guardado correctamente.", showConfirmButton: true, confirmButtonText: "Cerrar"
                }).then(result => { if(result.value){ window.location = "config-empresa"; } });
                </script>';
            }
        }
    }
}