<?php
require_once __DIR__ . '/../utils/database.php';

class Usuario
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Usuarios WHERE IdUsuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Usuarios (Username, Password, Nombre, Apellido, TipoUsuario, IdImagen) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['Username'],
            $data['Password'],
            $data['Nombre'],
            $data['Apellido'],
            $data['TipoUsuario'],
            $data['IdImagen'] ?? null
        ]);
    }
}
