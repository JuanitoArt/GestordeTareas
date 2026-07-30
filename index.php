<?php

require_once __DIR__ . '/controllers/TareaController.php';
require_once __DIR__ . '/controllers/ActividadController.php';

$tareaController = new TareaController();
$actividadController = new ActividadController();

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

    <h2>Tareas</h2>

    <p>Total de tareas: <?= count($tareas) ?></p>

    <h2>Actividades</h2>

    <p>Total de actividades: <?= count($actividades) ?></p>

</body>
</html>