<?php
set_include_path(__DIR__ . '/../');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload manual (si no estás usando Composer)
// Asegúrate de requerir todos los archivos necesarios
require_once 'Libs\Database.php';
require_once 'Libs\Response.php';
require_once 'Controllers\AuthController.php';
require_once 'Controllers\ColaboradoresController.php';
require_once 'Controllers\DocumentosController.php';
require_once 'Controllers\LegajoController.php';
require_once 'Controllers\UsuariosController.php';
require_once 'Controllers\DashboardController.php';

require_once __DIR__ . '/Models/Usuario.php';
require_once __DIR__ . '/Models/Colaborador.php';
require_once __DIR__ . '/Models/DocumentoIdentidad.php';
require_once __DIR__ . '/Models/Documentos.php';
require_once __DIR__ . '/Models/Legajo.php';

// Obtener la ruta solicitada
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace($basePath, '', $path);
$path = trim($path, '/');

// Dividir la ruta en segmentos
$segments = explode('/', $path);

// Segmentos de la URL
$controller = $segments[0] ?? '';
$action = $segments[1] ?? '';
$id = $segments[2] ?? '';

switch ($controller) {
    case '':
        // Ruta raíz - Dashboard
        Controllers\DashboardController::index();
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Controllers\AuthController::login();
        } else {
            require_once __DIR__ . '/Views/Auth/Login.php';
        }
        break;

    case 'logout':
        Controllers\AuthController::logout();
        break;

    case 'colaboradores':
        if ($action === '') {
            // /colaboradores
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                Controllers\ColaboradoresController::index();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\ColaboradoresController::create();
            }
        } elseif ($action === 'search') {
            Controllers\ColaboradoresController::search();
        } elseif ($action === 'update') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\ColaboradoresController::update();
            }
        } elseif ($action === 'delete') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\ColaboradoresController::delete();
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
        }
        break;

    case 'documentos':
            if ($action === '') {
                Controllers\DocumentosController::getAll();
            } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\DocumentosController::create();
            } elseif ($action === 'create') {
                require_once __DIR__ . '/Views/Documentos/Create.php';
            } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($id)) {
                Controllers\DocumentosController::edit($id);
            } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\DocumentosController::update();
            } elseif ($action === 'delete') {
                Controllers\DocumentosController::delete();
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ruta no encontrada']);
            }
            break;
        
    case 'legajo':
        if ($action === '') {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Mostrar la lista de legajos
                Controllers\LegajoController::getAll();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Crear un nuevo legajo
                Controllers\LegajoController::create();
            }
        } elseif ($action === 'create') {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Mostrar la vista de creación
                require_once __DIR__ . '/Views/Legajo/Create.php';
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Procesar la creación del legajo
                Controllers\LegajoController::create();
            }
        } elseif ($action === 'update') {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($id)) {
                // Mostrar la vista de edición
                $legajo = Models\Legajo::findById($id);
                if ($legajo) {
                    require_once __DIR__ . '/Views/Legajo/Edit.php';
                } else {
                    header('Location: /legajo?error=Legajo no encontrado');
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Procesar la actualización del legajo
                Controllers\LegajoController::update();
            }
        } elseif ($action === 'delete') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Eliminar un legajo
                Controllers\LegajoController::delete();
            }
        } else {
            // Ruta no encontrada
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
        }
        break;
            
    case 'usuarios':
        if ($action === '') {
            // /usuarios
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                Controllers\UsuariosController::getAll();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Aquí se podría recibir el formulario de creación enviado por POST
                // Pero si prefieres que /usuarios POST sea para crear, puedes dejarlo
                // Aunque es más ordenado manejar /usuarios/create
                Controllers\UsuariosController::create();
            }

        } elseif ($action === 'create') {
            // /usuarios/create
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Mostrar el formulario de creación
                Controllers\UsuariosController::showCreateForm();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Procesar el formulario de creación
                Controllers\UsuariosController::create();
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
            }
        } elseif ($action === 'update') {
            // /usuarios/update/ID
            if ($id && $_SERVER['REQUEST_METHOD'] === 'GET') {
                // Aquí llamas a la función para mostrar el formulario de edición
                Controllers\UsuariosController::showEditForm($id);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\UsuariosController::update();
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
            }
        } elseif ($action === 'delete') {
            // /usuarios/delete/ID
            if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\UsuariosController::delete();
            } else {
                // Podrías tener una vista de confirmación de borrado si lo deseas
                http_response_code(404);
                echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
        }
        break;

    default:
        // Ruta no encontrada
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
        break;
}

