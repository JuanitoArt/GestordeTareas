<?php

$titulo = "Editar actividad";

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

/** @var array $actividad */

?>

<h1>✏️ Editar actividad</h1>

<form action="index.php?accion=actualizarActividad&id=<?= (int) $actividad['id'] ?>" method="POST">

    <label for="titulo">
        Título:
    </label>

    <br>

    <input
        type="text"
        id="titulo"
        name="titulo"
        value="<?= htmlspecialchars($actividad['titulo']) ?>"
        required
    >

    <br><br>

    <label for="descripcion">
        Descripción:
    </label>

    <br>

    <textarea
        id="descripcion"
        name="descripcion"
        rows="4"
        required
    ><?= htmlspecialchars($actividad['descripcion']) ?></textarea>

    <br><br>

    <label for="fecha">
        Fecha:
    </label>

    <br>

    <input
        type="date"
        id="fecha"
        name="fecha"
        value="<?= htmlspecialchars($actividad['fecha']) ?>"
        required
    >

    <br><br>

    <label for="hora_inicio">
        Hora inicio:
    </label>

    <br>

    <input
        type="time"
        id="hora_inicio"
        name="hora_inicio"
        value="<?= htmlspecialchars($actividad['hora_inicio']) ?>"
        required
    >

    <br><br>

    <label for="hora_fin">
        Hora fin:
    </label>

    <br>

    <input
        type="time"
        id="hora_fin"
        name="hora_fin"
        value="<?= htmlspecialchars($actividad['hora_fin']) ?>"
        required
    >

    <br><br>

    <label for="lugar">
        Lugar:
    </label>

    <br>

    <input
        type="text"
        id="lugar"
        name="lugar"
        value="<?= htmlspecialchars($actividad['lugar']) ?>"
        required
    >

    <br><br>

    <button type="submit">
        💾 Actualizar actividad
    </button>

</form>

<br>

<a href="index.php?accion=actividades">
    ← Volver
</a>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
