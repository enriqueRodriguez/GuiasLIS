<?php
require_once __DIR__ . '/model.php';

class VentaProducto extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM VentasProductos");
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM VentasProductos WHERE IdVentaProducto = ?", [$id])[0] ?? null;
    }

    public function create($data)
    {
        return $this->set_query(
            "INSERT INTO VentasProductos (IdVenta, IdProducto, Cantidad, Precio, Total) VALUES (?, ?, ?, ?, ?)",
            [
                $data['IdVenta'],
                $data['IdProducto'],
                $data['Cantidad'],
                $data['Precio'],
                $data['Total']
            ]
        );
    }
}
