<?php

require_once __DIR__ . '/../models/Tarea.php';

class TareaController
{
    private $tarea;

    public function __construct()
    {
        $this->tarea = new Tarea();
    }

    // Listar todas las tareas
    public function listar()
    {
        return $this->tarea->listar();
    }

    // Buscar una tarea por ID
    public function buscarPorId($id)
    {
        return $this->tarea->buscarPorId($id);
    }

    // Crear una tarea
    public function crear($datos)
    {
        return $this->tarea->crear($datos);
    }

    // Actualizar una tarea
    public function actualizar($id, $datos)
    {
        return $this->tarea->actualizar($id, $datos);
    }

    // Eliminar una tarea
    public function eliminar($id)
    {
        return $this->tarea->eliminar($id);
    }
}