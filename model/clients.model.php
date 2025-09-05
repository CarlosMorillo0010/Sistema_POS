<?php

require_once "connection.php";

class ModelClients{
    
    /*=============================================
	 MOSTRAR CLIENTES
	=============================================*/
    static public function mdlMostrarClientes($tabla, $item, $valor)
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

    /*======================================
     CREAR CLIENTE
    ======================================**/
    static public function mdlIngresarClientes($tabla, $datos){
        
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, tipo_documento, documento, nombre, telefono, direccion, feregistro) VALUES (:id_usuario, :tipo_documento, :documento, :nombre, :telefono, :direccion, :feregistro)");
        
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
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
     EDITAR CLIENTE
    ======================================**/
    static public function mdlEditarCliente($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET tipo_documento = :tipo_documento, documento = :documento, nombre = :nombre, telefono = :telefono, direccion = :direccion WHERE id = :id");
        
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
   ELIMINAR CLIENTE
  ======================================**/
    static public function mdlEliminarCliente($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*=============================================
	 ACTUALIZAR CLIENTES
	=============================================*/
    static public function mdlActualizarCliente($tabla, $item1, $valor1, $valor)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");
        $stmt->bindParam(":" . $item1, $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":id", $valor, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*=============================================
    ACTUALIZAR CLIENTE DENTRO DE UNA TRANSACCIÓN
    =============================================*/
    static public function mdlActualizarClienteConexion($pdo, $tabla, $item1, $valor1, $valor)
    {
        // Prepara la consulta usando el objeto PDO de la transacción
        $stmt = $pdo->prepare("UPDATE $tabla SET $item1 = :item1 WHERE id = :id");

        $stmt->bindParam(":item1", $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":id", $valor, PDO::PARAM_INT);

        // Si la ejecución falla, lanza una excepción para que la transacción haga rollback
        if (!$stmt->execute()) {
            throw new Exception("Falló la actualización del cliente: " . implode(" - ", $stmt->errorInfo()));
        }

        return true;
    }
}