<?php
$titulo = "Mis tareas";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

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

<h2>📋 Mis Tareas</h2>

<a href="index.php?accion=crearTarea">
    ➕ Nueva tarea
</a>

<br><br>

<?php if (empty($tareas)): ?>

    <div class="card text-center p-5">

        <h1>📋</h1>

        <h4>No tienes tareas registradas</h4>

        <p class="text-muted">
            Crea tu primera tarea para comenzar.
        </p>

    </div>

<?php else: ?>

<div class="row g-4">

<?php foreach ($tareas as $tarea): ?>

    <?php

        switch ($tarea['prioridad']) {

            case 'Alta':
                $prioridad = 'danger';
                break;

            case 'Media':
                $prioridad = 'warning';
                break;

            default:
                $prioridad = 'success';
        }

        switch ($tarea['estado']) {

            case 'Pendiente':
                $estado = 'secondary';
                break;

            case 'En progreso':
                $estado = 'primary';
                break;

            case 'Completada':
                $estado = 'success';
                break;

            default:
                $estado = 'dark';
        }

    ?>

    <div class="col-md-6 col-lg-4">

       <div class="card h-100 overflow-hidden">

    <div
        style="
            height: 8px;
            background:
            <?= $tarea['prioridad'] == 'Alta'
                ? '#EF4444'
                : ($tarea['prioridad'] == 'Media'
                    ? '#F59E0B'
                    : '#22C55E') ?>;
        ">
    </div>

    <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="fw-bold mb-0">

                        <?= htmlspecialchars($tarea['titulo']) ?>

                    </h5>

                    <span class="badge bg-<?= $prioridad ?>">

                        <?= htmlspecialchars($tarea['prioridad']) ?>

                    </span>

                </div>

                <p class="text-muted">

                    <?= htmlspecialchars($tarea['descripcion']) ?>

                </p>

    <?php

if (
    $tarea['estado'] == 'Pendiente' ||
    $tarea['estado'] == 'En progreso'
) {

    $fechaLimite = new DateTime($tarea['fecha_limite']);
    $hoy = new DateTime(date('Y-m-d'));

    $dias = (int)$hoy->diff($fechaLimite)->format('%r%a');

    if ($dias == 0) {

        $textoFecha = "🔥 Vence hoy";

    } elseif ($dias == 1) {

        $textoFecha = "🌅 Vence mañana";

    } elseif ($dias > 1 && $dias <= 7) {

        $textoFecha = "⏳ Vence en $dias días";

    } elseif ($dias < 0) {

        $textoFecha = "🔴 Venció hace " . abs($dias) . " días";

    } else {

        $textoFecha = "📅 " . date('d/m/Y', strtotime($tarea['fecha_limite']));

    }

} else {

    $textoFecha = "";

}
?>

       <?php if (!empty($textoFecha)): ?>

    <p class="mb-2">

        <?= $textoFecha ?>

    </p>

<?php endif; ?>


    <?php

        switch ($tarea['estado']) {

            case 'Pendiente':
                $mensajeEstado = "Pendiente, lista para comenzar";
                break;

            case 'En progreso':
                $mensajeEstado = "En progreso, ¡esfuérzate!";
                break;

            case 'Completada':
                $mensajeEstado = "Completada, ¡Buen trabajo!";
                break;

            default:
                $mensajeEstado = "🚫 Cancelada";
                break;
        }

        ?>

        <p>

    <span class="badge bg-<?= $estado ?> fs-6">

        <?= $mensajeEstado ?>

    </span>

</p>

            

         <div class="d-flex justify-content-between">

    <a
        href="index.php?accion=editarTarea&id=<?= $tarea['id'] ?>"
        class="btn btn-outline-primary btn-sm">

        ✏️ Editar

    </a>

    <a
        href="index.php?accion=eliminarTarea&id=<?= $tarea['id'] ?>"
        class="btn btn-outline-danger btn-sm"
        onclick="return confirm('¿Seguro que deseas eliminar esta tarea?')">

        🗑️ Eliminar

    </a>
</div>

</div>

        </div>

    </div>

<?php endforeach; ?>

</div>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>