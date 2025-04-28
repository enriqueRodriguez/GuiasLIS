<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Imagen.php';

class AdministracionController extends Controller
{
    private $productoModel;
    private $categoriaModel;
    private $imagenModel;

    public function __construct()
    {
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
        $this->imagenModel = new Imagen();
    }

    public function index()
    {
        session_start();
        $productos = $this->productoModel->getAll();
        $categorias = $this->categoriaModel->getAll();
        $errores = $_SESSION['errores'] ?? [];
        $datosForm = $_SESSION['datos_form'] ?? [];
        $modalActivo = $_SESSION['modal_activo'] ?? '';
        unset($_SESSION['errores'], $_SESSION['datos_form'], $_SESSION['modal_activo']);
        session_write_close();

        $this->render('index.php', [
            'productos' => $productos,
            'categorias' => $categorias,
            'errores' => $errores,
            'datosForm' => $datosForm,
            'modalActivo' => $modalActivo
        ]);
    }

    public function agregar()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $errores = [];

            // Validaciones
            if (empty($data['Nombre'])) $errores[] = "El campo Nombre es requerido";
            if (empty($data['Descripcion'])) $errores[] = "El campo Descripción es requerido";
            if (!is_numeric($data['Precio']) || $data['Precio'] <= 0) $errores[] = "El precio debe ser un número positivo";
            if (!ctype_digit($data['Cantidad']) || $data['Cantidad'] < 0) $errores[] = "Las existencias deben ser un número entero no negativo";
            if (empty($data['IdCategoria'])) $errores[] = "Categoría no válida";
            // Nueva validación: imagen obligatoria
            if (empty($_FILES['Imagen']['name'])) $errores[] = "Debe seleccionar una imagen para el producto";

            // Generar el nuevo ID de producto antes de guardar la imagen
            $nuevoId = $this->productoModel->generarNuevoId();

            // Imagen
            $idImagen = null;
            if (!empty($_FILES['Imagen']['name'])) {
                $tipo = $_FILES['Imagen']['type'];
                if (!in_array($tipo, ['image/jpeg', 'image/png'])) {
                    $errores[] = "Solo se permiten archivos JPG o PNG";
                } else {
                    $ext = pathinfo($_FILES['Imagen']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = $nuevoId . '_' . time() . '.' . $ext;
                    $rutaRelativa = '/images/products/' . $nombreArchivo;
                    $rutaDestino = __DIR__ . '/../../public' . $rutaRelativa;
                    if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaDestino)) {
                        // Guardar imagen usando el modelo Imagen y obtener el ID insertado
                        $idImagen = $this->imagenModel->addImagen($rutaRelativa, 2); // 2 = Productos
                    } else {
                        $errores[] = "Error al guardar la imagen";
                    }
                }
            }

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                $_SESSION['datos_form'] = $data;
                $_SESSION['modal_activo'] = 'agregar';
                header('Location: /Administracion/index');
                exit;
            }

            // Crear producto
            $this->productoModel->create([
                'IdProducto' => $nuevoId,
                'Nombre' => $data['Nombre'],
                'Descripcion' => $data['Descripcion'],
                'Cantidad' => $data['Cantidad'],
                'Precio' => $data['Precio'],
                'IdCategoria' => $data['IdCategoria'],
                'IdImagen' => $idImagen
            ]);
            header('Location: /Administracion/index');
            exit;
        }
    }

    public function editar()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $errores = [];

            // Validaciones
            if (empty($data['Nombre'])) $errores[] = "El campo Nombre es requerido";
            if (empty($data['Descripcion'])) $errores[] = "El campo Descripción es requerido";
            if (!is_numeric($data['Precio']) || $data['Precio'] <= 0) $errores[] = "El precio debe ser un número positivo";
            if (!ctype_digit($data['Cantidad']) || $data['Cantidad'] < 0) $errores[] = "Las existencias deben ser un número entero no negativo";
            if (empty($data['IdCategoria'])) $errores[] = "Categoría no válida";

            $idImagen = $data['IdImagen'] ?? null;
            if (!empty($_FILES['Imagen']['name'])) {
                $tipo = $_FILES['Imagen']['type'];
                if (!in_array($tipo, ['image/jpeg', 'image/png'])) {
                    $errores[] = "Solo se permiten archivos JPG o PNG";
                } else {
                    $ext = pathinfo($_FILES['Imagen']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = $data['IdProducto'] . '_' . time() . '.' . $ext;
                    $rutaRelativa = '/images/products/' . $nombreArchivo;
                    $rutaDestino = __DIR__ . '/../../public' . $rutaRelativa;
                    if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $rutaDestino)) {
                        $idImagen = $this->imagenModel->addImagen($rutaRelativa, 2); // 2 = Productos
                    } else {
                        $errores[] = "Error al guardar la imagen";
                    }
                }
            }

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                $_SESSION['datos_form'] = $data;
                $_SESSION['modal_activo'] = 'editar_' . $data['IdProducto'];
                header('Location: /Administracion/index');
                exit;
            }

            // Actualizar producto
            $this->productoModel->update([
                'IdProducto' => $data['IdProducto'],
                'Nombre' => $data['Nombre'],
                'Descripcion' => $data['Descripcion'],
                'Cantidad' => $data['Cantidad'],
                'Precio' => $data['Precio'],
                'IdCategoria' => $data['IdCategoria'],
                'IdImagen' => $idImagen
            ]);
            header('Location: /Administracion/index');
            exit;
        }
    }

    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['IdProducto'];
            $this->productoModel->delete($id);
            header('Location: /Administracion/index');
            exit;
        }
    }

    public function agregarCategoria()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['Descripcion'])) {
            $this->categoriaModel->create($_POST['Descripcion']);
            $_SESSION['mensaje_exito'] = "Categoría agregada correctamente.";
        }
        header('Location: /Administracion/index');
        exit;
    }

    public function eliminarCategoria()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['IdCategoria'])) {
            $this->categoriaModel->delete($_POST['IdCategoria']);
            $_SESSION['mensaje_exito'] = "Categoría eliminada correctamente.";
        }
        header('Location: /Administracion/index');
        exit;
    }

    public function editarCategoria()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['IdCategoria']) && !empty($_POST['Descripcion'])) {
            $this->categoriaModel->update($_POST['IdCategoria'], $_POST['Descripcion']);
            $_SESSION['mensaje_exito'] = "Categoría modificada correctamente.";
        }
        header('Location: /Administracion/index');
        exit;
    }
}
