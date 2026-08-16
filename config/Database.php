<?php

class Database
{
    private static ?PDO $conexion = null;

    public static function getConexion(): PDO
    {
        if (self::$conexion === null) {

            // Configuración por defecto de XAMPP: usuario 'root' sin contraseña.
            $host = 'localhost';
            $dbname = 'gestor_tareas';
            $usuario = 'root';
            $password = '';

            try {

                self::$conexion = new PDO(
                    "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                    $usuario,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );

            } catch (PDOException $e) {

                die("Error de conexión a la base de datos: " . $e->getMessage());

            }
        }

        return self::$conexion;
    }
}
