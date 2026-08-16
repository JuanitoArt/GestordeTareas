<?php
// Ejecutar UNA sola vez desde la terminal:
//   C:\xampp\php\php.exe scripts/migrar_json_a_mysql.php
// (o solo "php scripts/migrar_json_a_mysql.php" si php está en tu PATH)
//
// Requiere que ya hayas corrido database/schema.sql en phpMyAdmin antes de esto.

require_once __DIR__ . '/../config/Database.php';

$db = Database::getConexion();

$archivoTareas = __DIR__ . '/../database/tareas.json';
$archivoActividades = __DIR__ . '/../database/actividades.json';

// ── Migrar tareas ──
if (file_exists($archivoTareas)) {

    $tareas = json_decode(file_get_contents($archivoTareas), true) ?? [];

    $stmt = $db->prepare(
        "INSERT INTO tareas (titulo, descripcion, prioridad, fecha_limite, estado)
         VALUES (:titulo, :descripcion, :prioridad, :fecha_limite, :estado)"
    );

    foreach ($tareas as $tarea) {
        $stmt->execute([
            'titulo' => $tarea['titulo'],
            'descripcion' => $tarea['descripcion'],
            'prioridad' => $tarea['prioridad'],
            'fecha_limite' => $tarea['fecha_limite'],
            'estado' => $tarea['estado'],
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
        "INSERT INTO actividades (titulo, descripcion, fecha, hora_inicio, hora_fin, lugar)
         VALUES (:titulo, :descripcion, :fecha, :hora_inicio, :hora_fin, :lugar)"
    );

    foreach ($actividades as $actividad) {
        $stmt->execute([
            'titulo' => $actividad['titulo'],
            'descripcion' => $actividad['descripcion'],
            'fecha' => $actividad['fecha'],
            'hora_inicio' => $actividad['hora_inicio'],
            'hora_fin' => $actividad['hora_fin'],
            'lugar' => $actividad['lugar'],
        ]);
    }

    echo "✅ Migradas " . count($actividades) . " actividades.\n";

} else {

    echo "⚠️  No se encontró actividades.json, se omite.\n";

}

echo "🎉 Migración completa. Nota: los IDs se reasignan desde 1 (AUTO_INCREMENT), no se conservan los del JSON.\n";
