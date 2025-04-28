<?php
require_once __DIR__ . '/model.php';

class Imagen extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM Imagenes");
    }

    public function getImagenProducto($id)
    {
        return $this->get_query("SELECT * FROM Imagenes WHERE IdImagen = ?", [$id])[0] ?? null;
    }

    public function getImagenesCarrousel($idTipoImagen)
    {
        return $this->get_query("SELECT * FROM Imagenes WHERE IdTipoImagen = ?", [$idTipoImagen]);
    }

    public function addImagen($ruta, $idTipoImagen)
    {
        $this->open_db();
        $stmt = $this->conn->prepare("INSERT INTO Imagenes (Ruta, IdTipoImagen) VALUES (?, ?)");
        $stmt->execute([$ruta, $idTipoImagen]);
        $lastId = $this->conn->lastInsertId();
        $this->close_db();
        return $lastId;
    }

    public function updateImagen($ruta, $id)
    {
        return $this->set_query("INSERT INTO Imagenes (Ruta, IdTipoImagen) VALUES (?, ?)", [$ruta, $id]);
    }
}
