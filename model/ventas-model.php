<?php

/*======================================
 CONNECTION DATA BASE
======================================**/
require_once "connection.php";

class ModelVentas{

    /*=============================================
     MOSTRAR VENTAS
    =============================================*/
    static public function mdlMostrarVenta($tabla, $item, $valor){
        if($item != null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id_venta ASC");
            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetch();
        }else{
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla ORDER BY id_venta ASC");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt -> close();
        $stmt = null;
    }

    /*=============================================
     REGISTRO DE VENTAS
    =============================================*/
    // static public function mdlIngresarVenta($tabla, $datos){
    //     $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, id_cliente, id_vendedor, codigo_venta, vendedor, productos, precio_neto, total,metodo_pago, fecha) VALUES (:id_usuario, :id_cliente, :id_vendedor, :codigo_venta, :vendedor, :productos, :precio_neto, :total,:metodo_pago, :fecha)");
    //     $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
    //     $stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_STR);
    //     $stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
    //     $stmt->bindParam(":codigo_venta", $datos["codigo_venta"], PDO::PARAM_STR);
    //     $stmt->bindParam(":vendedor", $datos["vendedor"], PDO::PARAM_STR);
    //     $stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
    //     $stmt->bindParam(":precio_neto", $datos["precio_neto"], PDO::PARAM_STR);
    //     $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
    //     $stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
    //     $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);

    //     if($stmt->execute()){
    //         return "ok";
    //     }else{
    //         return "error";
    //     }
    //     $stmt -> close();
    //     $stmt = null;
    // }

    /*=============================================
     RANGO FECHAS
    =============================================*/
    static public function mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal){
        if($fechaInicial == null){
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla ORDER BY id_venta ASC");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }else if($fechaInicial == $fechaFinal){
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE fecha like '%$fechaFinal%'");
            $stmt -> bindParam(":fecha", $fechaFinal, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetchAll();
        }else{
            $fechaActual = new DateTime();
            $fechaActual->add(new DateInterval("P1D"));
            $fechaActualMasUno = $fechaActual->format("Y-m-d");
            $fechaFinal2 = new DateTime($fechaFinal);
            $fechaFinal2->add(new DateInterval("P1D"));
            $fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

            if($fechaFinalMasUno == $fechaFinalMasUno){
                $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");
            }else{

                $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal'");
            }

            $stmt -> execute();
            return $stmt -> fetchAll();
        }
    }

    /*=============================================
    INGRESAR VENTA DENTRO DE UNA TRANSACCIÓN
    =============================================*/
    static public function mdlIngresarVentaConexion($pdo, $tabla, $datos)
    {
        $sql = "INSERT INTO $tabla (id_usuario, id_cliente, id_vendedor, codigo_venta, vendedor, productos, total, metodo_pago, fecha) 
                VALUES (:id_usuario, :id_cliente, :id_vendedor, :codigo_venta, :vendedor, :productos, :total, :metodo_pago, :fecha)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
        $stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
        $stmt->bindParam(":codigo_venta", $datos["codigo_venta"], PDO::PARAM_STR);
        $stmt->bindParam(":vendedor", $datos["vendedor"], PDO::PARAM_STR);
        $stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
        $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
        $stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);

        // Si la ejecución falla, lanza una excepción para el rollback
        if (!$stmt->execute()) {
            throw new Exception("Falló al guardar la venta: " . implode(" - ", $stmt->errorInfo()));
        }

        return true;
    }
}