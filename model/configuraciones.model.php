<?php
require_once "connection.php";

class ModelConfiguraciones {

    static public function mdlMostrarConfiguracion($tabla) {
        $stmt = Connection::connect()->prepare("SELECT * FROM $tabla LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function mdlGuardarConfiguracion($tabla, $datos) {
        if (!empty($datos["id"])) {
            $sql = "UPDATE $tabla SET nombre = :nombre, rif = :rif, telefono = :telefono, email = :email, direccion = :direccion, logo = :logo, iva = :iva, igtf = :igtf, moneda_principal_id = :moneda_principal_id, id_usuario_mod = :id_usuario_mod, fecha_mod = :fecha_mod WHERE id = :id";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        } else {
            $sql = "INSERT INTO $tabla (nombre, rif, telefono, email, direccion, logo, iva, igtf, moneda_principal_id, id_usuario_mod, fecha_mod) VALUES (:nombre, :rif, :telefono, :email, :direccion, :logo, :iva, :igtf, :moneda_principal_id, :id_usuario_mod, :fecha_mod)";
            $stmt = Connection::connect()->prepare($sql);
        }

        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":rif", $datos["rif"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
        $stmt->bindParam(":logo", $datos["logo"], PDO::PARAM_STR);
        $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
        $stmt->bindParam(":igtf", $datos["igtf"], PDO::PARAM_STR);
        $stmt->bindParam(":moneda_principal_id", $datos["moneda_principal_id"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario_mod", $datos["id_usuario_mod"], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_mod", $datos["fecha_mod"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
}