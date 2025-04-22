<?php
require_once __DIR__ . '/../utils/database.php';

class TipoImagen
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM TipoImagen");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM TipoImagen WHERE IdTipoImagen = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($descripcion)
    {
        $stmt = $this->pdo->prepare("INSERT INTO TipoImagen (Descripcion) VALUES (?)");
        return $stmt->execute([$descripcion]);
    }
}
