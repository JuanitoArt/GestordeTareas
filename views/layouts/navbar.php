<?php
$accionActual = $_GET['accion'] ?? 'inicio';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary rounded shadow-sm mb-4">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="index.php?accion=inicio">
            <i class="bi bi-check2-square"></i>
            Mi Agenda
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a class="nav-link <?= $accionActual == 'inicio' ? 'active' : '' ?>"
                       href="index.php?accion=inicio">

                        <i class="bi bi-house-fill"></i>
                        Inicio

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link <?= $accionActual == 'tareas' ? 'active' : '' ?>"
                       href="index.php?accion=tareas">

                        <i class="bi bi-list-check"></i>
                        Tareas

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link <?= ($accionActual == 'actividades' || $accionActual == 'miDia') ? 'active' : '' ?>"
                       href="index.php?accion=actividades">

                        <i class="bi bi-calendar-event"></i>
                        Actividades

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="index.php?accion=logout">

                        <i class="bi bi-box-arrow-right"></i>
                        <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? '') ?> · Salir

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- Barra inferior para móviles -->

<nav class="bottom-nav d-md-none">

    <a href="index.php?accion=inicio"
       class="<?= $accionActual == 'inicio' ? 'active' : '' ?>">

        <i class="bi bi-house-fill"></i>
        <span>Inicio</span>

    </a>

    <a href="index.php?accion=tareas"
       class="<?= $accionActual == 'tareas' ? 'active' : '' ?>">

        <i class="bi bi-list-task"></i>
        <span>Tareas</span>

    </a>

    <a href="index.php?accion=miDia"
       class="<?= ($accionActual == 'miDia' || $accionActual == 'actividades') ? 'active' : '' ?>">

        <i class="bi bi-calendar-event"></i>
        <span>Mi Día</span>

    </a>

    <div class="dropup">

    <a href="#"
       class="dropdown-toggle text-decoration-none"
       data-bs-toggle="dropdown"
       aria-expanded="false">

        <i class="bi bi-plus-circle-fill"></i>
        <span>Nueva</span>

    </a>

    <ul class="dropdown-menu shadow">

        <li>

            <a class="dropdown-item"
               href="index.php?accion=crearTarea">

                <i class="bi bi-list-task me-2"></i>
                Nueva tarea

            </a>

        </li>

        <li>

            <a class="dropdown-item"
               href="index.php?accion=crearActividad">

                <i class="bi bi-calendar-plus me-2"></i>
                Nueva actividad

            </a>

        </li>

    </ul>

</div>

</nav>

<style>

.bottom-nav{

    position:fixed;
    bottom:0;
    left:0;
    width:100%;

    background:#fff;

    border-top:1px solid #ddd;

    display:flex;

    justify-content:space-around;

    align-items:center;

    padding:8px 0;

    z-index:999;

    box-shadow:0 -2px 10px rgba(0,0,0,.08);

}

/* ===== FAB ===== */

.fab-container{

    position:relative;

    display:flex;

    flex-direction:column;

    align-items:center;

}

.fab-button{

    width:60px;

    height:60px;

    border:none;

    border-radius:50%;

    background:#0d6efd;

    color:white;

    font-size:1.6rem;

    display:flex;

    align-items:center;

    justify-content:center;

    box-shadow:0 8px 20px rgba(13,110,253,.35);

    cursor:pointer;

    transition:.3s;

}

.fab-button:hover{

    transform:scale(1.08);

}

.fab-button:active{

    transform:scale(.95);

}

.fab-menu{

    position:absolute;

    bottom:75px;

    display:flex;

    flex-direction:column;

    gap:12px;

    opacity:0;

    visibility:hidden;

    pointer-events:none;

}

.fab-item{

    width:240px;

    background:white;

    border-radius:18px;

    padding:14px 18px;

    text-decoration:none;

    color:#212529;

    display:flex;

    justify-content:space-between;

    align-items:center;

    box-shadow:0 10px 25px rgba(0,0,0,.12);

    transition:.25s;

}

.fab-item:hover{

    transform:translateY(-2px);

}

.fab-icon{

    width:42px;

    height:42px;

    border-radius:50%;

    background:#f1f3f5;

    display:flex;

    justify-content:center;

    align-items:center;

    color:#0d6efd;

    font-size:1.2rem;

}

.fab-text{

    font-weight:600;

}

.bottom-nav a{

    color:#6c757d;

    text-decoration:none;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    font-size:.75rem;

    padding:6px 12px;

    border-radius:12px;

    transition:.25s;

}

.bottom-nav i{

    font-size:1.35rem;

    margin-bottom:3px;

}

.bottom-nav a.active{

    background:#EAF2FF;

    color:#0d6efd;

}

.bottom-nav a.active i{

    color:#0d6efd;

}

.bottom-nav a:hover{

    color:#0d6efd;

}

body{

    padding-bottom:90px;

}

.bottom-nav .dropup{

    display:flex;

    flex-direction:column;

    align-items:center;

}

.bottom-nav .dropdown-toggle{

    color:#6c757d;

    display:flex;

    flex-direction:column;

    align-items:center;

    text-decoration:none;

    border-radius:12px;

    padding:6px 12px;

}

.bottom-nav .dropdown-toggle::after{

    display:none;

}

.bottom-nav .dropdown-menu{

    border:none;

    border-radius:18px;

    margin-bottom:12px;

    min-width:220px;

    box-shadow:0 10px 30px rgba(0,0,0,.15);

}

.bottom-nav .dropdown-item{

    padding:12px 18px;

    font-size:.95rem;

}

/* ===== Asistente de voz ===== */

.voz-widget{

    position:fixed;

    bottom:100px;

    right:16px;

    z-index:1000;

    display:flex;

    flex-direction:column;

    align-items:flex-end;

    gap:6px;

}

.voz-btn-activar{

    background:#0d6efd;

    color:white;

    border:none;

    border-radius:30px;

    padding:10px 18px;

    font-weight:600;

    box-shadow:0 8px 20px rgba(13,110,253,.35);

    cursor:pointer;

}

.voz-badge{

    background:white;

    border-radius:20px;

    padding:6px 14px;

    font-size:.8rem;

    color:#6c757d;

    box-shadow:0 4px 12px rgba(0,0,0,.1);

    border:2px solid transparent;

}

.voz-badge.voz-activo{

    border-color:#22C55E;

    color:#16794a;

    font-weight:600;

}

@media (max-width:768px){

    .voz-widget{

        bottom:80px;

    }

}

</style>

<div class="voz-widget">

    <button id="voz-activar" class="voz-btn-activar" type="button">
        🎤 Activar asistente de voz
    </button>

    <div id="voz-badge" class="voz-badge">
        <span id="voz-estado">Desactivado</span>
    </div>

</div>

<script src="assets/js/voz.js" defer></script>