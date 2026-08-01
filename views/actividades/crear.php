<?php
$titulo = "Crear actividad";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<h1>➕ Nueva actividad</h1>

<form action="index.php?accion=guardarActividad" method="POST">

    <label for="titulo">
        Título:
    </label>

    <br>

    <input
        type="text"
        id="titulo"
        name="titulo"
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
    ></textarea>

    <br><br>

    <label for="fecha">
        Fecha:
    </label>

    <br>

    <input
        type="date"
        id="fecha"
        name="fecha"
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
        required
    >

    <br><br>

    <button type="submit">
        💾 Guardar actividad
    </button>

</form>

<br>

<a href="index.php?accion=actividades">
    ← Volver
</a>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>