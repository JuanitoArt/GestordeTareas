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

    case 'miDia':

    $actividades = $actividadController->listar();

    $hoy = date('Y-m-d');

    // Solo actividades de hoy
    $actividades = array_filter($actividades, function ($actividad) use ($hoy) {

        return $actividad['fecha'] == $hoy;

    });

    // Ordenarlas por hora de inicio
    usort($actividades, function ($a, $b) {

        return strcmp($a['hora_inicio'], $b['hora_inicio']);

    });

    require_once __DIR__ . '/views/actividades/miDia.php';

    break;

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

    // Mostrar solo tareas de hoy (si viene del Dashboard)
    if (!empty($_GET['hoy'])) {

        $hoy = date('Y-m-d');

        $tareas = array_filter($tareas, function ($tarea) use ($hoy) {

            return $tarea['fecha_limite'] == $hoy;

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

        $actividadActual = null;
$proximaActividad = null;

$ahora = time();

usort($actividades, function ($a, $b) {
    return strtotime($a['fecha'].' '.$a['hora_inicio'])
        <=> strtotime($b['fecha'].' '.$b['hora_inicio']);
});

foreach ($actividades as $actividad) {

    $inicio = strtotime($actividad['fecha'].' '.$actividad['hora_inicio']);
    $fin    = strtotime($actividad['fecha'].' '.$actividad['hora_fin']);

    if ($ahora >= $inicio && $ahora <= $fin) {

        $actividadActual = $actividad;
        break;

    }

    if ($inicio > $ahora && $proximaActividad == null) {

        $proximaActividad = $actividad;

    }

}

$pendientes = 0;
$enProgreso = 0;
$completadas = 0;
$canceladas = 0;

$hoy = date('Y-m-d');

foreach ($tareas as $tarea) {

    switch ($tarea['estado']) {

        case 'Pendiente':

            if ($tarea['fecha_limite'] == $hoy) {
                $pendientes++;
            }

            break;

        case 'En progreso':

            if ($tarea['fecha_limite'] == $hoy) {
                $enProgreso++;
            }

            break;

        case 'Completada':

    if ($tarea['fecha_limite'] == $hoy) {
        $completadas++;
    }

    break;

        case 'Cancelada':
            $canceladas++;
            break;
    }

}
$actividadesHoy = 0;
$hoy = date('Y-m-d');
$ahora = time();

foreach ($actividades as $actividad) {

    if ($actividad['fecha'] == $hoy) {

        $horaFin = strtotime(
            $actividad['fecha'] . ' ' . $actividad['hora_fin']
        );

        // Solo contar actividades que aún no terminan
        if ($horaFin >= $ahora) {

            $actividadesHoy++;

        }

    }

}

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
$actividadActual = null;
$proximaActividad = null;
$tituloActividad = "📅 Próxima actividad";

// Ordenar las actividades por fecha y hora
usort($actividades, function ($a, $b) {

    return strtotime($a['fecha'] . ' ' . $a['hora_inicio'])
        <=> strtotime($b['fecha'] . ' ' . $b['hora_inicio']);

});

$ahora = time();

foreach ($actividades as $actividad) {

    $inicio = strtotime(
        $actividad['fecha'] . ' ' . $actividad['hora_inicio']
    );

    $fin = strtotime(
        $actividad['fecha'] . ' ' . $actividad['hora_fin']
    );

    // Si la actividad está en curso
    if ($ahora >= $inicio && $ahora <= $fin) {

        $actividadActual = $actividad;
        $tituloActividad = "🟢 Actividad en curso";
        break;

    }

    // Guardar la próxima actividad
    if ($inicio > $ahora && $proximaActividad == null) {

        $proximaActividad = $actividad;

    }

}

$actividadMostrar = $actividadActual ?? $proximaActividad;

$horaInicio = '';
$horaFin = '';
$fechaActividad = '';

if ($actividadMostrar) {

   $horaInicio = date(
    'g:i a',
    strtotime($actividadMostrar['hora_inicio'])
);

$horaFin = date(
    'g:i a',
    strtotime($actividadMostrar['hora_fin'])
);

$fecha = $actividadMostrar['fecha'];

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


         <?php
$titulo = "Inicio";
require_once __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/views/layouts/navbar.php';
?>

<div class="card mb-4">

    <div class="card-body">

        <h2 class="mb-2">
            <?= $saludo ?>,
            <?= htmlspecialchars($nombreUsuario) ?> 👋
        </h2>

        <p class="mb-2 text-muted">

            📅 <?= $fechaActual ?>

        </p>

        <h5>

            <?= $mensaje ?>

        </h5>

    </div>

</div>






<div class="card mt-4">

    <div class="card-body">

        <h4 class="mb-4">
             <?= $tituloActividad ?>
        </h4>

        <?php if ($actividadMostrar): ?>

            <h3 class="fw-bold">

                <?= htmlspecialchars($actividadMostrar['titulo']) ?>

            </h3>

            <p class="mb-2">

                <i class="bi bi-calendar-event"></i>

                <?= $fechaActividad ?>

            </p>

            <p class="mb-2">

                <i class="bi bi-clock"></i>

                <?= $horaInicio ?> - <?= $horaFin ?>

            </p>

            <p class="mb-0">

                <i class="bi bi-geo-alt-fill"></i>

                <?= htmlspecialchars($actividadMostrar['lugar']) ?>

            </p>

        <?php else: ?>

            <div class="text-center py-4">

                <h2>🌴</h2>

                <h5>No tienes actividades programadas</h5>

                <p class="text-muted">

                    Aprovecha para descansar o planear algo nuevo.

                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

<h3 class="mb-4">📊 Resumen de hoy</h3>

<div class="row g-4">

    <div class="col-6 col-md-3">

    <a href="index.php?accion=tareas&estado=Pendiente&hoy=1"
       class="text-decoration-none text-dark">

        <div class="card text-center h-100 resumen-card">

            <div class="card-body">

                <h1>📋</h1>

                <h2><?= $pendientes ?></h2>

                <p class="mb-0">Pendientes</p>

            </div>

        </div>

    </a>

</div>

    <div class="col-6 col-md-3">

    <a href="index.php?accion=tareas&estado=En progreso&hoy=1"
       class="text-decoration-none text-dark">

        <div class="card text-center h-100 resumen-card">

            <div class="card-body">

                <h1>🔄</h1>

                <h2><?= $enProgreso ?></h2>

                <p class="mb-0">En progreso</p>

            </div>

        </div>

    </a>

</div>

    <div class="col-6 col-md-3">

    <a href="index.php?accion=tareas&estado=Completada&hoy=1"
       class="text-decoration-none text-dark">

        <div class="card text-center h-100 resumen-card">

            <div class="card-body">

                <h1>✅</h1>

                <h2><?= $completadas ?></h2>

                <p class="mb-0">Completadas</p>

            </div>

        </div>

    </a>

</div>

    <div class="col-6 col-md-3">

    <a href="index.php?accion=miDia"
       class="text-decoration-none text-dark">

        <div class="card text-center h-100">

            <div class="card-body">

                <h1>📅</h1>

                <h2><?= $actividadesHoy ?></h2>

                <p class="mb-0">Actividades</p>

            </div>

        </div>

    </div>

</div>


<a class="btn btn-primary me-2" href="index.php?accion=tareas">
    <i class="bi bi-list-check"></i>
    Ver tareas
</a>

<a class="btn btn-success" href="index.php?accion=actividades">
    <i class="bi bi-calendar-event"></i>
    Ver actividades
</a>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
            <?php

            break;

    default:

        echo "<h1>404</h1>";
        echo "<p>La página que buscas no existe.</p>";

        break;
}