<?php

class JsonManager
{
    public static function leer($archivo)
    {
        if (!file_exists($archivo)) {
            return [];
        }

        $contenido = file_get_contents($archivo);

        if (empty($contenido)) {
            return [];
        }

        return json_decode($contenido, true) ?? [];
    }

    public static function guardar($archivo, $datos)
    {
        file_put_contents(
            $archivo,
            json_encode(
                $datos,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );
    }

    public static function siguienteId($datos)
    {
        if (empty($datos)) {
            return 1;
        }

        $ids = array_column($datos, 'id');

        return max($ids) + 1;
    }
}