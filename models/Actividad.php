<?php

require_once __DIR__ . '/../config/JsonManager.php';

class Actividad
{
    private $archivo;

    public function __construct()
    {
        $this->archivo = __DIR__ . '/../database/actividades.json';
    }

    // Obtener todas las actividades
    public function listar()
    {
        return JsonManager::leer($this->archivo);
    }

    // Buscar una actividad por ID
    public function buscarPorId($id)
    {
        $actividades = $this->listar();

        foreach ($actividades as $actividad) {
            if ($actividad['id'] == $id) {
                return $actividad;
            }
        }

        return null;
    }

    // Crear una actividad
public function crear($datos)
{
    $actividades = $this->listar();

    $nuevaActividad = [
    'id' => JsonManager::siguienteId($actividades),
    'titulo' => $datos['titulo'],
    'descripcion' => $datos['descripcion'],
    'fecha' => $datos['fecha'],
    'hora_inicio' => $datos['hora_inicio'],
    'hora_fin' => $datos['hora_fin'],
    'lugar' => $datos['lugar']
];

    $actividades[] = $nuevaActividad;

    JsonManager::guardar($this->archivo, $actividades);

    return $nuevaActividad;
}

    // Eliminar una actividad
    public function eliminar($id)
    {
        $actividades = $this->listar();

        foreach ($actividades as $indice => $actividad) {

            if ($actividad['id'] == $id) {

                unset($actividades[$indice]);

                $actividades = array_values($actividades);

                JsonManager::guardar($this->archivo, $actividades);

                return true;
            }
        }

        return false;
    }

    // Actualizar una actividad
    public function actualizar($id, $datos)
{
    $actividades = $this->listar();

    foreach ($actividades as $indice => $actividad) {

        if ($actividad['id'] == $id) {

            $actividades[$indice] = [
                'id' => $id,
                'titulo' => $datos['titulo'],
                'descripcion' => $datos['descripcion'],
                'fecha' => $datos['fecha'],
                'hora_inicio' => $datos['hora_inicio'],
                'hora_fin' => $datos['hora_fin'],
                'lugar' => $datos['lugar']
            ];

            JsonManager::guardar($this->archivo, $actividades);

            return true;
        }
    }

    return false;
}
}