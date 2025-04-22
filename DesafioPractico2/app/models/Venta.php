<?php
require_once __DIR__ . '/model.php';

class Venta extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM Ventas");
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM Ventas WHERE IdVenta = ?", [$id])[0] ?? null;
    }

    public function create($idUsuario, $total)
    {
        return $this->set_query(
            "INSERT INTO Ventas (IdUsuario, Total) VALUES (?, ?)",
            [$idUsuario, $total]
        );
    }
}
