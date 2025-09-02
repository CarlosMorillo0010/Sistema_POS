<?php

class ModelProducts
{
    static public function mdlMostrarProductos($tabla, $item, $valor, $orden)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT p.*, c.categoria FROM $tabla p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria WHERE p.$item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = Connection::connect()->prepare("SELECT p.*, c.categoria FROM $tabla p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY p.$orden");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    static public function mdlMostrarProductosCaja($tabla, $item, $valor, $orden)
    {
        if ($item != null) {
            $stmt = Connection::connect()->prepare("SELECT p.*, c.categoria FROM $tabla p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria WHERE $item = :valor_item ORDER BY p.$orden");
            $stmt->bindParam(":valor_item", $valor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = Connection::connect()->prepare("SELECT p.*, c.categoria FROM $tabla p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY p.$orden");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    static public function mdlIngresarProducto($tabla, $datos)
    {
        $sql = "INSERT INTO $tabla (id_usuario, id_categoria, codigo, marca, modelo, ano, descripcion, stock, ubicacion, imagen, moneda_costo, precio_costo, costo_referencia, porcentaje_ganancia, precio_venta_base, id_impuesto, monto_iva, pvp, pvp_referencia, feregistro) 
                VALUES (:id_usuario, :id_categoria, :codigo, :marca, :modelo, :ano, :descripcion, :stock, :ubicacion, :imagen, :moneda_costo, :precio_costo, :costo_referencia, :porcentaje_ganancia, :precio_venta_base, :id_impuesto, :monto_iva, :pvp, :pvp_referencia, :feregistro)";
        
        try {
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
            $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
            $stmt->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);
            $stmt->bindParam(":modelo", $datos["modelo"], PDO::PARAM_STR);
            $stmt->bindParam(":ano", $datos["ano"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_INT);
            $stmt->bindParam(":ubicacion", $datos["ubicacion"], PDO::PARAM_STR);
            $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt->bindParam(":moneda_costo", $datos["moneda_costo"], PDO::PARAM_STR);
            $stmt->bindParam(":precio_costo", $datos["precio_costo"], PDO::PARAM_STR);
            $stmt->bindParam(":costo_referencia", $datos["costo_referencia"], PDO::PARAM_STR);
            $stmt->bindParam(":porcentaje_ganancia", $datos["porcentaje_ganancia"], PDO::PARAM_STR);
            $stmt->bindParam(":precio_venta_base", $datos["precio_venta_base"], PDO::PARAM_STR);
            $stmt->bindParam(":id_impuesto", $datos["id_impuesto"], PDO::PARAM_INT);
            $stmt->bindParam(":monto_iva", $datos["monto_iva"], PDO::PARAM_STR);
            $stmt->bindParam(":pvp", $datos["pvp"], PDO::PARAM_STR);
            $stmt->bindParam(":pvp_referencia", $datos["pvp_referencia"], PDO::PARAM_STR);
            $stmt->bindParam(":feregistro", $datos["feregistro"], PDO::PARAM_STR);
            return $stmt->execute() ? "ok" : "error";
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }

    /**
     * ===================================================================
     * EDITAR PRODUCTO (CONSULTA SQL COMPLETA Y VERIFICADA)
     * ===================================================================
     */
    static public function mdlEditarProducto($tabla, $datos)
    {
        $sql = "UPDATE $tabla SET 
                id_categoria = :id_categoria, codigo = :codigo, descripcion = :descripcion, marca = :marca, modelo = :modelo, ano = :ano,
                stock = :stock, ubicacion = :ubicacion, imagen = :imagen, moneda_costo = :moneda_costo, precio_costo = :precio_costo, 
                costo_referencia = :costo_referencia, porcentaje_ganancia = :porcentaje_ganancia, precio_venta_base = :precio_venta_base, 
                id_impuesto = :id_impuesto, monto_iva = :monto_iva, pvp = :pvp, pvp_referencia = :pvp_referencia
                WHERE id_producto = :id_producto";

        try {
            $stmt = Connection::connect()->prepare($sql);

            $stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);
            $stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
            $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":marca", $datos["marca"], PDO::PARAM_STR);
            $stmt->bindParam(":modelo", $datos["modelo"], PDO::PARAM_STR);
            $stmt->bindParam(":ano", $datos["ano"], PDO::PARAM_STR);
            $stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_INT);
            $stmt->bindParam(":ubicacion", $datos["ubicacion"], PDO::PARAM_STR);
            $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
            $stmt->bindParam(":moneda_costo", $datos["moneda_costo"], PDO::PARAM_STR);
            $stmt->bindParam(":precio_costo", $datos["precio_costo"], PDO::PARAM_STR);
            $stmt->bindParam(":costo_referencia", $datos["costo_referencia"], PDO::PARAM_STR);
            $stmt->bindParam(":porcentaje_ganancia", $datos["porcentaje_ganancia"], PDO::PARAM_STR);
            $stmt->bindParam(":precio_venta_base", $datos["precio_venta_base"], PDO::PARAM_STR);
            $stmt->bindParam(":id_impuesto", $datos["id_impuesto"], PDO::PARAM_INT);
            $stmt->bindParam(":monto_iva", $datos["monto_iva"], PDO::PARAM_STR);
            $stmt->bindParam(":pvp", $datos["pvp"], PDO::PARAM_STR);
            $stmt->bindParam(":pvp_referencia", $datos["pvp_referencia"], PDO::PARAM_STR);
            
            return $stmt->execute() ? "ok" : implode(" - ", $stmt->errorInfo());

        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }

    static public function mdlEliminarProducto($tabla, $datos)
    {
        $stmt = Connection::connect()->prepare("DELETE FROM $tabla WHERE id_producto = :id_producto");
        $stmt->bindParam(":id_producto", $datos, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlActualizarProducto($tabla, $item1, $valor1, $item2, $valor2)
    {
        $stmt = Connection::connect()->prepare("UPDATE $tabla SET $item1 = :valor1 WHERE $item2 = :valor2");
        $stmt->bindParam(":valor1", $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":valor2", $valor2, PDO::PARAM_STR);
        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlActualizarProductoConexion($pdo, $tabla, $item1, $valor1, $item2, $valor2)
    {
        // Esta consulta usa la conexión PDO que ya está en una transacción
        $stmt = $pdo->prepare("UPDATE $tabla SET $item1 = :valor1 WHERE $item2 = :valor2");
        $stmt->bindParam(":valor1", $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":valor2", $valor2, PDO::PARAM_STR);
        
        if (!$stmt->execute()) {
            // Lanza una excepción si la actualización falla
            throw new Exception("Falló la actualización del producto: " . implode(" - ", $stmt->errorInfo()));
        }
        return true;
    }

    /**
     * ===================================================================
     * NUEVO: BUSCAR PRODUCTOS POR TÉRMINO (CÓDIGO O DESCRIPCIÓN)
     * ===================================================================
     */
    static public function mdlBuscarProductos($tabla, $termino)
    {
        // Preparamos el término de búsqueda para usarlo con LIKE
        $terminoBusqueda = "%" . $termino . "%";

        $stmt = Connection::connect()->prepare(
            "SELECT p.*, c.categoria 
             FROM $tabla p 
             LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
             WHERE p.descripcion LIKE :termino OR p.codigo LIKE :termino
             ORDER BY p.descripcion ASC"
        );

        $stmt->bindParam(":termino", $terminoBusqueda, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}