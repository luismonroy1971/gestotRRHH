<?php
set_include_path(__DIR__ . '/../');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload manual (si no estás usando Composer)
require_once 'Libs\Database.php';
require_once 'Libs\Response.php';
require_once 'Controllers\AuthController.php';
require_once 'Controllers\ColaboradoresController.php';
require_once 'Controllers\DocumentosController.php';
require_once 'Controllers\LegajoController.php';
require_once 'Controllers\UsuariosController.php';
require_once 'Controllers\DashboardController.php';


// Configuración de la base de datos
//$db = new Libs\Database(); // Instancia tu clase de base de datos si es necesario

// Obtener la ruta solicitada
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace($basePath, '', $path);
$path = rtrim($path, '/');
$path = ltrim($path, '/'); // Eliminamos la barra inicial

require_once __DIR__ . '/Models/Usuario.php';
require_once __DIR__ . '/Models/Colaborador.php';
require_once __DIR__ . '/Models/DocumentoIdentidad.php';
require_once __DIR__ . '/Models/Documentos.php';
require_once __DIR__ . '/Models/Legajo.php';
// Aquí se cargan el resto de tus modelos o librerías manualmente

// Manejar las rutas
switch ($path) {
    case '':
        // Mostrar el dashboard si el usuario está autenticado, si no, redirige a login
        Controllers\DashboardController::index();
        break;
    
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\AuthController::login(); // Llamar al método login del controlador
        } else {
            require_once __DIR__ . '/Views/Auth/Login.php'; // Mostrar el formulario de login
        }
        break;

    case 'logout':
        Controllers\AuthController::logout(); // Cerrar sesión
        break;

    // Rutas para Colaboradores
    case 'colaboradores':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            Controllers\ColaboradoresController::index(); // Listar colaboradores
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\ColaboradoresController::create(); // Crear un colaborador
        }
        break;
    
    case 'colaboradores/search':
        Controllers\ColaboradoresController::search(); // Buscar colaboradores
        break;
    
    case 'colaboradores/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\ColaboradoresController::update(); // Actualizar colaborador
        }
        break;
    
    case 'colaboradores/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\ColaboradoresController::delete(); // Eliminar colaborador
        }
        break;
    
    // Rutas para Documentos
    case 'documentos':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            Controllers\DocumentosController::getAll(); // Listar documentos
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\DocumentosController::create(); // Crear un documento
        }
        break;

    case 'documentos/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\DocumentosController::update(); // Actualizar documento
        }
        break;

    case 'documentos/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\DocumentosController::delete(); // Eliminar documento
        }
        break;

    // Rutas para Legajos
    case 'legajo':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            Controllers\LegajoController::getAll(); // Listar legajos
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\LegajoController::create(); // Crear legajo
        }
        break;

    case 'legajo/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\LegajoController::update(); // Actualizar legajo
        }
        break;

    case 'legajo/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\LegajoController::delete(); // Eliminar legajo
        }
        break;

    // Rutas para Usuarios
    case 'usuarios':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            Controllers\UsuariosController::getAll(); // Listar usuarios
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\UsuariosController::create(); // Crear usuario
        }
        break;

    case 'usuarios/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\UsuariosController::update(); // Actualizar usuario
        }
        break;

    case 'usuarios/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\UsuariosController::delete(); // Eliminar usuario
        }
        break;


    default:
        // Ruta no encontrada
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
        break;
}
