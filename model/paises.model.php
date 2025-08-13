<?php

require_once "connection.php";

class ModelPaises
{
    /*=============================================
        MOSTRAR PAISES
        =============================================*/
    static public function mdlMostrarPaises($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY pais ASC ");
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

    /*=============================================
       MOSTRAR ESTADOS
       =============================================*/
    static public function mdlMostrarEstados($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY estado ASC ");
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

    /*=============================================
       MOSTRAR CIUDADES
       =============================================*/
    static public function mdlMostrarCiudades($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY ciudad ASC ");
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

    /*=============================================
       MOSTRAR MUNICIPIOS
       =============================================*/
    static public function mdlMostrarMunicipios($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY municipio ASC ");
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
}