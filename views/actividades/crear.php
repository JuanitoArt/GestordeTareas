<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear actividad</title>

</head>

<body>

<h1>➕ Nueva actividad</h1>


<form action="index.php?accion=guardarActividad" method="POST">


    <label>
        Título:
    </label>

    <br>

    <input 
        type="text" 
        name="titulo"
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
    ></textarea>


    <br><br>


    <label>
        Fecha:
    </label>

    <br>

    <input 
        type="date"
        name="fecha"
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
    required
>

    <br><br>


    <button type="submit">
        💾 Guardar actividad
    </button>


</form>


<br>


<a href="index.php?accion=actividades">
    ← Volver
</a>


</body>

</html>