<?php
require_once __DIR__ . '/../utils/database.php';

class VentaProducto
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM VentasProductos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM VentasProductos WHERE IdVentaProducto = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO VentasProductos (IdVenta, IdProducto, Cantidad, Precio, Total) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['IdVenta'],
            $data['IdProducto'],
            $data['Cantidad'],
            $data['Precio'],
            $data['Total']
        ]);
    }
}
