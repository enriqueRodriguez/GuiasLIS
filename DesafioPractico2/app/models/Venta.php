<?php
require_once __DIR__ . '/../utils/database.php';

class Venta
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Ventas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Ventas WHERE IdVenta = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($idUsuario, $total)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Ventas (IdUsuario, Total) VALUES (?, ?)");
        return $stmt->execute([$idUsuario, $total]);
    }
}
