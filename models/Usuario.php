<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConexion();
    }

    // Busca un usuario por su nombre de usuario O su correo (para el login)
    public function buscarPorUsuarioOCorreo($identificador)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE nombre_usuario = :identificador OR correo = :identificador"
        );

        $stmt->execute(['identificador' => $identificador]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    // Verifica si ya existe alguien con ese nombre de usuario o correo (para el registro)
    public function existe($nombreUsuario, $correo)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :nombre_usuario OR correo = :correo"
        );

        $stmt->execute([
            'nombre_usuario' => $nombreUsuario,
            'correo' => $correo,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Crea un nuevo usuario. Se espera que $passwordHash ya venga procesado con password_hash().
    public function crear($nombreUsuario, $correo, $passwordHash)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nombre_usuario, correo, password_hash)
             VALUES (:nombre_usuario, :correo, :password_hash)"
        );

        $stmt->execute([
            'nombre_usuario' => $nombreUsuario,
            'correo' => $correo,
            'password_hash' => $passwordHash,
        ]);

        return (int) $this->db->lastInsertId();
    }
}
