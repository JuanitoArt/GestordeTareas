<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<form method="GET" action="index.php">

    <input type="hidden" name="accion" value="tareas">

    <input
        type="text"
        name="buscar"
        placeholder="🔎 Buscar tarea..."
        value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
    >

    <button type="submit">
        Buscar
    </button>

</form>

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