<?php

require_once "connection.php";

class ModelVehiculos{
    
    /*=============================================
	 MOSTRAR VEHICULOS
	=============================================*/
    static public function mdlMostrarVehiculos($tabla, $item, $valor)
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
     CREAR VEHICULO
    ======================================**/
    static public function mdlIngresarVehiculo($tabla, $datos){
        
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, marca, modelo, ano, placa, color, serial_chasis, serial_motor, descripcion, feregistro) VALUES (:id_usuario, :marca, :modelo, :ano, :placa, :color, :serial_chasis, :serial_motor, :descripcion, :feregistro)");
        
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);
        $stmt->bindParam(":modelo", $datos["modelo"], PDO::PARAM_STR);
        $stmt->bindParam(":ano", $datos["ano"], PDO::PARAM_STR);
        $stmt->bindParam(":placa", $datos["placa"], PDO::PARAM_STR);
        $stmt->bindParam(":color", $datos["color"], PDO::PARAM_STR);
        $stmt->bindParam(":serial_chasis", $datos["serial_chasis"], PDO::PARAM_INT);
        $stmt->bindParam(":serial_motor", $datos["serial_motor"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
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
     EDITAR VEHICULO
    ======================================**/
    static public function mdlEditarVehiculo($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET id_vehiculo = :id_vehiculo, marca = :marca, modelo = :modelo, ano = :ano, placa = :placa, color = :color, serial_chasis = :serial_chasis, serial_motor = :serial_motor, descripcion = :descripcion WHERE id_vehiculo = :id_vehiculo");
        
        $stmt->bindParam(":id_vehiculo", $datos["id_vehiculo"], PDO::PARAM_INT);
        $stmt->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);
        $stmt->bindParam(":modelo", $datos["modelo"], PDO::PARAM_STR);
        $stmt->bindParam(":ano", $datos["ano"], PDO::PARAM_INT);
        $stmt->bindParam(":placa", $datos["placa"], PDO::PARAM_STR);
        $stmt->bindParam(":color", $datos["color"], PDO::PARAM_STR);
        $stmt->bindParam(":serial_chasis", $datos["serial_chasis"], PDO::PARAM_STR);
        $stmt->bindParam(":serial_motor", $datos["serial_motor"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
    BORRAR VEHICULO
    ======================================**/
    static public function mdlBorrarVehiculo($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_vehiculo = :id_vehiculo");
        $stmt->bindParam(":id_vehiculo", $datos, PDO::PARAM_INT);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}