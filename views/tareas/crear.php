<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear tarea</title>
</head>

<body>

    <h1>➕ Nueva tarea</h1>

  <form action="index.php?accion=guardarTarea" method="POST">

        <div>
            <label for="titulo">Título:</label>
            <br>

            <input
                type="text"
                id="titulo"
                name="titulo"
                required
            >
        </div>

        <br>

        <div>
            <label for="descripcion">Descripción:</label>
            <br>

            <textarea
                id="descripcion"
                name="descripcion"
                rows="4"
                required
            ></textarea>
        </div>

        <br>

        <div>
            <label for="prioridad">Prioridad:</label>
            <br>

            <select id="prioridad" name="prioridad" required>

                <option value="">Seleccione una prioridad</option>
                <option value="Baja">Baja</option>
                <option value="Media">Media</option>
                <option value="Alta">Alta</option>

            </select>
        </div>

        <br>

        <div>
            <label for="fecha_limite">Fecha límite:</label>
            <br>

            <input
                type="date"
                id="fecha_limite"
                name="fecha_limite"
                required
            >
        </div>

        <br>

        <button type="submit">
            💾 Guardar tarea
        </button>

    </form>

    <br>

    <a href="index.php?accion=tareas">
        ← Volver a tareas
    </a>

</body>

</html>