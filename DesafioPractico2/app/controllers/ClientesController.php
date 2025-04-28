<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/Controller.php';

class ClientesController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function index()
    {
        $usuarios = $this->model->getAll();
        $this->render('index.php', ['usuarios' => $usuarios]);
    }

    public function registrar()
    {
        $usuarios = $this->model->getAll();
        $this->render('registrar.php');
    }

    public function agregar()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Usa el modelo para buscar si el usuario ya existe
            $usuarioExistente = $this->model->getByUsername($_POST['Username']);
            if ($usuarioExistente) {
                $_SESSION['mensaje_error'] = "El usuario ya existe.";
                header('Location: /Clientes/index');
                exit;
            }
            $this->model->create($_POST);
            $_SESSION['mensaje_exito'] = "Usuario agregado correctamente.";
            header('Location: /Clientes/index');
            exit;
        }
    }

    public function agregarCliente()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Usa el modelo para buscar si el usuario ya existe
            $usuarioExistente = $this->model->getByUsername($_POST['Username']);
            if ($usuarioExistente) {
                $_SESSION['mensaje_error'] = "El usuario ya existe.";
                header('Location: /Clientes/registrar');
                exit;
            }
            $this->model->create($_POST);
            $_SESSION['mensaje_exito'] = "¡Cliente registrado correctamente!";
            header('Location: /');
            exit;
        }
    }

    public function editar()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['IdUsuario'];
            $username = $_POST['Username'];

            // Verifica si el nuevo username ya existe en otro usuario
            $usuarioExistente = $this->model->getByUsername($username);
            if ($usuarioExistente && $usuarioExistente['IdUsuario'] != $id) {
                $_SESSION['mensaje_error'] = "El usuario ya existe.";
                header('Location: /Clientes/index');
                exit;
            }

            // Si la contraseña está vacía, no la actualices
            $data = $_POST;
            if (empty($data['Password'])) {
                unset($data['Password']);
            }
            $this->model->update($id, $data);
            $_SESSION['mensaje_exito'] = "Usuario actualizado correctamente.";
            header('Location: /Clientes/index');
            exit;
        }
    }

    public function eliminar()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['IdUsuario'])) {
            $this->model->delete($_POST['IdUsuario']);
            $_SESSION['mensaje_exito'] = "Cliente eliminado correctamente.";
            header('Location: /Clientes/index');
            exit;
        }
    }

    public function toggleActivo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['IdUsuario'])) {
            $usuario = $this->model->getById($_POST['IdUsuario']);
            if ($usuario) {
                $nuevoEstado = $usuario['Activo'] ? 0 : 1;
                $this->model->setActivo($usuario['IdUsuario'], $nuevoEstado);
                $_SESSION['mensaje_exito'] = $nuevoEstado ? "Cliente activado." : "Cliente desactivado.";
            }
            header('Location: /Clientes/index');
            exit;
        }
    }
}
