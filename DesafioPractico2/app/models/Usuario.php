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
        $usuario = $this->get_query(
            "SELECT * FROM Usuarios WHERE Username = ? AND Activo = 1",
            [$user]
        )[0] ?? null;

        if ($usuario && password_verify($pass, $usuario['Password'])) {
            return $usuario;
        }
        return null;
    }

    public function getByUsername($username)
    {
        return $this->get_query("SELECT * FROM Usuarios WHERE Username = ?", [$username])[0] ?? null;
    }

    public function create($data)
    {
        $hashedPassword = password_hash($data['Password'], PASSWORD_DEFAULT);
        return $this->set_query(
            "INSERT INTO Usuarios (Username, Password, Nombre, Apellido, TipoUsuario, IdImagen) VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['Username'],
                $hashedPassword,
                $data['Nombre'],
                $data['Apellido'],
                $data['TipoUsuario'],
                $data['IdImagen'] ?? null
            ]
        );
    }

    public function update($id, $data)
    {
        $campos = [];
        $params = [];
        if (isset($data['Username'])) {
            $campos[] = "Username = ?";
            $params[] = $data['Username'];
        }
        if (isset($data['Nombre'])) {
            $campos[] = "Nombre = ?";
            $params[] = $data['Nombre'];
        }
        if (isset($data['Apellido'])) {
            $campos[] = "Apellido = ?";
            $params[] = $data['Apellido'];
        }
        if (isset($data['TipoUsuario'])) {
            $campos[] = "TipoUsuario = ?";
            $params[] = $data['TipoUsuario'];
        }
        if (!empty($data['Password'])) {
            $campos[] = "Password = ?";
            $params[] = password_hash($data['Password'], PASSWORD_DEFAULT);
        }
        $params[] = $id;
        $sql = "UPDATE Usuarios SET " . implode(', ', $campos) . " WHERE IdUsuario = ?";
        return $this->set_query($sql, $params);
    }

    public function delete($id)
    {
        return $this->set_query("DELETE FROM Usuarios WHERE IdUsuario = ?", [$id]);
    }

    public function setActivo($id, $activo)
    {
        return $this->set_query("UPDATE Usuarios SET Activo = ? WHERE IdUsuario = ?", [$activo, $id]);
    }
}
