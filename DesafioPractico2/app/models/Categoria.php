<?php
require_once __DIR__ . '/../utils/database.php';

class Categoria
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Categorias");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Categorias WHERE IdCategoria = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($descripcion)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Categorias (Descripcion) VALUES (?)");
        return $stmt->execute([$descripcion]);
    }
}
