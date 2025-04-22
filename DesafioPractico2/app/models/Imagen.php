<?php
require_once __DIR__ . '/model.php';

class Imagen extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM Imagenes");
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM Imagenes WHERE IdImagen = ?", [$id])[0] ?? null;
    }

    public function create($ruta, $idTipoImagen)
    {
        return $this->set_query("INSERT INTO Imagenes (Ruta, IdTipoImagen) VALUES (?, ?)", [$ruta, $idTipoImagen]);
    }
}
