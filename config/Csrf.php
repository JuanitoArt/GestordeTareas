<?php

// Protección CSRF: un token aleatorio por sesión que todo formulario POST
// debe incluir. Sin este token (o con uno incorrecto), el router rechaza
// la petición. Esto evita que un sitio externo pueda, por ejemplo, mandar
// una petición de "eliminar tarea" a nombre de un usuario que tiene la
// sesión abierta, solo con que visite una página maliciosa.
class Csrf
{
    // Devuelve el token de la sesión actual, generando uno nuevo si aún
    // no existe (se mantiene igual mientras dure la sesión).
    public static function token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    // Imprime el <input type="hidden"> listo para pegar dentro de un <form>.
    public static function campoOculto()
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token()) . '">';
    }

    // Compara el token recibido con el de la sesión. hash_equals() evita
    // que la comparación sea vulnerable a ataques de timing.
    public static function validar($tokenRecibido)
    {
        if (empty($_SESSION['csrf_token']) || empty($tokenRecibido)) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $tokenRecibido);
    }
}