<?php

require_once __DIR__ . '/../models/Actividad.php';

class ActividadController
{
    private $actividad;

    public function __construct()
    {
        $this->actividad = new Actividad();
    }

    public function listar($usuarioId)
    {
        return $this->actividad->listar($usuarioId);
    }

    public function buscarPorId($id, $usuarioId)
    {
        return $this->actividad->buscarPorId($id, $usuarioId);
    }

    public function crear($datos, $usuarioId)
    {
        return $this->actividad->crear($datos, $usuarioId);
    }

    public function actualizar($id, $datos, $usuarioId)
    {
        return $this->actividad->actualizar($id, $datos, $usuarioId);
    }

    public function eliminar($id, $usuarioId)
    {
        return $this->actividad->eliminar($id, $usuarioId);
    }
}
