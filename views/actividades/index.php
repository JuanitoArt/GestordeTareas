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

                <!-- FIX: eliminar ahora es un form POST, no un link GET -->
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
