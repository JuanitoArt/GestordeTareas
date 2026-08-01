<?php
$titulo = "Mis actividades";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<form method="GET" action="index.php">

    <input type="hidden" name="accion" value="actividades">

    <input
        type="text"
        name="buscar"
        placeholder="🔎 Buscar actividad..."
        value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
    >

    <select name="fecha">

        <option value="">Todas las fechas</option>

        <option value="hoy"
            <?= (($_GET['fecha'] ?? '') == 'hoy') ? 'selected' : '' ?>>
            Hoy
        </option>

        <option value="manana"
            <?= (($_GET['fecha'] ?? '') == 'manana') ? 'selected' : '' ?>>
            Mañana
        </option>

        <option value="semana"
            <?= (($_GET['fecha'] ?? '') == 'semana') ? 'selected' : '' ?>>
            Esta semana
        </option>

        <option value="mes"
            <?= (($_GET['fecha'] ?? '') == 'mes') ? 'selected' : '' ?>>
            Este mes
        </option>

    </select>

    <button type="submit">
        🔍 Aplicar
    </button>

    <a href="index.php?accion=actividades">
        🧹 Limpiar
    </a>

</form>

<br>

<h2>📅 Mis Actividades</h2>

<a href="index.php?accion=crearActividad">
    ➕ Nueva actividad
</a>

<br><br>

<?php if (empty($actividades)): ?>

    <p>No tienes actividades registradas.</p>

<?php else: ?>

    <table border="1" cellpadding="10">

        <thead>

            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Lugar</th>
                <th>Fecha</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($actividades as $actividad): ?>

                <tr>

                    <td><?= $actividad['id'] ?></td>

                    <td><?= htmlspecialchars($actividad['titulo']) ?></td>

                    <td><?= htmlspecialchars($actividad['descripcion']) ?></td>

                    <td><?= htmlspecialchars($actividad['lugar']) ?></td>

                    <td><?= htmlspecialchars($actividad['fecha']) ?></td>

                    <td><?= htmlspecialchars($actividad['hora_inicio']) ?></td>

                    <td><?= htmlspecialchars($actividad['hora_fin']) ?></td>

                    <td>

                        <a href="index.php?accion=editarActividad&id=<?= $actividad['id'] ?>">
                            ✏️ Editar
                        </a>

                        |

                        <a href="index.php?accion=eliminarActividad&id=<?= $actividad['id'] ?>"
                           onclick="return confirm('¿Eliminar actividad?')">
                            🗑️ Eliminar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>