<?php

require_once __DIR__ . '/../config/Database.php';

class Actividad
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // Obtener todas las actividades DE UN USUARIO
    public function listar($usuarioId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM actividades WHERE usuario_id = :usuario_id ORDER BY fecha, hora_inicio"
        );

        $stmt->execute(['usuario_id' => (int) $usuarioId]);

        return $stmt->fetchAll();
    }

    // Buscar una actividad por ID, verificando que pertenezca al usuario
    public function buscarPorId($id, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM actividades WHERE id = :id AND usuario_id = :usuario_id"
        );

        $stmt->execute([
            'id' => (int) $id,
            'usuario_id' => (int) $usuarioId,
        ]);

        $actividad = $stmt->fetch();

        return $actividad ?: null;
    }

    // Crear una actividad para un usuario
    public function crear($datos, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO actividades (titulo, descripcion, fecha, hora_inicio, hora_fin, lugar, usuario_id)
             VALUES (:titulo, :descripcion, :fecha, :hora_inicio, :hora_fin, :lugar, :usuario_id)"
        );

        $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'fecha' => $datos['fecha'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $datos['hora_fin'],
            'lugar' => $datos['lugar'],
            'usuario_id' => (int) $usuarioId,
        ]);

        return $this->buscarPorId($this->db->lastInsertId(), $usuarioId);
    }

    // Eliminar una actividad, verificando que pertenezca al usuario
    public function eliminar($id, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM actividades WHERE id = :id AND usuario_id = :usuario_id"
        );

        return $stmt->execute([
            'id' => (int) $id,
            'usuario_id' => (int) $usuarioId,
        ]);
    }

    // Actualizar una actividad, verificando que pertenezca al usuario
    public function actualizar($id, $datos, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "UPDATE actividades
             SET titulo = :titulo,
                 descripcion = :descripcion,
                 fecha = :fecha,
                 hora_inicio = :hora_inicio,
                 hora_fin = :hora_fin,
                 lugar = :lugar
             WHERE id = :id AND usuario_id = :usuario_id"
        );

        return $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'fecha' => $datos['fecha'],
            'hora_inicio' => $datos['hora_inicio'],
            'hora_fin' => $datos['hora_fin'],
            'lugar' => $datos['lugar'],
            'id' => (int) $id,
            'usuario_id' => (int) $usuarioId,
        ]);
    }
}
