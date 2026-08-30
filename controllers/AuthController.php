<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    private $usuario;

    // Paletas de colores disponibles. Se valida contra esta lista tanto en
    // el controlador como en el router, para que nunca se guarde un valor
    // arbitrario en la base de datos.
    public const TEMAS_VALIDOS = ['indigo', 'azul', 'verde', 'rosa', 'naranja'];

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    // Registra un nuevo usuario. Devuelve ['exito' => bool, 'error' => string|null]
    public function registrar($datos)
    {
        $nombreUsuario = trim($datos['nombre_usuario'] ?? '');
        $correo = trim($datos['correo'] ?? '');
        $password = $datos['password'] ?? '';
        $passwordConfirmar = $datos['password_confirmar'] ?? '';

        // Validaciones básicas de campos vacíos
        if ($nombreUsuario === '' || $correo === '' || $password === '') {
            return ['exito' => false, 'error' => 'Todos los campos son obligatorios.'];
        }

        if (strlen($nombreUsuario) < 3) {
            return ['exito' => false, 'error' => 'El nombre de usuario debe tener al menos 3 caracteres.'];
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return ['exito' => false, 'error' => 'El correo no es válido.'];
        }

        // Contraseña: mínimo 8 caracteres, con al menos una letra y un número
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password)) {
            return [
                'exito' => false,
                'error' => 'La contraseña debe tener mínimo 8 caracteres, incluyendo al menos una letra y un número.'
            ];
        }

        if ($password !== $passwordConfirmar) {
            return ['exito' => false, 'error' => 'Las contraseñas no coinciden.'];
        }

        if ($this->usuario->existe($nombreUsuario, $correo)) {
            return ['exito' => false, 'error' => 'Ya existe una cuenta con ese usuario o correo.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // FIX: aunque ya validamos con existe() arriba, si dos personas se
        // registran casi al mismo tiempo con el mismo usuario/correo, el
        // segundo INSERT puede chocar con la restricción UNIQUE de la tabla.
        // Antes eso lanzaba una PDOException sin capturar (error fatal feo);
        // ahora se devuelve como el mismo mensaje de "ya existe".
        try {
            $this->usuario->crear($nombreUsuario, $correo, $passwordHash);
        } catch (PDOException $e) {
            return ['exito' => false, 'error' => 'Ya existe una cuenta con ese usuario o correo.'];
        }

        return ['exito' => true, 'error' => null];
    }

    // Inicia sesión. Devuelve ['exito' => bool, 'error' => string|null]
    public function iniciarSesion($identificador, $password)
    {
        $identificador = trim($identificador ?? '');

        if ($identificador === '' || $password === '') {
            return ['exito' => false, 'error' => 'Ingresa tu usuario/correo y tu contraseña.'];
        }

        $usuario = $this->usuario->buscarPorUsuarioOCorreo($identificador);

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            return ['exito' => false, 'error' => 'Usuario/correo o contraseña incorrectos.'];
        }

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        $_SESSION['tema'] = $usuario['tema'] ?? 'indigo';

        return ['exito' => true, 'error' => null];
    }

    public function cerrarSesion()
    {
        $_SESSION = [];
        session_destroy();
    }

    // Cambia la paleta de colores del usuario. Devuelve true/false según
    // si el valor recibido es una paleta válida.
    public function actualizarTema($usuarioId, $tema)
    {
        if (!in_array($tema, self::TEMAS_VALIDOS, true)) {
            return false;
        }

        $this->usuario->actualizarTema($usuarioId, $tema);
        $_SESSION['tema'] = $tema;

        return true;
    }
}