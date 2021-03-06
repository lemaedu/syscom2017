<?php

require_once 'model/ConexDB/ConexPDO.php';

class Opciones {

    var $id_producto;
    var $descripcion;
    var $cantidad;
    var $valor_compra;
    var $descuento;
    var $total_compra;
    var $proveedor;
    var $valor_real_compra;
    var $stok;
    var $precio_venta;
    var $estado;

    function __construct() {
        
    }

    function getStok() {
        return $this->stok;
    }

    function setStok($stok) {
        $this->stok = $stok;
    }

    function getId_producto() {
        return $this->id_producto;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getValor_compra() {
        return $this->valor_compra;
    }

    function getDescuento() {
        return $this->descuento;
    }

    function getTotal_compra() {
        return $this->total_compra;
    }

    function getProveedor() {
        return $this->proveedor;
    }

    function getValor_real_compra() {
        return $this->valor_real_compra;
    }

    function getPrecio_venta() {
        return $this->precio_venta;
    }

    function getEstado() {
        return $this->estado;
    }

    function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setValor_compra($valor_compra) {
        $this->valor_compra = $valor_compra;
    }

    function setDescuento($descuento) {
        $this->descuento = $descuento;
    }

    function setTotal_compra($total_compra) {
        $this->total_compra = $total_compra;
    }

    function setProveedor($proveedor) {
        $this->proveedor = $proveedor;
    }

    function setValor_real_compra($valor_real_compra) {
        $this->valor_real_compra = $valor_real_compra;
    }

    function setPrecio_venta($precio_venta) {
        $this->precio_venta = $precio_venta;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    public function f_total_compra() {
        return ($this->cantidad * $this->valor_compra);
    }

    public function f_compra_real() {
        return ((($this->f_total_compra()) - ($this->f_total_compra() * $this->descuento)) / $this->cantidad);
    }

    public function f_precio_venta() {
        return (($this->f_compra_real()) + ($this->f_compra_real() * 0.3));
    }

    public function create() {
        $conex = new conexionMYSQL();
        if ($conex->conectar()) {
            $a = $this->f_total_compra();
            $b = $this->f_compra_real();
            $c = $this->f_precio_venta();
            $sql = "Insert Into tb_productos1(descripcion,cantidad,valor_compra,descuento,total_compra,proveedor,real_compra,stok,precio_venta)
                    VALUES('$this->descripcion',$this->cantidad, $this->valor_compra,$this->descuento, 
                            $a,'$this->proveedor',$b,$this->cantidad,$c)";
            $result = mysql_query($sql);
            if (!$result) {//Si es distinto de 1 ; si no se ejecuta                
                return false;
            }
            $conex->desconectar();
            return true;
        } else {
            echo 'ERROR DE CONEXION CON DB';
        }
    }

    public function update() {
        $conex = new conexionMYSQL();
        if ($conex->conectar()) {
            try {
                $sql = "UPDATE tb_productos1 SET descripcion='$this->descripcion',
                        stok='$this->stok',precio_venta='$this->precio_venta',
                        estado='$this->estado'
                         WHERE id_producto='$this->id_producto'";
                $result = mysql_query($sql);
                if ($result > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $ex) {
                echo "No se pudo pudieron obtener los datos Error" . $ex->getMessage();
            }
        } else {
            echo 'ERROR DE CONEXION CON DB';
        }
    }

    public function delete() {
        $conex = new conexionMYSQL();
        if ($conex->conectar()) {
            try {
                $sql = "DELETE FROM  tb_productos WHERE id_producto='$this->id_producto'";
                if (mysql_query($sql) > 0) {//Si es distinto de 1 ; si no se ejecuta                
                    return true;
                } else {
                    return false;
                }
                $conex->desconectar();
            } catch (Exception $ex) {
                echo "No se pudo pudieron obtener los datos Error" . $ex->getMessage();
            }
        } else {
            echo 'ERROR DE CONEXION CON DB';
        }
    }

       function retrive() {
        $conex = new ConexPDO();
        if ($conex) {
            $sql = "SELECT g.grupo,opcion,m.contenido FROM tb_menu as m
                    join tb_grupos as g on g.id_grupo=m._id_grupo";
            $result = $conex->query($sql);
                        
            if ($result) {
                return $result;
            } else {
                return false;
            }
            mysql_free_result($result);
            $conex->desconectar();
        } else {
            echo 'ERROR CON DB';
            return false;
        }
    }

    public function search() {
        $conex = new conexionMYSQL();
        if ($conex->conectar()) {

            $sql = "SELECT * FROM tb_productos1
                    WHERE descripcion LIKE '%$this->descripcion%'";
            $result = mysql_query($sql);
            $numero_filas = mysql_num_rows($result);
            if (($result) && ($numero_filas > 0)) {
                while ($lista_temporal = mysql_fetch_assoc($result)) {
                    $lista_resultados[] = $lista_temporal;
                }
                return $lista_resultados;
            } else {
                return false;
            }
            mysql_free_result($result);
            $conex->desconectar();
        } else {
            echo 'ERROR CON DB';
            return false;
        }
    }

}
