<?php

class ControllerAlmacenes{

    /*=============================================
	CREAR ALMACEN
	=============================================*/
    static public function ctrCrearAlmacen(){
        if(isset($_POST["nuevoAlmacen"])){
            if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoAlmacen"])){
                $tabla = "almacenes";
                $id_sucursal = "1001";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "id_sucursal" => $id_sucursal,
                    "almacen" => $_POST["nuevoAlmacen"],
                    "fecha" => $fecha
                );
                $respuesta = ModelAlmacenes::mdlIngresarAlmacen($tabla, $datos);
                if($respuesta == "ok"){
                    echo'<script>
					swal({
						  type: "success",
						  title: "El almacen ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "almacenes";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					swal({
						  type: "error",
						  title: "¡El almacen no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "almacenes";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
	MOSTRAR ALMACEN
	=============================================*/
    static public function ctrMostrarAlmacen($item, $valor){
        $tabla = "almacenes";
        $respuesta = ModelAlmacenes::mdlMostrarAlmacen($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
	EDITAR ALMACEN
	=============================================*/
    static public function ctrEditarAlmacen(){
        if (isset($_POST["editarAlmacen"])){
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarAlmacen"])){
                $tabla = "almacenes";
                $datos = array(
                    "almacen" => $_POST["editarAlmacen"],
                    "id_almacen" => $_POST["idAlmacen"]
                );
                $respuesta = ModelAlmacenes::mdlEditarAlmacen($tabla, $datos);

                if ($respuesta == "ok"){
                    echo'<script>
					swal({
						  type: "success",
						  title: "El almacen ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "almacenes";
									}
								})
					</script>';
                }
            }else{
                echo'<script>
					swal({
						  type: "error",
						  title: "¡El almacen no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "almacenes";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
	BORRAR ALMACEN
	=============================================*/
    static public function ctrBorrarAlmacen()
    {
        if(isset($_GET["idAlmacen"])){
            $tabla = "almacenes";
            $datos = $_GET["idAlmacen"];
            $respuesta = ModelAlmacenes::mdlBorrarAlamacen($tabla, $datos);
            if ($respuesta == "ok"){
                echo'<script>
					swal({
						  type: "success",
						  title: "El almacen ha sido borrado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "almacenes";
									}
								})
					</script>';
            }
        }
    }
}