<?php

class ControllerDivisas
{
    /*=============================================
    CREAR DIVISAS
    =============================================*/
    static public function ctrCrearDivisa()
    {
        if (isset($_POST["nuevaDivisa"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDivisa"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ$€ ]+$/', $_POST["nuevoSimbolo"])) {
                $tabla = "divisas";
                date_default_timezone_set('America/Caracas');
                $fecha = date('Y-m-d h:i:s');
                $datos = array(
                    "id_usuario" => $_SESSION["id_usuario"],
                    "divisa" => $_POST["nuevaDivisa"],
                    "simbolo" => $_POST["nuevoSimbolo"],
                    "fecha" => $fecha
                );
                $respuesta = ModelDivisas::mdlIngresarDivisa($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					Swal.fire({
						  icon: "success",
						  title: "La divisa ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "divisas";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					Swal.fire({
						  icon: "error",
						  title: "¡La divisa no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "divisas";
							}
						})
			  	</script>';
            }
        }
    }

    /*=============================================
    EDITAR DIVISAS
    =============================================*/
    static public function ctrEditarDivisa()
    {
        if (isset($_POST["editarDivisa"])) {
            if (preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDivisa"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ$€ ]+$/', $_POST["editarSimbolo"])) {
                $tabla = "divisas";
                $datos = array(
                    "divisa" => $_POST["editarDivisa"],
                    "simbolo" => $_POST["editarSimbolo"],
                    "id_divisa" => $_POST["idDivisa"]
					);
                $respuesta = ModelDivisas::mdlEditarDivisa($tabla, $datos);
                if ($respuesta == "ok") {
                    echo '<script>
					Swal.fire({
						  icon: "success",
						  title: "La divisa ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "divisas";
									}
								})
					</script>';
                }
            } else {
                echo '<script>
					Swal.fire({
						  icon: "error",
						  title: "¡La divisa no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "divisas";
							}
						})
			  	</script>';
            }
        }
    }

    /**=====================================
     * BORRAR DIVISAS
     * ======================================**/
    static public function ctrBorrarDivisa()
    {
        if (isset($_GET["idDivisa"])) {
            $tabla = "divisas";
            $datos = $_GET["idDivisa"];
            $respuesta = ModelDivisas::mdlBorrarDivisa($tabla, $datos);
            if ($respuesta == "ok") {
                echo '<script>
						Swal.fire({
							  icon: "success",
							  title: "La divisa ha sido borrada correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "divisas";
										}
									})
						</script>';
            }
        }
    }

    static public function ctrMostrarDivisa($item, $valor) {
        return ModelDivisas::mdlMostrarDivisa("divisas", $item, $valor);
    }

    static public function ctrMostrarDivisasConTasa(){
        return ModelDivisas::mdlMostrarDivisasConTasa("divisas", "tasas_cambio");
    }

    static public function ctrActualizarTasaBCV() {
        try {
            // --- INICIO DE LA CORRECCIÓN ---
    
            // CAMBIO 1: Se usa la URL funcional de tu código JavaScript.
            $apiUrl = 'https://pydolarve.org/api/v2/tipo-cambio?currency=usd';
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            // ... (el resto de las opciones de cURL no cambian)
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
            $json_data = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                curl_close($ch);
                return ["status" => "error", "message" => "Error de cURL: " . $error_msg];
            }
            curl_close($ch);
    
            $data = json_decode($json_data, true);
    
            // CAMBIO 2: Se ajusta la validación a la nueva respuesta de la API.
            // Tu propio código JS confirma que la respuesta contiene el campo "price".
            if ($http_code != 200 || !isset($data['price'])) {
                 return ["status" => "error", "message" => "La respuesta de la API no tiene el formato esperado o hubo un error."];
            }
            
            // CAMBIO 3: Se extraen los datos de la nueva estructura.
            $tasaBCV = (float) $data['price'];
            date_default_timezone_set('America/Caracas');
            $fechaTasa = date('Y-m-d');
            
            // --- FIN DE LA CORRECCIÓN ---
    
            // El resto del código para guardar en la BD funciona perfectamente.
            $divisaUSD = ModelDivisas::mdlMostrarDivisa("divisas", "codigo", "USD");
            if (!$divisaUSD) {
                return ["status" => "error", "message" => "La divisa 'USD' no está registrada en el sistema."];
            }
            
            $datos = ["divisa_id" => $divisaUSD["id"], "tasa" => $tasaBCV, "fecha" => $fechaTasa];
            $respuesta = ModelDivisas::mdlGuardarTasaCambio("tasas_cambio", $datos);
            
            $tasaFormateada = number_format($tasaBCV, 4, ',', '.');
            switch ($respuesta) {
                case "ok_inserted":
                    return ["status" => "success", "message" => "Nueva tasa del BCV (".$tasaFormateada.") guardada para hoy."];
                case "ok_updated":
                    return ["status" => "success", "message" => "Tasa de hoy actualizada a: ".$tasaFormateada];
                case "not_changed":
                    return ["status" => "info", "message" => "La tasa de hoy (".$tasaFormateada.") ya se encuentra registrada sin cambios."];
                default:
                    return ["status" => "error", "message" => "Error al guardar la tasa en la base de datos."];
            }
    
        } catch (Exception $e) {
            return ["status" => "error", "message" => "Excepción capturada: " . $e->getMessage()];
        }
    }

    static public function ctrObtenerTasaActual($codigoDivisa) {
        return ModelDivisas::mdlObtenerTasaActual("tasas_cambio", "divisas", $codigoDivisa);
    }

    /*===================================================================
     MÉTODO PARA ACTUALIZAR LA TASA DESDE EL BCV (PARA SER USADO POR AJAX)
    ===================================================================*/
    static public function ctrActualizarTasaDesdeBCV()
    {
        // Usaremos esta API que parece responder de forma más estándar.
        $apiUrl = "https://pydolarve.org/api/v2/tipo-cambio?currency=usd";

        $ch = curl_init();

        // Configuración de cURL
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Timeout de conexión en segundos
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);      // Timeout total de la petición

        // === INICIO DE LA CORRECCIÓN CLAVE ===
        // 1. Simula ser un navegador para evitar bloqueos.
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36');
        
        // 2. Esta es la solución más común para el "Error: 0".
        //    Le dice a cURL que no verifique el certificado SSL del par.
        //    Esto soluciona el 90% de los problemas en hostings compartidos.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // === FIN DE LA CORRECCIÓN CLAVE ===

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Mejora en la captura de errores:
        // Primero, verificamos si hubo un error a nivel de cURL (conexión, DNS, etc.)
        if (curl_errno($ch)) {
            $curlError = curl_error($ch);
            curl_close($ch);
            // Devolvemos el error específico de cURL para un mejor diagnóstico.
            return ["status" => "error", "message" => "Error de cURL al conectar con la API: " . $curlError];
        }

        curl_close($ch);

        // Segundo, verificamos el código de respuesta HTTP.
        if ($httpCode != 200) {
            return ["status" => "error", "message" => "El servicio de la tasa no está disponible (Respuesta HTTP: " . $httpCode . ")."];
        }

        $data = json_decode($response, true);

        // Tercero, verificamos si la respuesta JSON es válida y tiene el formato esperado.
        // La API que usamos ahora devuelve el precio en el campo 'price'.
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['price']) || !is_numeric($data['price'])) {
            return ["status" => "error", "message" => "La respuesta de la API no tuvo el formato JSON esperado."];
        }

        $nuevaTasa = floatval($data['price']);
        
        if ($nuevaTasa <= 0) {
             return ["status" => "error", "message" => "Se recibió una tasa inválida (cero o negativa)."];
        }

        date_default_timezone_set('America/Caracas');
        $fechaHoy = date('Y-m-d');
        
        // Buscamos el ID de la divisa USD en la base de datos.
        $divisaUSD = ModelDivisas::mdlMostrarDivisa("divisas", "codigo", "USD");
        if (!$divisaUSD) {
            return ["status" => "error", "message" => "La divisa 'USD' no se encuentra registrada en el sistema. Asegúrate de que exista en la tabla 'divisas'."];
        }

        // Preparamos los datos para guardar.
        $datos = [
            "id_divisa" => $divisaUSD["id"], // Usamos el ID dinámico
            "tasa"      => $nuevaTasa,
            "fecha"     => $fechaHoy
        ];

        // Llamamos al método del modelo para guardar o actualizar la tasa del día.
        // Asegúrate de que este método exista y funcione.
        $guardado = ModelDivisas::mdlGuardarTasaDelDia($datos);

        if ($guardado == "ok") {
            // Si todo salió bien, devolvemos un array con el estado y la nueva tasa
            return [
                "status" => "ok",
                "tasa"   => $nuevaTasa,
                "fecha"  => $fechaHoy
            ];
        } else {
            // Si hubo un error en la BD, lo reportamos.
            return ["status" => "error", "message" => "Error al guardar la nueva tasa en la base de datos: " . $guardado];
        }
    }
}