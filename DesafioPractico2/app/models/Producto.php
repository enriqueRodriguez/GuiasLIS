<?php
require_once __DIR__ . '/../utils/database.php';

class Producto
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Productos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Productos WHERE IdProducto = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Productos (IdProducto, Nombre, Cantidad, Precio, IdCategoria, IdImagen) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['IdProducto'],
            $data['Nombre'],
            $data['Cantidad'],
            $data['Precio'],
            $data['IdCategoria'],
            $data['IdImagen']
        ]);
    }
}
