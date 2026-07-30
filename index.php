<?php

require_once __DIR__ . '/controllers/TareaController.php';
require_once __DIR__ . '/controllers/ActividadController.php';

$tareaController = new TareaController();
$actividadController = new ActividadController();

$accion = $_GET['accion'] ?? 'inicio';

switch ($accion) {

    case 'actualizarActividad':

    $id = $_GET['id'];

    $datos = [
        'titulo' => $_POST['titulo'] ?? '',
        'descripcion' => $_POST['descripcion'] ?? '',
        'fecha' => $_POST['fecha'] ?? '',
        'hora_inicio' => $_POST['hora_inicio'] ?? '',
        'hora_fin' => $_POST['hora_fin'] ?? '',
        'lugar' => $_POST['lugar'] ?? ''
    ];

    $actividadController->actualizar($id, $datos);

    header('Location: index.php?accion=actividades');
    exit;

    break;

    case 'editarActividad':

    $id = $_GET['id'];

    $actividad = $actividadController->buscarPorId($id);

    require_once __DIR__ . '/views/actividades/editar.php';

    break;

    case 'eliminarActividad':

    $id = $_GET['id'];

    $actividadController->eliminar($id);

    header('Location: index.php?accion=actividades');
    exit;

    break;

    case 'editarActividad':

    $id = $_GET['id'];

    $actividad = $actividadController->buscarPorId($id);

    require_once __DIR__ . '/views/actividades/editar.php';

    break;

    case 'guardarActividad':

   $datos = [
    'titulo' => $_POST['titulo'] ?? '',
    'descripcion' => $_POST['descripcion'] ?? '',
    'fecha' => $_POST['fecha'] ?? '',
    'hora_inicio' => $_POST['hora_inicio'] ?? '',
    'hora_fin' => $_POST['hora_fin'] ?? '',
    'lugar' => $_POST['lugar'] ?? ''
];

    $resultado = $actividadController->crear($datos);

    header('Location: index.php?accion=actividades');
    exit;

    break;

    case 'crearActividad':

    require_once __DIR__ . '/views/actividades/crear.php';

    break;

   case 'actividades':

    $actividades = $actividadController->listar();

    require_once __DIR__ . '/views/actividades/index.php';

    break;

    case 'eliminarTarea':

    $id = $_GET['id'];

    $tareaController->eliminar($id);

    header('Location: index.php?accion=tareas');
    exit;

    break;

    case 'actualizarTarea':

    $id = $_GET['id'];

    $datos = [
        'titulo' => $_POST['titulo'] ?? '',
        'descripcion' => $_POST['descripcion'] ?? '',
        'prioridad' => $_POST['prioridad'] ?? '',
        'fecha_limite' => $_POST['fecha_limite'] ?? '',
        'estado' => $_POST['estado'] ?? ''
    ];

    $resultado = $tareaController->actualizar($id, $datos);

    header('Location: index.php?accion=tareas');
    exit;

    break;

    case 'editarTarea':

    $id = $_GET['id'];

    $tarea = $tareaController->buscarPorId($id);

    require_once __DIR__ . '/views/tareas/editar.php';

    break;

    case 'guardarTarea':

        $datos = [
            'titulo' => $_POST['titulo'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'prioridad' => $_POST['prioridad'] ?? '',
            'fecha_limite' => $_POST['fecha_limite'] ?? '',
            'estado' => 'Pendiente'
        ];

        $resultado = $tareaController->crear($datos);

        header('Location: index.php?accion=tareas');
        exit;

        break;

    case 'crearTarea':

        require_once __DIR__ . '/views/tareas/crear.php';

        break;

    case 'tareas':

        $tareas = $tareaController->listar();

        require_once __DIR__ . '/views/tareas/index.php';

        break;

    case 'inicio':

        // ...

        $tareas = $tareaController->listar();
        $actividades = $actividadController->listar();

        ?>

        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Gestor de Tareas</title>
        </head>

        <body>

            <h1>📅 Mi Agenda Digital</h1>

            <h2>Resumen</h2>

            <p>Total de tareas: <?= count($tareas) ?></p>

            <p>Total de actividades: <?= count($actividades) ?></p>

            <hr>

            <a href="index.php?accion=tareas">
                📋 Ver tareas
            </a>

            <br><br>

            <a href="index.php?accion=actividades">
                📅 Ver actividades
            </a>

        </body>

        </html>

        <?php

        break;

    default:

        echo "<h1>404</h1>";
        echo "<p>La página que buscas no existe.</p>";

        break;
}