<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar actividad</title>

</head>

<body>

<h1>✏️ Editar actividad</h1>


<form action="index.php?accion=actualizarActividad&id=<?= $actividad['id'] ?>" method="POST">


    <label>
        Título:
    </label>

    <br>

    <input
        type="text"
        name="titulo"
        value="<?= htmlspecialchars($actividad['titulo']) ?>"
        required
    >


    <br><br>


    <label>
        Descripción:
    </label>

    <br>

    <textarea
        name="descripcion"
        rows="4"
        required
    ><?= htmlspecialchars($actividad['descripcion']) ?></textarea>


    <br><br>


    <label>
        Fecha:
    </label>

    <br>

    <input
        type="date"
        name="fecha"
        value="<?= $actividad['fecha'] ?>"
        required
    >


    <br><br>


    <label>
        Hora inicio:
    </label>

    <br>

    <input
        type="time"
        name="hora_inicio"
        value="<?= $actividad['hora_inicio'] ?>"
        required
    >


    <br><br>


    <label>
        Hora fin:
    </label>

    <br>

    <input
        type="time"
        name="hora_fin"
        value="<?= $actividad['hora_fin'] ?>"
        required
    >


    <br><br>


    <label>
        Lugar:
    </label>

    <br>

    <input
        type="text"
        name="lugar"
        value="<?= htmlspecialchars($actividad['lugar']) ?>"
        required
    >


    <br><br>


    <button type="submit">
        💾 Actualizar actividad
    </button>


</form>


<br>


<a href="index.php?accion=actividades">
    ← Volver
</a>


</body>

</html>