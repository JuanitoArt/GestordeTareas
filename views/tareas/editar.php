<?php

$titulo = "Editar tarea";

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

/** @var array $tarea */

?>

<h1>✏️ Editar tarea</h1>

<form action="index.php?accion=actualizarTarea&id=<?= $tarea['id'] ?>" method="POST">

    <label for="titulo">Título:</label><br>

    <input
        type="text"
        id="titulo"
        name="titulo"
        value="<?= htmlspecialchars($tarea['titulo']) ?>"
        required
    >

    <br><br>

    <label for="descripcion">Descripción:</label><br>

    <textarea
        id="descripcion"
        name="descripcion"
        rows="4"
        required
    ><?= htmlspecialchars($tarea['descripcion']) ?></textarea>

    <br><br>

    <label for="prioridad">Prioridad:</label><br>

    <select id="prioridad" name="prioridad" required>

        <option value="Baja"
            <?= $tarea['prioridad'] == 'Baja' ? 'selected' : '' ?>>
            Baja
        </option>

        <option value="Media"
            <?= $tarea['prioridad'] == 'Media' ? 'selected' : '' ?>>
            Media
        </option>

        <option value="Alta"
            <?= $tarea['prioridad'] == 'Alta' ? 'selected' : '' ?>>
            Alta
        </option>

    </select>

    <br><br>

    <label for="fecha_limite">Fecha límite:</label><br>

    <input
        type="date"
        id="fecha_limite"
        name="fecha_limite"
        value="<?= $tarea['fecha_limite'] ?>"
        required
    >

    <br><br>

    <label for="estado">
        Estado:
    </label>

    <br>

    <select id="estado" name="estado">

        <option value="Pendiente"
            <?= $tarea['estado'] == 'Pendiente' ? 'selected' : '' ?>>
            Pendiente
        </option>

        <option value="En progreso"
            <?= $tarea['estado'] == 'En progreso' ? 'selected' : '' ?>>
            En progreso
        </option>

        <option value="Completada"
            <?= $tarea['estado'] == 'Completada' ? 'selected' : '' ?>>
            Completada
        </option>

        <option value="Cancelada"
            <?= $tarea['estado'] == 'Cancelada' ? 'selected' : '' ?>>
            Cancelada
        </option>

    </select>

    <br><br>

    <button type="submit">
        💾 Actualizar tarea
    </button>

</form>

<br>

<a href="index.php?accion=tareas">
    ← Volver
</a>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>