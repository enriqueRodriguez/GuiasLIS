<?php
require_once __DIR__ . '/model.php';

class Usuario extends Model
{
    public function getAll()
    {
        return $this->get_query("SELECT * FROM Usuarios");
    }

    public function getById($id)
    {
        return $this->get_query("SELECT * FROM Usuarios WHERE IdUsuario = ?", [$id])[0] ?? null;
    }

    public function getUser($user, $pass)
    {
        return $this->get_query(
            "SELECT * FROM Usuarios WHERE Username = ? AND Password = ? AND Activo = 1",
            [$user, $pass]
        )[0] ?? null;
    }

    public function create($data)
    {
        return $this->set_query(
            "INSERT INTO Usuarios (Username, Password, Nombre, Apellido, TipoUsuario, IdImagen) VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['Username'],
                $data['Password'],
                $data['Nombre'],
                $data['Apellido'],
                $data['TipoUsuario'],
                $data['IdImagen'] ?? null
            ]
        );
    }
}
