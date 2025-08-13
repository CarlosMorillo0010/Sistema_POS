<?php
require_once "connection.php";

class ModelFacturasCompras
{
    /*=============================================
	MOSTRAR FACTURA COMPRA
	=============================================*/
    static public function mdlMostrarFacturaCompra($tabla, $item, $valor){
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
    static public function mdlIngresarFacturaCompra($tabla, $datos){
        $stmt = Connection::connect()->prepare("INSERT INTO $tabla(id_usuario, id_proveedor, codigo, numero_pedido, fecha_emision, fecha_vencimiento, proveedor, productos, feregistro) VALUES (:id_usuario, :id_proveedor, :codigo, :numero_pedido, :fecha_emision, :fecha_vencimiento, :proveedor, :productos, :feregistro)");

        $stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_STR);
        $stmt -> bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_STR);
        $stmt -> bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
        $stmt -> bindParam(":numero_pedido", $datos["numero_pedido"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_emision", $datos["fecha_emision"], PDO::PARAM_STR);
        $stmt -> bindParam(":fecha_vencimiento", $datos["fecha_vencimiento"], PDO::PARAM_STR);
        $stmt -> bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
        $stmt -> bindParam(":productos", $datos["listaProductos"], PDO::PARAM_STR);
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
     EDITAR FACTURA DE COMPRA
    =======================================*/
    static public function mdlEditarFacturaCompra($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET proveedor = :proveedor, productos = :productos WHERE id_factura_compra = :id_factura_compra");
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
     BORRAR FACTURA DE COMPRA
    =======================================*/
    static public function mdlBorrarFacturaCompra($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_factura_compra = :id_factura_compra");
        $stmt->bindParam(":id_factura_compra", $datos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
}