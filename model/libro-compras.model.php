<?php

/*======================================
CONNECTION DATABASE
======================================**/
require_once "connection.php";

class ModelLibroCompras
{
    /*======================================
    REGISTRO DE LIBRO COMPRAS
    ======================================**/
    static public function mdlIngresarLibroCompra($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare(
            "INSERT INTO $tabla(id_usuario, numfactura, descripcion, proveedor, dias_credito, rif, numcontrol, tipodoc, monto, iva, total, metodo, fecha, estado, observacion, feregistro) VALUES (:id_usuario, :numfactura, :descripcion, :proveedor, :dias_credito, :rif, :numcontrol, :tipodoc, :monto, :iva, :total, :metodo, :fecha, :estado, :observacion, :feregistro)"
        );

        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":numfactura", $datos["numfactura"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":dias_credito", $datos["dias_credito"], PDO::PARAM_INT);
        $stmt->bindParam(":rif", $datos["rif"], PDO::PARAM_STR);
        $stmt->bindParam(":numcontrol", $datos["numcontrol"], PDO::PARAM_STR);
        $stmt->bindParam(":tipodoc", $datos["tipodoc"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
        $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
        $stmt->bindParam(":metodo", $datos["metodo"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     MOSTRAR LIBRO COMPRAS
    ======================================**/
    static public function mdlMostrarLibroCompra($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }

    static public function mdlObtenerUltimoId($tabla)
    {
        $stmt = Connection::connect()->query("SELECT MAX(id) as id FROM $tabla");
        return $stmt->fetch()["id"];
    }

    /*======================================
     EDITAR LIBRO COMPRAS
    ======================================**/
    static public function mdlEditarLibroCompra($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare(
            "UPDATE $tabla SET numfactura = :numfactura, descripcion = :descripcion, proveedor = :proveedor, dias_credito = :dias_credito, rif = :rif, numcontrol = :numcontrol, tipodoc = :tipodoc, monto = :monto, iva = :iva, total = :total, metodo = :metodo, fecha = :fecha, estado = :estado, observacion = :observacion WHERE id = :id"
        );
        $stmt->bindParam(":numfactura", $datos["numfactura"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":dias_credito", $datos["dias_credito"], PDO::PARAM_INT);
        $stmt->bindParam(":rif", $datos["rif"], PDO::PARAM_STR);
        $stmt->bindParam(":numcontrol", $datos["numcontrol"], PDO::PARAM_STR);
        $stmt->bindParam(":tipodoc", $datos["tipodoc"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
        $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
        $stmt->bindParam(":metodo", $datos["metodo"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*=============================================
    BORRAR LIBRO COMPRA CON TRANSACCIÓN (MÉTODO RECOMENDADO)
    =============================================*/
    static public function mdlBorrarLibroCompra($idLibroCompra)
    {

        $db = Connection::connect();

        try {
            $db->beginTransaction();

            $stmt1 = $db->prepare("DELETE FROM cuentas_pagar WHERE id_libro_compra = :id_libro_compra");
            $stmt1->bindParam(":id_libro_compra", $idLibroCompra, PDO::PARAM_INT);
            $stmt1->execute();
            // Nota: No nos importa si esta consulta borra 0 filas (si no había cuenta por pagar), no dará error.

            // Borramos el registro de libro_compras
            $stmt2 = $db->prepare("DELETE FROM libro_compras WHERE id = :id");
            $stmt2->bindParam(":id", $idLibroCompra, PDO::PARAM_INT);
            $stmt2->execute();

            // Si ambas consultas se ejecutaron sin lanzar una excepción, confirmamos los cambios.
            $db->commit();

            return "ok";

        } catch (Exception $e) {

            // Si ocurrió cualquier error en el bloque try, deshacemos todos los cambios.
            $db->rollBack();

            return "error";
        } finally {
            // Cerramos la conexión
            $stmt1 = null;
            $stmt2 = null;
            $db = null;
        }
    }
}