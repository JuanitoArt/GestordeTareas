<?php

require_once __DIR__ . '/../config/JsonManager.php';

class Tarea
{
    private $archivo;

    public function __construct()
    {
        $this->archivo = __DIR__ . '/../database/tareas.json';
    }

    // Obtener todas las tareas
    public function listar()
    {
        return JsonManager::leer($this->archivo);
    }

    // Buscar una tarea por ID
    public function buscarPorId($id)
    {
        $tareas = $this->listar();

        foreach ($tareas as $tarea) {
            if ((int) $tarea['id'] === (int) $id) {
                return $tarea;
            }
        }

        return null;
    }

    // Crear una tarea
    public function crear($datos)
    {
        $tareas = $this->listar();

        $nuevaTarea = [
            'id' => JsonManager::siguienteId($tareas),
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'prioridad' => $datos['prioridad'],
            'fecha_limite' => $datos['fecha_limite'],
            'estado' => 'Pendiente'
        ];

        $tareas[] = $nuevaTarea;

        JsonManager::guardar($this->archivo, $tareas);

        return $nuevaTarea;
    }

    // Editar una tarea
    public function actualizar($id, $datos)
    {
        $tareas = $this->listar();

        foreach ($tareas as $indice => $tarea) {

            if ((int) $tarea['id'] === (int) $id) {

                $tareas[$indice]['titulo'] = $datos['titulo'];
                $tareas[$indice]['descripcion'] = $datos['descripcion'];
                $tareas[$indice]['prioridad'] = $datos['prioridad'];
                $tareas[$indice]['fecha_limite'] = $datos['fecha_limite'];
                $tareas[$indice]['estado'] = $datos['estado'];

                JsonManager::guardar($this->archivo, $tareas);

                return true;
            }
        }

        return false;
    }

    // Eliminar una tarea
    public function eliminar($id)
    {
        $tareas = $this->listar();

        foreach ($tareas as $indice => $tarea) {

            if ((int) $tarea['id'] === (int) $id) {

                unset($tareas[$indice]);

                $tareas = array_values($tareas);

                JsonManager::guardar($this->archivo, $tareas);

                return true;
            }
        }

        return false;
    }
}
