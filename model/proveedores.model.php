<?php

require_once "connection.php";

class ModelProveedores
{

    /*=============================================
	MOSTRAR PROVEEDORES
	=============================================*/
    static public function mdlMostrarProveedores($tabla, $item, $valor)
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
        /* $stmt->close(); */
        $stmt = null;
    }

    /*======================================
    CREAR PROVEEDORES
    ======================================**/
    static public function mdlIngresarProveedor($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, codigo, tipo_persona, tipo_documento, documento, nombre, telefono, dias_credito, email, direccion, nota, feregistro) VALUES (:id_usuario, :codigo, :tipo_persona, :tipo_documento, :documento, :nombre, :telefono, :dias_credito, :email, :direccion, :nota, :feregistro)");

        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_persona", $datos["tipo_persona"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":dias_credito", $datos["dias_credito"], PDO::PARAM_INT);
        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
        $stmt->bindParam(":nota", $datos["nota"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        /* $stmt->close(); */
        $stmt = null;
    }

    /*======================================
    EDITAR PROVEEDOR
    ======================================**/
    static public function mdlEditarProveedor($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET id_proveedor = :id_proveedor, tipo_persona = :tipo_persona, tipo_documento = :tipo_documento, documento = :documento, nombre = :nombre, telefono = :telefono, dias_credito = :dias_credito, email = :email, direccion = :direccion, nota = :nota WHERE id_proveedor = :id_proveedor");

        $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_persona", $datos["tipo_persona"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":dias_credito", $datos["dias_credito"], PDO::PARAM_INT);
        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
        $stmt->bindParam(":nota", $datos["nota"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        /* $stmt->close(); */
        $stmt = null;
    }

    /*======================================
  ELIMINAR PROVEEDOR
  ======================================**/
    static public function mdlEliminarProveedor($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_proveedor = :id_proveedor");
        $stmt->bindParam(":id_proveedor", $datos, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        /* $stmt->close(); */
        $stmt = null;
    }
}