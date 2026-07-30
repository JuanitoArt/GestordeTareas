<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarea</title>
</head>

<body>

<h1>✏️ Editar tarea</h1>

<form action="index.php?accion=actualizarTarea&id=<?= $tarea['id'] ?>" method="POST">

    <label>Título:</label><br>
    <input
        type="text"
        name="titulo"
        value="<?= htmlspecialchars($tarea['titulo']) ?>"
        required
    >

    <br><br>

    <label>Descripción:</label><br>
    <textarea
        name="descripcion"
        rows="4"
        required
    ><?= htmlspecialchars($tarea['descripcion']) ?></textarea>

    <br><br>

    <label>Prioridad:</label><br>

    <select name="prioridad" required>

        <option value="Baja" <?= $tarea['prioridad'] == 'Baja' ? 'selected' : '' ?>>
            Baja
        </option>

        <option value="Media" <?= $tarea['prioridad'] == 'Media' ? 'selected' : '' ?>>
            Media
        </option>

        <option value="Alta" <?= $tarea['prioridad'] == 'Alta' ? 'selected' : '' ?>>
            Alta
        </option>

    </select>

    <br><br>

    <label>Fecha límite:</label><br>

    <input
        type="date"
        name="fecha_limite"
        value="<?= $tarea['fecha_limite'] ?>"
        required
    >

    <br><br>

   <label>
    Estado:
</label>

<br>

<select name="estado">

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

</body>
</html>