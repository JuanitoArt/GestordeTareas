<?php

require_once __DIR__ . '/../config/Database.php';

class Actividad
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // Obtener todas las actividades
    public function listar()
    {
        $stmt = $this->db->query("SELECT * FROM actividades ORDER BY fecha, hora_inicio");

        return $stmt->fetchAll();
    }

    // Buscar una actividad por ID
    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM actividades WHERE id = :id");
        $stmt->execute(['id' => (int) $id]);

        $actividad = $stmt->fetch();

        return $actividad ?: null;
    }

    // Crear una actividad
    public function crear($datos)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO actividades (titulo, descripcion, fecha, hora_inicio, hora_fin, lugar)
             VALUES (:titulo, :descripcion, :fecha, :hora_inicio, :hora_fin, :lugar)"
        );

        $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'fecha' => $datos['fecha'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $datos['hora_fin'],
            'lugar' => $datos['lugar'],
        ]);

        return $this->buscarPorId($this->db->lastInsertId());
    }

    // Eliminar una actividad
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM actividades WHERE id = :id");

        return $stmt->execute(['id' => (int) $id]);
    }

    // Actualizar una actividad
    public function actualizar($id, $datos)
    {
        $stmt = $this->db->prepare(
            "UPDATE actividades
             SET titulo = :titulo,
                 descripcion = :descripcion,
                 fecha = :fecha,
                 hora_inicio = :hora_inicio,
                 hora_fin = :hora_fin,
                 lugar = :lugar
             WHERE id = :id"
        );

        return $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'fecha' => $datos['fecha'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $datos['hora_fin'],
            'lugar' => $datos['lugar'],
            'id' => (int) $id,
        ]);
    }
}
