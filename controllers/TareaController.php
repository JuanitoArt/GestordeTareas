<?php

require_once __DIR__ . '/../models/Tarea.php';

class TareaController
{
    private $tarea;

    public function __construct()
    {
        $this->tarea = new Tarea();
    }

    public function listar($usuarioId)
    {
        return $this->tarea->listar($usuarioId);
    }

    public function buscarPorId($id, $usuarioId)
    {
        return $this->tarea->buscarPorId($id, $usuarioId);
    }

    public function crear($datos, $usuarioId)
    {
        return $this->tarea->crear($datos, $usuarioId);
    }

    public function actualizar($id, $datos, $usuarioId)
    {
        return $this->tarea->actualizar($id, $datos, $usuarioId);
    }

    public function eliminar($id, $usuarioId)
    {
        return $this->tarea->eliminar($id, $usuarioId);
    }
}
