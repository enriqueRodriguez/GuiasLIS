<?php
require_once __DIR__ . '/../utils/database.php';

class Imagen
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Imagenes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Imagenes WHERE IdImagen = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($ruta, $idTipoImagen)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Imagenes (Ruta, IdTipoImagen) VALUES (?, ?)");
        return $stmt->execute([$ruta, $idTipoImagen]);
    }
}
