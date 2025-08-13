<?php
require_once "connection.php";

class ModelNotasEntregaVentas
{
    /*=============================================
	MOSTRAR NOTAS DE ENTREGA
	=============================================*/
    static public function mdlMostrarNotaEntregaVenta($tabla, $item, $valor){
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
    static public function mdlIngresarNotaEntregaVenta($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, id_cliente, id_vendedor, numero_recepcion, numero_nota, fecha_nota, feregistro_nota, cliente, productos, total_nota_entrega_venta, feregistro) VALUES (:id_usuario, :id_cliente, :id_vendedor, :numero_recepcion, :numero_nota, :fecha_nota, :feregistro_nota, :cliente, :productos, :total_nota_entrega_venta, :feregistro)");

        $stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
        $stmt -> bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
        $stmt -> bindParam(":numero_recepcion", $datos["numero_recepcion"], PDO::PARAM_INT);
        $stmt -> bindParam(":numero_nota", $datos["numero_nota"], PDO::PARAM_INT);
        $stmt -> bindParam(":fecha_nota", $datos["fecha_nota"], PDO::PARAM_STR);
        $stmt -> bindParam(":feregistro_nota", $datos["feregistro_nota"], PDO::PARAM_STR);
        $stmt -> bindParam(":cliente", $datos["cliente"], PDO::PARAM_STR);
        $stmt -> bindParam(":productos", $datos["listarProductos"], PDO::PARAM_STR);
        $stmt -> bindParam(":total_nota_entrega_venta", $datos["total_nota_entrega"], PDO::PARAM_STR);
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
     EDITAR NOTA ENTREGA VENTA
    =======================================*/
    static public function mdlEditarNotaEntregaVenta($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET cliente = :cliente, productos = :productos WHERE id_pedido = :id_pedido");
        $stmt->bindParam(":proveedor", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":productos", $datos["password"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }

    /*======================================
     BORRAR NOTA ENTREGA VENTA
    =======================================*/
    static public function mdlBorrarNotaEntregaVenta($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_nota_entrega = :id_nota_entrega");
        $stmt->bindParam(":id_nota_entrega", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}