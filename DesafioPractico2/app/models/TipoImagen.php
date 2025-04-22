<?php
require_once __DIR__ . '/model.php';

class TipoImagen extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM TipoImagen");
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM TipoImagen WHERE IdTipoImagen = ?", [$id])[0] ?? null;
    }

    public function create($descripcion)
    {
        return $this->set_query("INSERT INTO TipoImagen (Descripcion) VALUES (?)", [$descripcion]);
    }
}
