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

    // Actualiza la paleta de colores elegida por el usuario
    public function actualizarTema($id, $tema)
    {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET tema = :tema WHERE id = :id"
        );

        return $stmt->execute([
            'tema' => $tema,
            'id' => (int) $id,
        ]);
    }

    // Guarda el hash de un token de "recordarme" para un usuario
    public function guardarTokenRecordar($usuarioId, $tokenHash, $expiraEn)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tokens_recordar (usuario_id, token_hash, expira_en)
             VALUES (:usuario_id, :token_hash, :expira_en)"
        );

        return $stmt->execute([
            'usuario_id' => (int) $usuarioId,
            'token_hash' => $tokenHash,
            'expira_en' => $expiraEn,
        ]);
    }

    // Busca un usuario a partir de un token de "recordarme" válido y no vencido
    public function buscarPorTokenRecordar($usuarioId, $tokenHash)
    {
        $stmt = $this->db->prepare(
            "SELECT u.* FROM usuarios u
             INNER JOIN tokens_recordar t ON t.usuario_id = u.id
             WHERE t.usuario_id = :usuario_id
               AND t.token_hash = :token_hash
               AND t.expira_en > NOW()"
        );

        $stmt->execute([
            'usuario_id' => (int) $usuarioId,
            'token_hash' => $tokenHash,
        ]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    // Elimina todos los tokens de "recordarme" de un usuario (al cerrar sesión)
    public function eliminarTokensRecordar($usuarioId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM tokens_recordar WHERE usuario_id = :usuario_id"
        );

        return $stmt->execute(['usuario_id' => (int) $usuarioId]);
    }
}