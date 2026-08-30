<?php
$titulo = "Mis tareas";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

    <h2 class="mb-0">📋 Mis Tareas</h2>

    <a href="index.php?accion=crearTarea" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nueva tarea
    </a>

</div>

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <form method="GET" action="index.php" class="row g-3 align-items-end">

            <input type="hidden" name="accion" value="tareas">

            <div class="col-12 col-md-4">

                <label class="form-label small text-muted mb-1">Buscar</label>

                <div class="input-group">

                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Título o descripción..."
                        value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                    >

                </div>

            </div>

            <div class="col-6 col-md-3">

                <label class="form-label small text-muted mb-1">Estado</label>

                <select name="estado" class="form-select">

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

            </div>

            <div class="col-6 col-md-3">

                <label class="form-label small text-muted mb-1">Prioridad</label>

                <select name="prioridad" class="form-select">

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

            </div>

            <div class="col-12 col-md-2 d-flex gap-2">

                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel"></i>
                    Aplicar
                </button>

                <a href="index.php?accion=tareas" class="btn btn-outline-secondary" title="Limpiar filtros">
                    <i class="bi bi-x-lg"></i>
                </a>

            </div>

        </form>

    </div>

</div>

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
                $colorPrioridad = 'danger';
                break;

            case 'Media':
                $colorPrioridad = 'warning';
                break;

            default:
                $colorPrioridad = 'success';
        }

        switch ($tarea['estado']) {

            case 'Pendiente':
                $colorEstado = 'secondary';
                $iconoEstado = 'bi-hourglass-split';
                break;

            case 'En progreso':
                $colorEstado = 'primary';
                $iconoEstado = 'bi-arrow-repeat';
                break;

            case 'Completada':
                $colorEstado = 'success';
                $iconoEstado = 'bi-check-circle-fill';
                break;

            default:
                $colorEstado = 'dark';
                $iconoEstado = 'bi-x-circle-fill';
        }

        $fechaLimiteTexto = '';

        if ($tarea['estado'] == 'Pendiente' || $tarea['estado'] == 'En progreso') {

            $fechaLimite = new DateTime($tarea['fecha_limite']);
            $hoy = new DateTime(date('Y-m-d'));

            $dias = (int) $hoy->diff($fechaLimite)->format('%r%a');

            if ($dias == 0) {
                $fechaLimiteTexto = "🔥 Vence hoy";
            } elseif ($dias == 1) {
                $fechaLimiteTexto = "🌅 Vence mañana";
            } elseif ($dias > 1 && $dias <= 7) {
                $fechaLimiteTexto = "⏳ Vence en $dias días";
            } elseif ($dias < 0) {
                $fechaLimiteTexto = "🔴 Venció hace " . abs($dias) . " días";
            } else {
                $fechaLimiteTexto = "📅 " . date('d/m/Y', strtotime($tarea['fecha_limite']));
            }
        }

    ?>

    <div class="col-md-6 col-lg-4">

        <div class="card h-100 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start mb-2">

                    <h5 class="card-title mb-0">
                        <?= htmlspecialchars($tarea['titulo']) ?>
                    </h5>

                    <span class="badge bg-<?= $colorPrioridad ?>">
                        <?= htmlspecialchars($tarea['prioridad']) ?>
                    </span>

                </div>

                <p class="text-muted mb-3">
                    <?= htmlspecialchars($tarea['descripcion']) ?>
                </p>

                <?php if (!empty($fechaLimiteTexto)): ?>

                    <p class="mb-2">
                        <?= $fechaLimiteTexto ?>
                    </p>

                <?php endif; ?>

                <p class="mb-3">
                    <span class="badge bg-<?= $colorEstado ?>">
                        <i class="bi <?= $iconoEstado ?>"></i>
                        <?= htmlspecialchars($tarea['estado']) ?>
                    </span>
                </p>

                <div class="d-flex justify-content-between">

                    <a
                        href="index.php?accion=editarTarea&id=<?= (int) $tarea['id'] ?>"
                        class="btn btn-outline-primary btn-sm">

                        ✏️ Editar

                    </a>

                    <form
                        action="index.php?accion=eliminarTarea&id=<?= (int) $tarea['id'] ?>"
                        method="POST"
                        onsubmit="return confirm('¿Seguro que deseas eliminar esta tarea?')"
                        style="display:inline;">

                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            🗑️ Eliminar
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

<?php endforeach; ?>

</div>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>