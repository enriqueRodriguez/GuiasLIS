<?php
require_once __DIR__ . '/model.php';

class Producto extends Model
{
    public function getAll()
    {
        return $this->get_query(
            "SELECT p.*, i.Ruta, c.Descripcion
             FROM Productos p
             INNER JOIN Imagenes i ON p.IdImagen = i.IdImagen
             INNER JOIN Categorias c ON p.IdCategoria = c.IdCategoria"
        );
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM Productos WHERE IdProducto = ?", [$id])[0] ?? null;
    }

    public function create($data)
    {
        return $this->set_query(
            "INSERT INTO Productos (IdProducto, Nombre, Cantidad, Precio, IdCategoria, IdImagen) VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['IdProducto'],
                $data['Nombre'],
                $data['Cantidad'],
                $data['Precio'],
                $data['IdCategoria'],
                $data['IdImagen']
            ]
        );
    }
}
