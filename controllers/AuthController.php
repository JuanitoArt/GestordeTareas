<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController
{
    private $usuario;

    // Paletas de colores disponibles. Se valida contra esta lista tanto en
    // el controlador como en el router, para que nunca se guarde un valor
    // arbitrario en la base de datos.
    public const TEMAS_VALIDOS = ['indigo', 'azul', 'verde', 'rosa', 'naranja'];

    // Nombre de la cookie de "Recordarme" y cuántos días dura.
    private const COOKIE_RECORDAR = 'recordar_token';
    private const DIAS_RECORDAR = 30;

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
    public function iniciarSesion($identificador, $password, $recordar = false)
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

        if ($recordar) {
            $this->crearTokenRecordar($usuario['id']);
        }

        return ['exito' => true, 'error' => null];
    }

    // Genera un token aleatorio, guarda solo su hash en la base de datos
    // (igual que con las contraseñas) y deja el token real en una cookie
    // httpOnly de larga duración para reconstruir la sesión más adelante.
    private function crearTokenRecordar($usuarioId)
    {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiraEnTimestamp = time() + (self::DIAS_RECORDAR * 86400);

        $this->usuario->guardarTokenRecordar(
            $usuarioId,
            $tokenHash,
            date('Y-m-d H:i:s', $expiraEnTimestamp)
        );

        setcookie(
            self::COOKIE_RECORDAR,
            $usuarioId . ':' . $token,
            [
                'expires' => $expiraEnTimestamp,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );
    }

    // Si no hay sesión activa pero existe una cookie de "recordarme" válida,
    // reconstruye la sesión automáticamente. Devuelve true si el usuario
    // quedó autenticado (ya sea porque tenía sesión o gracias a la cookie).
    public function intentarAutoLogin()
    {
        if (isset($_SESSION['usuario_id'])) {
            return true;
        }

        if (empty($_COOKIE[self::COOKIE_RECORDAR])) {
            return false;
        }

        $partes = explode(':', $_COOKIE[self::COOKIE_RECORDAR], 2);

        if (count($partes) !== 2) {
            return false;
        }

        [$usuarioId, $token] = $partes;
        $tokenHash = hash('sha256', $token);

        $usuario = $this->usuario->buscarPorTokenRecordar($usuarioId, $tokenHash);

        if (!$usuario) {
            // Token inválido, ya usado o vencido: limpiamos la cookie para
            // no seguir intentando en cada request.
            $this->borrarCookieRecordar();
            return false;
        }

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        $_SESSION['tema'] = $usuario['tema'] ?? 'indigo';

        return true;
    }

    private function borrarCookieRecordar()
    {
        setcookie(self::COOKIE_RECORDAR, '', [
            'expires' => time() - 3600,
            'path' => '/',
        ]);
    }

    public function cerrarSesion()
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->usuario->eliminarTokensRecordar($_SESSION['usuario_id']);
        }

        $this->borrarCookieRecordar();

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