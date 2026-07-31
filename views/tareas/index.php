<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<form method="GET" action="index.php">

    <input type="hidden" name="accion" value="tareas">

    <input
        type="text"
        name="buscar"
        placeholder="🔎 Buscar tarea..."
        value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
    >

    <select name="estado">

        <option value="">Todos los estados</option>

        <option value="Pendiente"
            <?= (($_GET['estado'] ?? '') == 'Pendiente') ? 'selected' : '' ?>>
            Pendiente
        </option>

        <option value="En progreso"
            <?= (($_GET['estado'] ?? '') == 'En progreso') ? 'selected' : '' ?>>
            En progreso
        </option>

        <option value="Completada"
            <?= (($_GET['estado'] ?? '') == 'Completada') ? 'selected' : '' ?>>
            Completada
        </option>

        <option value="Cancelada"
            <?= (($_GET['estado'] ?? '') == 'Cancelada') ? 'selected' : '' ?>>
            Cancelada
        </option>

    </select>

    <select name="prioridad">

    <option value="">Todas las prioridades</option>

    <option value="Alta"
        <?= (($_GET['prioridad'] ?? '') == 'Alta') ? 'selected' : '' ?>>
        Alta
    </option>

    <option value="Media"
        <?= (($_GET['prioridad'] ?? '') == 'Media') ? 'selected' : '' ?>>
        Media
    </option>

    <option value="Baja"
        <?= (($_GET['prioridad'] ?? '') == 'Baja') ? 'selected' : '' ?>>
        Baja
    </option>

</select>

    <button type="submit">
        🔍 Aplicar
    </button>
    <a href="index.php?accion=tareas">
    🧹 Limpiar
    </a>
</form>

<br>



<br>

<br>

<h2>📋 Mis Tareas</h2>
<a href="index.php?accion=crearTarea">
    ➕ Nueva tarea
</a>

<br><br>

<?php if (empty($tareas)): ?>

    <p>No tienes tareas registradas.</p>

<?php else: ?>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Prioridad</th>
                <th>Fecha límite</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($tareas as $tarea): ?>

                <tr>
                    <td><?= $tarea['id'] ?></td>

                    <td>
                        <?= htmlspecialchars($tarea['titulo']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($tarea['descripcion']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($tarea['prioridad']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($tarea['fecha_limite']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($tarea['estado']) ?>
                    </td>

                    <td>
                       <a href="index.php?accion=editarTarea&id=<?= $tarea['id'] ?>">
                            ✏️ Editar
                        </a>

                        |

                        <a href="index.php?accion=eliminarTarea&id=<?= $tarea['id'] ?>"
                           onclick="return confirm('¿Seguro que deseas eliminar esta tarea?')">
                            🗑️ Eliminar
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>