<?php
$titulo = "Mi Día";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="mb-4">

    <h2 class="fw-bold">
        📅 Mi Día
    </h2>

    <p class="text-muted">
        <?= date('l, d \d\e F') ?>
    </p>

</div>

<?php if(empty($actividades)): ?>

<div class="card shadow-sm">

    <div class="card-body text-center py-5">

        <h1>🎉</h1>

        <h4>No tienes actividades para hoy</h4>

        <p class="text-muted">
            Disfruta tu día.
        </p>

    </div>

</div>

<?php else: ?>

<div class="timeline">

<?php foreach($actividades as $actividad): ?>

    <?php

$ahora = new DateTime();

$inicio = new DateTime(
    $actividad['fecha'] . ' ' . $actividad['hora_inicio']
);

$fin = new DateTime(
    $actividad['fecha'] . ' ' . $actividad['hora_fin']
);

if ($ahora > $fin) {

    $estado = "Finalizada";
    $color = "secondary";
    $mensaje = "✔ Finalizada";

} elseif ($ahora >= $inicio && $ahora <= $fin) {

    $estado = "En curso";
    $color = "success";
    $mensaje = "🟢 En curso";

} else {

    $estado = "Pendiente";
    $color = "primary";

    $intervalo = $ahora->diff($inicio);

    if ($intervalo->days == 0) {

        $mensaje = "⏳ Empieza en "
            . $intervalo->h . " h "
            . $intervalo->i . " min";

    } else {

        $mensaje = "⏳ Más tarde";

    }

}

?>

<?php

$titulo = strtolower($actividad['titulo']);

if (str_contains($titulo, 'estudi')) {

    $icono = "bi-book";

} elseif (str_contains($titulo, 'trabaj') || str_contains($titulo, 'proyecto')) {

    $icono = "bi-briefcase";

} elseif (str_contains($titulo, 'gim') || str_contains($titulo, 'ejercicio')) {

    $icono = "bi-heart-pulse";

} elseif (str_contains($titulo, 'com') || str_contains($titulo, 'almuerzo') || str_contains($titulo, 'cena')) {

    $icono = "bi-cup-hot";

} elseif (str_contains($titulo, 'viaj') || str_contains($titulo, 'salir')) {

    $icono = "bi-car-front";

} elseif (str_contains($titulo, 'reun')) {

    $icono = "bi-people";

} else {

    $icono = "bi-calendar-event";

}

?>

<div class="timeline-item mb-4">

    <div class="card shadow-sm border-start border-5 border-<?= $color ?>">

        <div class="card-body">

          <div class="d-flex justify-content-between align-items-start">

    <div class="d-flex align-items-center">

       <?php

$fondoIcono = match ($color) {
    'success' => '#E8F8EE',   // Verde muy suave
    'primary' => '#EAF2FF',   // Azul muy suave
    'secondary' => '#F1F3F5', // Gris muy suave
    default => '#F8F9FA'
};

?>

<div
    class="d-flex align-items-center justify-content-center me-3 rounded-4"
    style="
        width:70px;
        height:70px;
        background: <?= $fondoIcono ?>;
        font-size:32px;
    ">

    <i class="bi <?= $icono ?>"></i>

</div>

        <div>

            <h4 class="fw-bold mb-1">

                <?= htmlspecialchars($actividad['titulo']) ?>

            </h4>

            <p class="text-muted mb-0">

                📍 <?= htmlspecialchars($actividad['lugar']) ?>

            </p>

        </div>

    </div>

    <div class="text-end">

        <h3 class="fw-bold mb-1">

            <?= substr($actividad['hora_inicio'], 0, 5) ?>

        </h3>

        <small class="text-<?= $color ?> fw-semibold">

            <?= $mensaje ?>

        </small>

    </div>

</div>
            <?php if(!empty($actividad['descripcion'])): ?>

            <div class="mt-4 pt-3 border-top">

                <p class="mb-0">

                <?= htmlspecialchars($actividad['descripcion']) ?>

                </p>

            </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>