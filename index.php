<?php

require_once __DIR__ . '/controllers/TareaController.php';
require_once __DIR__ . '/controllers/ActividadController.php';

$tareaController = new TareaController();
$actividadController = new ActividadController();

$accion = $_GET['accion'] ?? 'inicio';

switch ($accion) {

    case 'tareas':

        $tareas = $tareaController->listar();

        require_once __DIR__ . '/views/tareas/index.php';

        break;

    case 'inicio':

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