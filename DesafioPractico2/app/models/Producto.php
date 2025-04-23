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

    public function countAll()
    {
        $result = $this->get_query("SELECT COUNT(*) as total FROM Productos");
        return $result[0]['total'] ?? 0;
    }

    public function getPaged($inicio, $limite)
    {
        $inicio = (int)$inicio;
        $limite = (int)$limite;
        return $this->get_query(
            "SELECT p.*, i.Ruta, c.Descripcion
             FROM Productos p
             INNER JOIN Imagenes i ON p.IdImagen = i.IdImagen
             INNER JOIN Categorias c ON p.IdCategoria = c.IdCategoria
             LIMIT $inicio, $limite"
        );
    }

    public function buscarPorNombre($nombre)
    {
        return $this->get_query(
            "SELECT p.*, i.Ruta, c.Descripcion
             FROM Productos p
             INNER JOIN Imagenes i ON p.IdImagen = i.IdImagen
             INNER JOIN Categorias c ON p.IdCategoria = c.IdCategoria
             WHERE p.Nombre LIKE ?",
            ['%' . $nombre . '%']
        );
    }

    public function buscarPorNombreYCategoria($nombre, $categoria)
    {
        $sql = "SELECT p.*, i.Ruta, c.Descripcion
                FROM Productos p
                INNER JOIN Imagenes i ON p.IdImagen = i.IdImagen
                INNER JOIN Categorias c ON p.IdCategoria = c.IdCategoria
                WHERE 1";
        $params = [];
        if ($nombre !== '') {
            $sql .= " AND p.Nombre LIKE ?";
            $params[] = '%' . $nombre . '%';
        }
        if ($categoria !== '') {
            $sql .= " AND p.IdCategoria = ?";
            $params[] = $categoria;
        }
        return $this->get_query($sql, $params);
    }

    public function getCategorias()
    {
        return $this->get_query("SELECT * FROM Categorias");
    }
}
