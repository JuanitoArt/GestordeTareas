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
        // FIX: LOCK_EX evita que dos escrituras simultáneas corrompan el archivo
        // o se pisen entre sí.
        $resultado = file_put_contents(
            $archivo,
            json_encode(
                $datos,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ),
            LOCK_EX
        );

        // FIX: antes un fallo al escribir (permisos, disco lleno, etc.)
        // pasaba completamente desapercibido.
        if ($resultado === false) {
            throw new RuntimeException("No se pudo guardar el archivo: {$archivo}");
        }

        return true;
    }

    public static function siguienteId($datos)
    {
        if (empty($datos)) {
            return 1;
        }

        $ids = array_map('intval', array_column($datos, 'id'));

        return max($ids) + 1;
    }
}
