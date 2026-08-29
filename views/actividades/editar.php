<?php

$titulo = "Editar actividad";

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

/** @var array $actividad */

?>

<div class="row justify-content-center">

    <div class="col-md-8 col-lg-6">

        <div class="card shadow-sm">

            <div class="card-body p-4">

                <h2 class="mb-4">
                    ✏️ Editar actividad
                </h2>

                <form action="index.php?accion=actualizarActividad&id=<?= (int) $actividad['id'] ?>" method="POST">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            class="form-control"
                            value="<?= htmlspecialchars($actividad['titulo']) ?>"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            class="form-control"
                            rows="4"
                            required
                        ><?= htmlspecialchars($actividad['descripcion']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input
                            type="date"
                            id="fecha"
                            name="fecha"
                            class="form-control"
                            value="<?= htmlspecialchars($actividad['fecha']) ?>"
                            required
                        >
                    </div>

                    <div class="row">

                        <div class="col-6 mb-3">
                            <label for="hora_inicio" class="form-label">Hora inicio</label>
                            <input
                                type="time"
                                id="hora_inicio"
                                name="hora_inicio"
                                class="form-control"
                                value="<?= htmlspecialchars($actividad['hora_inicio']) ?>"
                                required
                            >
                        </div>

                        <div class="col-6 mb-3">
                            <label for="hora_fin" class="form-label">Hora fin</label>
                            <input
                                type="time"
                                id="hora_fin"
                                name="hora_fin"
                                class="form-control"
                                value="<?= htmlspecialchars($actividad['hora_fin']) ?>"
                                required
                            >
                        </div>

                    </div>

                    <div class="mb-4">
                        <label for="lugar" class="form-label">Lugar</label>
                        <input
                            type="text"
                            id="lugar"
                            name="lugar"
                            class="form-control"
                            value="<?= htmlspecialchars($actividad['lugar']) ?>"
                            required
                        >
                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="index.php?accion=actividades" class="btn btn-outline-secondary">
                            ← Volver
                        </a>

                        <button type="submit" class="btn btn-primary">
                            💾 Actualizar actividad
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>