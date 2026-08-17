<?php

require_once __DIR__ . '/../config/Database.php';

class Tarea
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // Obtener todas las tareas DE UN USUARIO
    public function listar($usuarioId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM tareas WHERE usuario_id = :usuario_id ORDER BY id DESC"
        );

        $stmt->execute(['usuario_id' => (int) $usuarioId]);

        return $stmt->fetchAll();
    }

    // Buscar una tarea por ID, verificando que pertenezca al usuario
    public function buscarPorId($id, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM tareas WHERE id = :id AND usuario_id = :usuario_id"
        );

        $stmt->execute([
            'id' => (int) $id,
            'usuario_id' => (int) $usuarioId,
        ]);

        $tarea = $stmt->fetch();

        return $tarea ?: null;
    }

    // Crear una tarea para un usuario
    public function crear($datos, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tareas (titulo, descripcion, prioridad, fecha_limite, estado, usuario_id)
             VALUES (:titulo, :descripcion, :prioridad, :fecha_limite, :estado, :usuario_id)"
        );

        $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'prioridad' => $datos['prioridad'],
            'fecha_limite' => $datos['fecha_limite'],
            'estado' => 'Pendiente',
            'usuario_id' => (int) $usuarioId,
        ]);

        return $this->buscarPorId($this->db->lastInsertId(), $usuarioId);
    }

    // Editar una tarea, verificando que pertenezca al usuario
    public function actualizar($id, $datos, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "UPDATE tareas
             SET titulo = :titulo,
                 descripcion = :descripcion,
                 prioridad = :prioridad,
                 fecha_limite = :fecha_limite,
                 estado = :estado
             WHERE id = :id AND usuario_id = :usuario_id"
        );

        return $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'prioridad' => $datos['prioridad'],
            'fecha_limite' => $datos['fecha_limite'],
            'estado' => $datos['estado'],
            'id' => (int) $id,
            'usuario_id' => (int) $usuarioId,
        ]);
    }

    // Eliminar una tarea, verificando que pertenezca al usuario
    public function eliminar($id, $usuarioId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM tareas WHERE id = :id AND usuario_id = :usuario_id"
        );

        return $stmt->execute([
            'id' => (int) $id,
            'usuario_id' => (int) $usuarioId,
        ]);
    }
}
