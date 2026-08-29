<?php

$titulo = "Editar tarea";

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

/** @var array $tarea */

?>

<div class="row justify-content-center">

    <div class="col-md-8 col-lg-6">

        <div class="card shadow-sm">

            <div class="card-body p-4">

                <h2 class="mb-4">
                    ✏️ Editar tarea
                </h2>

                <form action="index.php?accion=actualizarTarea&id=<?= (int) $tarea['id'] ?>" method="POST">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            class="form-control"
                            value="<?= htmlspecialchars($tarea['titulo']) ?>"
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
                        ><?= htmlspecialchars($tarea['descripcion']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="prioridad" class="form-label">Prioridad</label>
                        <select id="prioridad" name="prioridad" class="form-select" required>

                            <option value="Baja"
                                <?= $tarea['prioridad'] == 'Baja' ? 'selected' : '' ?>>
                                Baja
                            </option>

                            <option value="Media"
                                <?= $tarea['prioridad'] == 'Media' ? 'selected' : '' ?>>
                                Media
                            </option>

                            <option value="Alta"
                                <?= $tarea['prioridad'] == 'Alta' ? 'selected' : '' ?>>
                                Alta
                            </option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label">Fecha límite</label>
                        <input
                            type="date"
                            id="fecha_limite"
                            name="fecha_limite"
                            class="form-control"
                            value="<?= htmlspecialchars($tarea['fecha_limite']) ?>"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-select">

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
                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="index.php?accion=tareas" class="btn btn-outline-secondary">
                            ← Volver
                        </a>

                        <button type="submit" class="btn btn-primary">
                            💾 Actualizar tarea
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>