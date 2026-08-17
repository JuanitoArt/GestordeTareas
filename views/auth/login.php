<?php
$titulo = "Iniciar sesión";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center">

    <div class="col-md-6 col-lg-5">

        <div class="card mt-5 shadow-sm">

            <div class="card-body p-4">

                <h2 class="text-center mb-4">
                    🔐 Iniciar sesión
                </h2>

                <?php if (!empty($error)): ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php endif; ?>

                <?php if (!empty($mensaje)): ?>

                    <div class="alert alert-success">
                        <?= htmlspecialchars($mensaje) ?>
                    </div>

                <?php endif; ?>

                <form action="index.php?accion=procesarLogin" method="POST">

                    <div class="mb-3">
                        <label for="identificador" class="form-label">Usuario o correo</label>
                        <input
                            type="text"
                            id="identificador"
                            name="identificador"
                            class="form-control"
                            required
                            autofocus
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
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Entrar
                    </button>

                </form>

                <p class="text-center mt-3 mb-0">
                    ¿No tienes cuenta?
                    <a href="index.php?accion=registro">Regístrate</a>
                </p>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
