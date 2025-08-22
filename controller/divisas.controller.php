<?php

class ControllerDivisas
{
    /**
     * Lista de proveedores de datos en orden de prioridad.
     * Si el primero falla, se intentará con el segundo, y así sucesivamente.
     * El scraping se deja como el último y más frágil recurso.
     */
    private const API_PROVIDERS = [
        'exchangedate' => 'https://exchangedate.app/api/v1/rates/bcv/usd',      // Opción 1 (API simple y rápida)
        'pydolarve'    => 'https://pydolarve.org/api/v2/tipo-cambio?currency=usd', // Opción 2 (API alternativa)
        'monitordolar' => 'https://api.monitordolarvenezuela.com/last',            // Opción 3 (API completa)
        'bcv_scrape'   => 'https://www.bcv.org.ve/'                               // Opción 4 (Scraping directo)
    ];

    private const CODIGO_DIVISA_PRINCIPAL = 'USD';

    /*=======================================================================
     * SECCIÓN ROBUSTA PARA LA ACTUALIZACIÓN AUTOMÁTICA DE TASA DE CAMBIO
     =======================================================================*/

    /**
     * MÉTODO PÚBLICO PRINCIPAL PARA ACTUALIZAR LA TASA (VERSIÓN CON FALLBACK MÚLTIPLE)
     * @return array Respuesta estructurada para ser convertida a JSON.
     */
    static public function ctrActualizarTasaBCV()
    {
        $nuevaTasa = null;
        $errorMessages = []; // Para depuración

        foreach (self::API_PROVIDERS as $providerKey => $url) {
            $resultado = ($providerKey === 'bcv_scrape')
                ? self::_consultarBCVPorScraping($url)
                : self::_consultarApiExterna($url);

            if ($resultado['success']) {
                $tasaParseada = self::_parsearTasaDesdeRespuesta($resultado['data'], $providerKey);
                if ($tasaParseada !== null) {
                    $nuevaTasa = $tasaParseada;
                    break; // ¡Éxito! Salimos del bucle.
                }
                $errorMessages[] = "Proveedor '$providerKey': formato de respuesta inesperado.";
            } else {
                $errorMessages[] = "Proveedor '$providerKey': " . $resultado['message'];
            }
        }

        if ($nuevaTasa === null) {
            // error_log("Fallo total de proveedores de divisas: " . implode(" | ", $errorMessages));
            return ["status" => "error", "message" => "No se pudo obtener la tasa de cambio de ninguno de los servicios externos."];
        }
        
        $divisa = ModelDivisas::mdlMostrarDivisa("divisas", "codigo", self::CODIGO_DIVISA_PRINCIPAL);
        if (!$divisa) {
            return ["status" => "error", "message" => "Configuración requerida: La divisa '" . self::CODIGO_DIVISA_PRINCIPAL . "' no existe en el sistema."];
        }

        date_default_timezone_set('America/Caracas');
        $datosParaGuardar = ["id_divisa" => $divisa["id"], "tasa" => $nuevaTasa, "fecha" => date('Y-m-d')];

        try {
            $resultadoDB = ModelDivisas::mdlGuardarOActualizarTasa($datosParaGuardar);
            $tasaFormateada = number_format($nuevaTasa, 2, ',', '.');
            switch ($resultadoDB) {
                case 'inserted': return ["status" => "ok", "message" => "¡Éxito! Tasa del BCV ($tasaFormateada) guardada para hoy.", "tasa" => $nuevaTasa];
                case 'updated': return ["status" => "ok", "message" => "Tasa de hoy actualizada a: $tasaFormateada.", "tasa" => $nuevaTasa];
                case 'not_changed': return ["status" => "info", "message" => "La tasa ($tasaFormateada) ya estaba registrada sin cambios.", "tasa" => $nuevaTasa];
                default: return ["status" => "error", "message" => "Error desconocido al guardar la tasa."];
            }
        } catch (Exception $e) {
            return ["status" => "error", "message" => "Error crítico al conectar con la base de datos."];
        }
    }

    /**
     * FUNCIÓN PRIVADA PARA HACER WEB SCRAPING A LA PÁGINA DEL BCV.
     */
    private static function _consultarBCVPorScraping($url) {
        $ch = curl_init();
        curl_setopt_array($ch, [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 15, CURLOPT_TIMEOUT => 30, CURLOPT_USERAGENT => 'Mozilla/5.0', CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false]);
        $html = curl_exec($ch);
        if (curl_errno($ch)) { return ['success' => false, 'message' => 'Error de cURL: ' . curl_error($ch)]; }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        if ($httpCode != 200) { return ['success' => false, 'message' => "La web del BCV no está disponible (HTTP: $httpCode)"]; }
        if (empty($html)) { return ['success' => false, 'message' => "La web del BCV devolvió una respuesta vacía."]; }
        $dom = new DOMDocument(); @$dom->loadHTML($html);
        $dolarDiv = $dom->getElementById('dolar');
        if (!$dolarDiv) { return ['success' => false, 'message' => 'Scraping fallido: No se encontró id="dolar".']; }
        $strongElement = $dolarDiv->getElementsByTagName('strong')->item(0);
        if (!$strongElement) { return ['success' => false, 'message' => 'Scraping fallido: No se encontró <strong> con el precio.']; }
        return ['success' => true, 'data' => ['price_text' => trim($strongElement->nodeValue)]];
    }

    /**
     * MÉTODO PARSER para manejar las diferentes respuestas (APIs y Scraper).
     */
    private static function _parsearTasaDesdeRespuesta($data, $providerKey) {
        $tasa = null;
        switch ($providerKey) {
            case 'exchangedate': case 'pydolarve':
                if (isset($data['price']) && is_numeric($data['price']) && $data['price'] > 0) $tasa = floatval($data['price']);
                break;
            case 'monitordolar':
                if (isset($data['bcv']['price']) && is_numeric($data['bcv']['price']) && $data['bcv']['price'] > 0) $tasa = floatval($data['bcv']['price']);
                break;
            case 'bcv_scrape':
                if (isset($data['price_text'])) {
                    $precioNumerico = (float) str_replace(',', '.', $data['price_text']);
                    if ($precioNumerico > 0) $tasa = $precioNumerico;
                }
                break;
        }
        return $tasa;
    }

    /**
     * MÉTODO PRIVADO para consultar APIs que devuelven JSON.
     */
    private static function _consultarApiExterna($url) {
        $ch = curl_init();
        curl_setopt_array($ch, [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => 20, CURLOPT_USERAGENT => 'Mozilla/5.0', CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) { $error_msg = curl_error($ch); curl_close($ch); return ['success' => false, 'message' => "Error de cURL: " . $error_msg]; }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        if ($httpCode != 200) { return ['success' => false, 'message' => "Servicio no disponible (HTTP: " . $httpCode . ")."]; }
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) { return ['success' => false, 'message' => "La respuesta no es un JSON válido."]; }
        return ['success' => true, 'data' => $data];
    }


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


    static public function ctrObtenerTasaActual($codigoDivisa) {
        return ModelDivisas::mdlObtenerTasaActual("tasas_cambio", "divisas", $codigoDivisa);
    }

}