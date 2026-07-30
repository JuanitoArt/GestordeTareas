<?php

require_once __DIR__ . '/../models/Actividad.php';

class ActividadController
{
    private $actividad;

    public function __construct()
    {
        $this->actividad = new Actividad();
    }

    // Listar todas las actividades
    public function listar()
    {
        return $this->actividad->listar();
    }

    // Buscar una actividad por ID
    public function buscarPorId($id)
    {
        return $this->actividad->buscarPorId($id);
    }

    // Crear una actividad
    public function crear($datos)
    {
        return $this->actividad->crear($datos);
    }

    // Actualizar una actividad
    public function actualizar($id, $datos)
    {
        return $this->actividad->actualizar($id, $datos);
    }

    // Eliminar una actividad
    public function eliminar($id)
    {
        return $this->actividad->eliminar($id);
    }
}