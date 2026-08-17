<?php
$titulo = "Crear cuenta";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center">

    <div class="col-md-6 col-lg-5">

        <div class="card mt-5 shadow-sm">

            <div class="card-body p-4">

                <h2 class="text-center mb-4">
                    📝 Crear cuenta
                </h2>

                <?php if (!empty($error)): ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php endif; ?>

                <form action="index.php?accion=procesarRegistro" method="POST">

                    <div class="mb-3">
                        <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
                        <input
                            type="text"
                            id="nombre_usuario"
                            name="nombre_usuario"
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['nombre_usuario'] ?? '') ?>"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            required
                        >
                        <div class="form-text">
                            Mínimo 8 caracteres, con al menos una letra y un número.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmar" class="form-label">Confirmar contraseña</label>
                        <input
                            type="password"
                            id="password_confirmar"
                            name="password_confirmar"
                            class="form-control"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Crear cuenta
                    </button>

                </form>

                <p class="text-center mt-3 mb-0">
                    ¿Ya tienes cuenta?
                    <a href="index.php?accion=login">Inicia sesión</a>
                </p>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
