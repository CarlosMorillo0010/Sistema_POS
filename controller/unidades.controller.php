<?php

class ControllerUnidades
{
    /*=============================================
     CREAR UNIDADES DE MEDIDAS
    =============================================*/
    static public function ctrCrearUnidad()
    {
        if (isset($_POST["nuevoNombre"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ$€ ]+$/', $_POST["nuevaUnidad"])) {
                $tabla = "unidadesdemedidas";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "nombre" => $_POST["nuevoNombre"],
                    "unidad" => $_POST["nuevaUnidad"],
                    "fecha" => $fecha
                );
                $respuesta = ModelUnidades::mdlIngresarUnidad($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "La Und. de Medida ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "unidades-medida";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡La Und. de Medida no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "unidades-medida";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
     MOSTRAR UNIDADES DE MEDIDAS
    =============================================*/
    static public function ctrMostrarUnidad($item, $valor)
    {
        $tabla = "unidadesdemedidas";
        $respuesta = ModelUnidades::mdlMostrarUnidad($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
     EDITAR UNIDADES DE MEDIDAS
    =============================================*/
    static public function ctrEditarUnidad()
    {
        if (isset($_POST["editarNombre"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ$€ ]+$/', $_POST["editarUnidad"])) {
                $tabla = "unidadesdemedidas";
                $datos = array(
                    "nombre" => $_POST["editarNombre"],
                    "unidad" => $_POST["editarUnidad"],
                    "id_unidad" => $_POST["idUnidad"]
                );
                $respuesta = ModelUnidades::mdlEditarUnidad($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					swal({
						  type: "success",
						  title: "La Und. de Medida ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "unidades-medida";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					swal({
						  type: "error",
						  title: "¡La Und. de Medida no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "unidades-medida";
							}
						})
			  	</script>';
            }
        }
    }

    /*======================================
     BORRAR UNIDADES DE MEDIDAS
    ======================================**/
    static public function ctrBorrarUnidad()
    {
        if (isset($_GET["idUnidad"])) {
            $tabla = "unidadesdemedidas";
            $datos = $_GET["idUnidad"];
            $respuesta = ModelUnidades::mdlBorrarUnidad($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
						swal({
							  type: "success",
							  title: "La unid. de medida ha sido borrada correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "unidades-medida";
										}
									})
						</script>';
            }
        }
    }
}