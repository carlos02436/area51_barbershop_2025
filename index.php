<?php
session_start();
require_once __DIR__ . '/config/database.php'; // Conexión PDO

// ==================== AUTOLOAD MODELOS ====================
require_once __DIR__ . '/app/models/Cita.php';
require_once __DIR__ . '/app/models/Servicio.php';
require_once __DIR__ . '/app/models/Barbero.php';
require_once __DIR__ . '/app/models/Dashboard.php';
require_once __DIR__ . '/app/controllers/CitasController.php';
require_once __DIR__ . '/app/controllers/ServicioController.php';

$servicioController = new ServicioController();
$citasController = new CitasController($db);
$barberoModel = new Barbero();
$servicioModel = new Servicio();

// ==================== LÓGICA DE LOGIN ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'], $_POST['password'])) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM administradores WHERE usuario = :usuario LIMIT 1");
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin && $admin['password'] === $password) { // En producción usa password_hash
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_usuario'] = $admin['usuario'];
        header("Location: index.php?page=panel");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}

// =========== DEFINIR PÁGINA PRINCIPAL ==============
$page = $_GET['page'] ?? 'home'; // Por defecto 'home'

// ==========  ENDPOINT AJAX PARA CHECK_SESSION ======
if ($page === 'check_session') {
    header('Content-Type: application/json');
    $logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    echo json_encode(['logged_in' => $logged_in]);
    exit();
}

// ==================== HEADER =======================
include __DIR__ . '/app/views/plantillas/header.php';

// ==================== ENRUTADOR ====================
switch ($page) {
    case 'home':
        include __DIR__ . '/app/views/home.php';
        break;

    case 'login':
        require __DIR__ . '/app/views/login.php';
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?page=login");
        exit();

    case 'dashboard':
        require __DIR__ . '/app/views/dashboard.php';
        break;

    case 'panel':
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }
        require __DIR__ . '/app/views/panel.php';
        break;

    case 'citas':
        $citasController->index();
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $citasController->guardar(); 
        } else {
            $citasController->crearFormulario(); 
        }
        break;


    case 'editar_cita':
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $citasController->actualizar($id, $_POST);
        } else {
            $citasController->editarFormulario($id);
        }
        break;

    case 'eliminar_cita':
        $id = $_GET['id'] ?? null;
        $citasController->eliminar($id);
        break;

    case 'servicios':
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }
        require __DIR__ . '/app/views/servicios/servicios.php';
        break;

    case 'crear_servicio':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicioController->crearServicio([
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'img_servicio' => $_FILES['img_servicio'] ?? null,
                'observacion' => $_POST['observacion'] ?? null
            ]);
            header('Location: index.php?page=servicios');
            exit();
        } else {
            require __DIR__ . '/app/views/servicios/crear_servicio.php';
        }
        break;

    case 'editar_servicio':
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=servicios');
            exit();
        }
        // Solo incluimos la vista, la vista maneja POST
        require __DIR__ . '/app/views/servicios/editar_servicio.php';
        break;

    case 'eliminar_servicio':
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $servicio = $servicioController->mostrarServicio($id);
            if ($servicio && $servicio['img_servicio']) {
                $rutaImagen = __DIR__ . '/uploads/' . $servicio['img_servicio'];
                if (file_exists($rutaImagen)) unlink($rutaImagen);
            }
            $servicioController->eliminarServicio($id);
        }
        header('Location: index.php?page=servicios');
        exit();

    case 'barberos':
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }
        require __DIR__ . '/app/views/barberos/barberos.php';
        break;

    case 'administradores':
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }
        require __DIR__ . '/app/views/administradores/administradores.php';
        break;

    case 'reportes':
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: index.php?page=login");
            exit;
        }
        require __DIR__ . '/app/views/reportes/reportes.php';
        break;

    default:
        echo "<section class='container py-5 text-center'>
                <h2 class='text-danger'>404 - Página no encontrada</h2>
                <p>Lo sentimos, la página que buscas no existe.</p>
                <a href='index.php' class='btn btn-primary mt-3'>Volver al inicio</a>
              </section>";
        break;
}

// ==================== FOOTER ====================
include __DIR__ . '/app/views/plantillas/footer.php';