<?php
$titulo = "Mis actividades";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

    <h2 class="mb-0">📅 Mis Actividades</h2>

    <a href="index.php?accion=crearActividad" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nueva actividad
    </a>

</div>

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <form method="GET" action="index.php" class="row g-3 align-items-end">

            <input type="hidden" name="accion" value="actividades">

            <div class="col-12 col-md-8">

                <label class="form-label small text-muted mb-1">Buscar</label>

                <div class="input-group">

                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Título, descripción o lugar..."
                        value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
                    >

                </div>

            </div>

            <div class="col-8 col-md-2">

                <label class="form-label small text-muted mb-1">Fecha</label>

                <select name="fecha" class="form-select">

                    <option value="">Todas</option>

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

            </div>

            <div class="col-4 col-md-2 d-flex gap-2">

                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel"></i>
                    Aplicar
                </button>

                <a href="index.php?accion=actividades" class="btn btn-outline-secondary" title="Limpiar filtros">
                    <i class="bi bi-x-lg"></i>
                </a>

            </div>

        </form>

    </div>

</div>

<?php if (empty($actividades)): ?>

    <div class="alert alert-info">
        📅 No tienes actividades registradas.
    </div>

<?php else: ?>

<div class="row g-4">

<?php foreach ($actividades as $actividad): ?>

<?php

$fechaActividad = new DateTime($actividad['fecha']);
$hoy = new DateTime(date('Y-m-d'));

$dias = (int)$hoy->diff($fechaActividad)->format('%r%a');

if ($dias == 0) {

    $textoFecha = "📅 Hoy";

} elseif ($dias == 1) {

    $textoFecha = "🌅 Mañana";

} elseif ($dias > 1 && $dias <= 7) {

    $textoFecha = "📆 En $dias días";

} elseif ($dias < 0) {

    $textoFecha = "📂 Hace " . abs($dias) . " días";

} else {

    $textoFecha = "📅 " . date('d/m/Y', strtotime($actividad['fecha']));

}

?>

<div class="col-md-6 col-lg-4">

    <div class="card h-100 shadow-sm">

        <div class="card-body">

            <h5 class="card-title">
                <?= htmlspecialchars($actividad['titulo']) ?>
            </h5>

            <p class="text-muted mb-3">
                <?= htmlspecialchars($actividad['descripcion']) ?>
            </p>

            <p class="mb-2">
                <i class="bi bi-geo-alt-fill text-danger"></i>
                <?= htmlspecialchars($actividad['lugar']) ?>
            </p>

            <p class="mb-2">
                <?= $textoFecha ?>
            </p>

            <p class="mb-3">
                <i class="bi bi-clock"></i>

                <?= date('g:i A', strtotime($actividad['hora_inicio'])) ?>

                -

                <?= date('g:i A', strtotime($actividad['hora_fin'])) ?>
            </p>

            <div class="d-flex justify-content-between">

                <a
                    href="index.php?accion=editarActividad&id=<?= (int) $actividad['id'] ?>"
                    class="btn btn-outline-primary btn-sm">

                    ✏️ Editar

                </a>

                <form
                    action="index.php?accion=eliminarActividad&id=<?= (int) $actividad['id'] ?>"
                    method="POST"
                    onsubmit="return confirm('¿Eliminar actividad?')"
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