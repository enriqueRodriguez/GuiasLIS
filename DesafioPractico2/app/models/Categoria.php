<?php
require_once __DIR__ . '/model.php';

class Categoria extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM Categorias");
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM Categorias WHERE IdCategoria = ?", [$id])[0] ?? null;
    }

    public function create($descripcion)
    {
        return $this->set_query("INSERT INTO Categorias (Descripcion) VALUES (?)", [$descripcion]);
    }
}
