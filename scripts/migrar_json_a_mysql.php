<?php
// Ejecutar UNA sola vez desde la terminal:
//   C:\xampp\php\php.exe scripts/migrar_json_a_mysql.php [usuario_id]
// (o solo "php scripts/migrar_json_a_mysql.php [usuario_id]" si php está en tu PATH)
//
// Requiere que ya hayas corrido database/schema.sql y database/schema_usuarios.sql
// en phpMyAdmin antes de esto (este último agrega la columna usuario_id).
//
// FIX: antes los INSERT no incluían usuario_id (la columna quedaba con su
// valor DEFAULT 1 implícito). Ahora se pide explícitamente a qué usuario
// asignar los datos migrados, para no depender en silencio del default de
// la columna. Si no se pasa nada, sigue usando 1.

require_once __DIR__ . '/../config/Database.php';

$db = Database::getConexion();

$usuarioId = isset($argv[1]) ? (int) $argv[1] : 1;

echo "ℹ️  Los datos migrados se asignarán al usuario_id = {$usuarioId}.\n";

$archivoTareas = __DIR__ . '/../database/tareas.json';
$archivoActividades = __DIR__ . '/../database/actividades.json';

// ── Migrar tareas ──
if (file_exists($archivoTareas)) {

    $tareas = json_decode(file_get_contents($archivoTareas), true) ?? [];

    $stmt = $db->prepare(
        "INSERT INTO tareas (titulo, descripcion, prioridad, fecha_limite, estado, usuario_id)
         VALUES (:titulo, :descripcion, :prioridad, :fecha_limite, :estado, :usuario_id)"
    );

    foreach ($tareas as $tarea) {
        $stmt->execute([
            'titulo' => $tarea['titulo'],
            'descripcion' => $tarea['descripcion'],
            'prioridad' => $tarea['prioridad'],
            'fecha_limite' => $tarea['fecha_limite'],
            'estado' => $tarea['estado'],
            'usuario_id' => $usuarioId,
        ]);
    }

    echo "✅ Migradas " . count($tareas) . " tareas.\n";

} else {

    echo "⚠️  No se encontró tareas.json, se omite.\n";

}

// ── Migrar actividades ──
if (file_exists($archivoActividades)) {

    $actividades = json_decode(file_get_contents($archivoActividades), true) ?? [];

    $stmt = $db->prepare(
        "INSERT INTO actividades (titulo, descripcion, fecha, hora_inicio, hora_fin, lugar, usuario_id)
         VALUES (:titulo, :descripcion, :fecha, :hora_inicio, :hora_fin, :lugar, :usuario_id)"
    );

    foreach ($actividades as $actividad) {
        $stmt->execute([
            'titulo' => $actividad['titulo'],
            'descripcion' => $actividad['descripcion'],
            'fecha' => $actividad['fecha'],
            'hora_inicio' => $actividad['hora_inicio'],
            'hora_fin' => $actividad['hora_fin'],
            'lugar' => $actividad['lugar'],
            'usuario_id' => $usuarioId,
        ]);
    }

    echo "✅ Migradas " . count($actividades) . " actividades.\n";

} else {

    echo "⚠️  No se encontró actividades.json, se omite.\n";

}

echo "🎉 Migración completa. Nota: los IDs se reasignan desde 1 (AUTO_INCREMENT), no se conservan los del JSON.\n";