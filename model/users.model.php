<?php

/*======================================
 CONNECTION DATA BASE
======================================**/
require_once "connection.php";

class ModelUsers
{
    /*======================================
     MOSTRAR USUARIOS
    ======================================**/
    static public function mdlMostrarUsuarios($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Connection::  connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
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

    /*======================================
     REGISTRO USUARIOS
    ======================================**/
    static public function mdlIngresarUsuario($tabla, $datos)
    {
        $stmt = Connection::  connect()->prepare("INSERT INTO $tabla(id_usuario_adm, nacionalidad, documento, nombre, password, telefono, correo_electronico, genero, perfil, feregistro) VALUES (:id_usuario_adm, :nacionalidad, :documento, :nombre, :password, :telefono, :correo_electronico, :genero, :perfil, :feregistro)");
        $stmt->bindParam(":id_usuario_adm", $datos["id_usuario_adm"], PDO::PARAM_STR);
        $stmt->bindParam(":nacionalidad", $datos["nacionalidad"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":correo_electronico", $datos["correo"], PDO::PARAM_STR);
        $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     EDITAR USUARIOS
    ======================================**/
    static public function mdlEditarUsuario($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET nombre = :nombre, documento = :documento, password = :password, telefono = :telefono, correo_electronico = :correo_electronico, genero = :genero, perfil = :perfil WHERE documento = :documento");
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt->bindParam(":correo_electronico", $datos["correo"], PDO::PARAM_STR);
        $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     ACTUALIZAR USUARIOS
    ======================================**/
    static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");
        $stmt->bindParam(":" . $item1, $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":" . $item2, $valor2, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     BORRAR USUARIOS
    ======================================**/
    static public function mdlBorrarUsuario($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_usuario = :id_usuario");
        $stmt->bindParam(":id_usuario", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}