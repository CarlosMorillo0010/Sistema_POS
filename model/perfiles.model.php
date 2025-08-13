<?php

require_once "connection.php";

class ModelPerfiles
{
    /*======================================
     CREAR PERFILES
    ======================================**/
    static public function mdlIngresarPerfil($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, perfil, feregistro) VALUES (:id_usuario, :perfil, :feregistro)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     MOSTRAR PERFILES
    ======================================**/
    static public function mdlMostrarPerfil($tabla, $item, $valor){
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
     EDITAR PERFILES
    ======================================**/
    static public function mdlEditarPerfil($tabla, $datos){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET perfil = :perfil WHERE id_perfil = :id_perfil");
        $stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
        $stmt->bindParam(":id_perfil", $datos["id_perfil"], PDO::PARAM_INT);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     ACTUALIZAR PERFILES
    ======================================**/
    static public function mdlActualizarPerfil($tabla, $item1, $valor1, $item2, $valor2){
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");
        $stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
        $stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

        if($stmt -> execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt -> close();
        $stmt = null;
    }

    /*======================================
     BORRAR PERFILES
    ======================================**/
    static public function mdlBorrarPerfil($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_perfil = :id_perfil");
        $stmt->bindParam(":id_perfil", $datos, PDO::PARAM_INT);
        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}