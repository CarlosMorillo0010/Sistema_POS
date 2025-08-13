<?php

require_once "connection.php";

class ModelCategories{

    /**=====================================
    CREAR CATEGORIAS
    ======================================**/
    static public function mdlIngresarCategoria($tabla, $datos){
	$stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, categoria, imagen, feregistro) VALUES (:id_usuario, :categoria, :imagen, :feregistro)");
	    $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt->bindParam(":categoria", $datos["categoria"], PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
        $stmt->bindParam(":feregistro", $datos["fecha"], PDO::PARAM_STR);
    	if($stmt->execute()){
    		return "ok";
    	}else{
    		return "error";
    	}
    	$stmt->close();
    	$stmt = null;
    }

    /**=====================================
    MOSTRAR CATEGORIAS
    ======================================**/
	static public function mdlMostrarCategoria($tabla, $item, $valor){
		if($item != null){
			$stmt = Connection::connect()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			 $stmt = Connection::connect()->prepare("SELECT * FROM $tabla");
			 $stmt -> execute();
			 return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}

    /**=====================================
    EDITAR CATEGORIAS
    ======================================**/
    static public function mdlEditarCategoria($tabla, $datos){
    $stmt = Connection::connect()->prepare("UPDATE $tabla SET categoria = :categoria  WHERE id_categoria = :id_categoria");
    $stmt->bindParam(":categoria", $datos["categoria"], PDO::PARAM_STR);
    $stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_STR);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**=====================================
     BORRAR CATEGORIAS
    ======================================**/
    static public function mdlBorrarCategoria($tabla, $datos){
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_categoria = :id_categoria");
        $stmt->bindParam(":id_categoria", $datos, PDO::PARAM_INT);
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /**
     * ===================================================================
     * MOSTRAR SOLO CATEGORÍAS QUE TIENEN PRODUCTOS ASOCIADOS
     * ===================================================================
     */
    static public function mdlMostrarCategoriasConProductos()
    {
        $sql = "SELECT c.id_categoria, c.categoria 
                FROM categorias c
                INNER JOIN productos p ON c.id_categoria = p.id_categoria
                GROUP BY c.id_categoria, c.categoria
                ORDER BY c.categoria ASC";
        
        try {
            $stmt = Connection::connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            
            error_log("Error en mdlMostrarCategoriasConProductos: " . $e->getMessage());
            return [];
        }
    }
}