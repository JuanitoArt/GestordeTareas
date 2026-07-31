<?php

require_once __DIR__ . '/config/zonahoraria.php';
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

    case 'eliminarAct+ividad':

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

    // Buscar por texto
    if (!empty($_GET['buscar'])) {

        $buscar = strtolower(trim($_GET['buscar']));

        $actividades = array_filter($actividades, function ($actividad) use ($buscar) {

            return
                str_contains(strtolower($actividad['titulo']), $buscar) ||
                str_contains(strtolower($actividad['descripcion']), $buscar) ||
                str_contains(strtolower($actividad['lugar']), $buscar);

        });

    }

    // Filtrar por fecha
    if (!empty($_GET['fecha'])) {

        $hoy = date('Y-m-d');

        switch ($_GET['fecha']) {

            case 'hoy':

                $actividades = array_filter($actividades, function ($actividad) use ($hoy) {

                    return $actividad['fecha'] == $hoy;

                });

                break;

            case 'manana':

                $manana = date('Y-m-d', strtotime('+1 day'));

                $actividades = array_filter($actividades, function ($actividad) use ($manana) {

                    return $actividad['fecha'] == $manana;

                });

                break;

            case 'semana':

                $finSemana = date('Y-m-d', strtotime('+7 days'));

                $actividades = array_filter($actividades, function ($actividad) use ($hoy, $finSemana) {

                    return $actividad['fecha'] >= $hoy &&
                           $actividad['fecha'] <= $finSemana;

                });

                break;

            case 'mes':

                $mesActual = date('Y-m');

                $actividades = array_filter($actividades, function ($actividad) use ($mesActual) {

                    return substr($actividad['fecha'], 0, 7) == $mesActual;

                });

                break;
        }

    }

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
    'estado' => $_POST['estado'] ?? 'Pendiente'
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

    // Buscar por texto
    if (!empty($_GET['buscar'])) {

        $buscar = strtolower(trim($_GET['buscar']));

        $tareas = array_filter($tareas, function ($tarea) use ($buscar) {

            return
                str_contains(strtolower($tarea['titulo']), $buscar) ||
                str_contains(strtolower($tarea['descripcion']), $buscar);

        });

    }

    // Filtrar por estado
    if (!empty($_GET['estado'])) {

        $estado = $_GET['estado'];

        $tareas = array_filter($tareas, function ($tarea) use ($estado) {

            return $tarea['estado'] == $estado;

        });

    }

    // Filtrar por prioridad
    if (!empty($_GET['prioridad'])) {

        $prioridad = $_GET['prioridad'];

        $tareas = array_filter($tareas, function ($tarea) use ($prioridad) {

            return $tarea['prioridad'] == $prioridad;

       });

    }

    require_once __DIR__ . '/views/tareas/index.php';

    break;

    case 'inicio':


        $tareas = $tareaController->listar();
        $actividades = $actividadController->listar();

        $nombreUsuario = "Juan";

        $hora = (int) date('H');

if ($hora >= 5 && $hora < 12) {

    $saludo = "🌅 Buenos días";

} elseif ($hora >= 12 && $hora < 18) {

    $saludo = "☀️ Buenas tardes";

} else {

    $saludo = "🌙 Buenas noches";

}

        $dias = [
        'Sunday' => 'Domingo',
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado'
    ];

    $meses = [
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre'
    ];

    $fechaActual =
        $dias[date('l')] . ", " .
        date('d') . " de " .
        $meses[(int)date('n')] . " de " .
        date('Y');

    // Contar solo las tareas pendientes o en progreso
    $tareasPendientes = 0;

    foreach ($tareas as $tarea) {

        if (
            $tarea['estado'] == 'Pendiente' ||
            $tarea['estado'] == 'En progreso'
        ) {
            $tareasPendientes++;
        }
    }

    // Buscar la próxima actividad
    $proximaActividad = null;

// Ordenar las actividades por fecha y hora
usort($actividades, function ($a, $b) {

    return strtotime($a['fecha'] . ' ' . $a['hora_inicio'])
        <=> strtotime($b['fecha'] . ' ' . $b['hora_inicio']);

});

// Buscar la primera actividad que aún no haya comenzado
$ahora = time();



foreach ($actividades as $actividad) {

    $fechaHoraActividad = strtotime(
        $actividad['fecha'] . ' ' . $actividad['hora_inicio']
    );

    if ($fechaHoraActividad >= $ahora) {

        $proximaActividad = $actividad;
        break;
    }
}

$horaInicio = '';
$horaFin = '';
$fechaActividad = '';

if ($proximaActividad) {

    $horaInicio = date(
        'g:i a',
        strtotime($proximaActividad['hora_inicio'])
    );

    $horaFin = date(
        'g:i a',
        strtotime($proximaActividad['hora_fin'])
    );

    $fecha = $proximaActividad['fecha'];

    if ($fecha == date('Y-m-d')) {

        $fechaActividad = "Hoy";

    } elseif ($fecha == date('Y-m-d', strtotime('+1 day'))) {

        $fechaActividad = "Mañana";

    } else {

        $fechaActividad =
            $dias[date('l', strtotime($fecha))] . ", " .
            date('d', strtotime($fecha)) . " de " .
            $meses[(int)date('n', strtotime($fecha))] . " de " .
            date('Y', strtotime($fecha));
    }
}

    // Mensaje dinámico
    if ($tareasPendientes == 0) {

        $mensaje = "🎉 Hoy estás libre. ¡Disfruta tu día!";

    } elseif ($tareasPendientes == 1) {

        $mensaje = "🎯 Solo te queda una tarea. ¡Ya casi terminas!";

    } elseif ($tareasPendientes <= 3) {

        $mensaje = "🙂 Te quedan algunas cosas por hacer. ¡Ánimo!";

    } else {

        $mensaje = "💪 Tienes mucho por hacer hoy.";
    }

            ?>

            <!DOCTYPE html>
            <html lang="es">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Gestor de Tareas</title>
            </head>

            <body>

    <h1>📌 Gestor de Tareas</h1>

    <h2><?= $saludo ?>, <?= htmlspecialchars($nombreUsuario) ?></h2>

    <p>
    📅 <?= $fechaActual ?>
</p>

    <h3><?= $mensaje ?></h3>

    <hr>

    <h3>📋 Tareas pendientes</h3>

    <p>
        <?= $tareasPendientes ?>
    </p>

    <hr>

    <h3>📅 Próxima actividad</h3>

    <?php if ($proximaActividad): ?>

        <strong>
            <?= htmlspecialchars($proximaActividad['titulo']) ?>
        </strong>

        <br>

        🗓️ <?= $fechaActividad ?>

        <br>

        🕒 <?= $horaInicio ?> - <?= $horaFin ?>

        <br>

        📍 <?= htmlspecialchars($proximaActividad['lugar']) ?>

    <?php else: ?>

        <p>No tienes actividades programadas.</p>

    <?php endif; ?>

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