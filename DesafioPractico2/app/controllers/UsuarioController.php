<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/Controller.php';

class UsuarioController extends Controller
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

    public function show($id)
    {
        $usuario = $this->model->getById($id);
        $this->render('show.php', ['usuario' => $usuario]);
    }

    public function store($data)
    {
        $this->model->create($data);
    }

    public function login()
    {
        session_start();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuario = $this->model->getUser($username, $password);

            if ($usuario) {
                $_SESSION['id_usuario'] = $usuario['IdUsuario'];
                $_SESSION['username'] = $usuario['Username'];
                $_SESSION['nombre'] = $usuario['Nombre'];
                $_SESSION['apellido'] = $usuario['Apellido'];
                $_SESSION['tipo_usuario'] = (int)$usuario['TipoUsuario'];
                $_SESSION['id_imagen'] = $usuario['IdImagen'];

                // Si es AJAX, responde con JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit;
                }

                header('Location: /');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos';

                // Si es AJAX, responde con JSON
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => $error]);
                    exit;
                }

                $this->render('Index/index.php', ['error' => $error]);
                exit;
            }
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        // Si es AJAX, responde con JSON y no redirige
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }

        header('Location: /');
        exit;
    }

    public function agregar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create($_POST);
            header('Location: /Usuario/index');
            exit;
        }
    }

    public function editar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $id = $data['IdUsuario'];
            unset($data['IdUsuario']);
            // Si la contraseña está vacía, no la actualices
            if (empty($data['Password'])) {
                unset($data['Password']);
            }
            $this->model->update($id, $data);
            header('Location: /Usuario/index');
            exit;
        }
    }

    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['IdUsuario'])) {
            $this->model->delete($_POST['IdUsuario']);
            header('Location: /Usuario/index');
            exit;
        }
    }
}
