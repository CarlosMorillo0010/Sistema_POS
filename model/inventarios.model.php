<?php

/**=====================================
CONNECTION DATA BASE
======================================**/
require_once "connection.php";

class ModelInventarios
{
    /*======================================
     MOSTRAR PRODUCTOS
    ======================================**/
    static public function mdlMostrarProductos($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::  connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY codigo DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Connection::  connect()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->close();
        $stmt = null;
    }
}