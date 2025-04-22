<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/Controller.php';

class UsuarioController extends Controller
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Usuario($pdo);
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
}
