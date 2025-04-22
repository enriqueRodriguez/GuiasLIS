<?php
require_once __DIR__ . '/model.php';

class TipoUsuario extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM TipoUsuario");
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM TipoUsuario WHERE IdTipoUsuario = ?", [$id])[0] ?? null;
    }

    public function create($descripcion)
    {
        return $this->set_query(
            "INSERT INTO TipoUsuario (Descripcion) VALUES (?)",
            [$descripcion]
        );
    }
}
