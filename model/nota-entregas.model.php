<?php
require_once "connection.php";

class ModelNotasEntregas
{
    /*=============================================
	MOSTRAR NOTAS DE ENTREGA
	=============================================*/
    static public function mdlMostrarNotaEntrega($tabla, $item, $valor){
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

    /*======================================
     REGISTRO DE NOTAS DE ENTREGA
    ======================================**/
    static public function mdlIngresarNotaEntrega($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, id_proveedor, numero_recepcion, numero_nota, fecha_nota, feregistro_nota, proveedor, productos, total_nota_entrega_compra, feregistro) VALUES (:id_usuario, :id_proveedor, :numero_recepcion, :numero_nota, :fecha_nota, :feregistro_nota, :proveedor, :productos, :total_nota_entrega_compra, :feregistro)");

        $stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_STR);
        $stmt -> bindParam(":numero_recepcion", $datos["numero_recepcion"], PDO::PARAM_STR);
        $stmt -> bindParam(":numero_nota", $datos["numero_nota"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_nota", $datos["fecha_nota"], PDO::PARAM_STR);
        $stmt -> bindParam(":feregistro_nota", $datos["feregistro_nota"], PDO::PARAM_STR);
        $stmt -> bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt -> bindParam(":productos", $datos["listaProductos"], PDO::PARAM_STR);
        $stmt -> bindParam(":total_nota_entrega_compra", $datos["total_compra"], PDO::PARAM_STR);
        $stmt -> bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);

        if ($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     BORRAR NOTA ENTREGA COMPRAR
    =======================================*/
    static public function mdlBorrarNotaEntrega($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_nota_entrega_compra = :id_nota_entrega_compra");
        $stmt->bindParam(":id_nota_entrega_compra", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}