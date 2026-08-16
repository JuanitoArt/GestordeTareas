<?php

require_once __DIR__ . '/../config/Database.php';

class Tarea
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // Obtener todas las tareas
    public function listar()
    {
        $stmt = $this->db->query("SELECT * FROM tareas ORDER BY id DESC");

        return $stmt->fetchAll();
    }

    // Buscar una tarea por ID
    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tareas WHERE id = :id");
        $stmt->execute(['id' => (int) $id]);

        $tarea = $stmt->fetch();

        return $tarea ?: null;
    }

    // Crear una tarea
    public function crear($datos)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tareas (titulo, descripcion, prioridad, fecha_limite, estado)
             VALUES (:titulo, :descripcion, :prioridad, :fecha_limite, :estado)"
        );

        $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'prioridad' => $datos['prioridad'],
            'fecha_limite' => $datos['fecha_limite'],
            'estado' => 'Pendiente',
        ]);

        return $this->buscarPorId($this->db->lastInsertId());
    }

    // Editar una tarea
    public function actualizar($id, $datos)
    {
        $stmt = $this->db->prepare(
            "UPDATE tareas
             SET titulo = :titulo,
                 descripcion = :descripcion,
                 prioridad = :prioridad,
                 fecha_limite = :fecha_limite,
                 estado = :estado
             WHERE id = :id"
        );

        return $stmt->execute([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'prioridad' => $datos['prioridad'],
            'fecha_limite' => $datos['fecha_limite'],
            'estado' => $datos['estado'],
            'id' => (int) $id,
        ]);
    }

    // Eliminar una tarea
    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tareas WHERE id = :id");

        return $stmt->execute(['id' => (int) $id]);
    }
}
