(function () {
    'use strict';

    // Frases que activan el modo "escuchando comando"
    const FRASES_ACTIVACION = ['veamos mi agenda', 'hey agenda', 'oye agenda'];

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        console.warn('Este navegador no soporta reconocimiento de voz (usa Chrome o Edge).');
        return;
    }

    const btnActivar = document.getElementById('voz-activar');
    const estadoTexto = document.getElementById('voz-estado');
    const badge = document.getElementById('voz-badge');

    // Recordamos si el usuario ya activó el asistente antes, para reactivarlo
    // solo en cada página nueva (el permiso del micrófono el navegador ya lo recuerda).
    const CLAVE_ACTIVADO = 'vozAsistenteActivado';

    // Recordamos el momento del último comando/activación para poder seguir
    // "en modo comando" en la siguiente página, sin repetir la frase clave,
    // siempre que no hayan pasado más de VENTANA_SESION_MS.
    const CLAVE_SESION_COMANDO = 'vozSesionComandoTimestamp';
    const VENTANA_SESION_MS = 60000;

    let reconocimiento = null;
    let escuchandoComando = false; // true = ya se dijo la frase de activación
    let modoCreacion = null;       // null | { tipo, paso, datos }
    let debeReiniciar = false;
    let timeoutComando = null;

    function hablar(texto) {
        if (!('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(texto);
        utterance.lang = 'es-ES';
        window.speechSynthesis.speak(utterance);
    }

    function actualizarEstado(texto) {
        if (estadoTexto) estadoTexto.textContent = texto;
    }

    function marcarSesionComandoActiva() {
        sessionStorage.setItem(CLAVE_SESION_COMANDO, Date.now().toString());
    }

    function haySesionComandoActiva() {
        const ultimo = parseInt(sessionStorage.getItem(CLAVE_SESION_COMANDO) || '0', 10);
        return (Date.now() - ultimo) < VENTANA_SESION_MS;
    }

    function volverAEspera() {
        escuchandoComando = false;
        modoCreacion = null;
        sessionStorage.removeItem(CLAVE_SESION_COMANDO);
        actualizarEstado('👂 Di: "veamos mi agenda"');
        if (badge) badge.classList.remove('voz-activo');
    }

    function iniciarReconocimiento() {
        reconocimiento = new SpeechRecognition();
        reconocimiento.lang = 'es-CO';
        reconocimiento.continuous = true;
        reconocimiento.interimResults = false;

        reconocimiento.onresult = function (evento) {
            const ultimo = evento.results[evento.results.length - 1];
            const texto = ultimo[0].transcript.trim().toLowerCase();
            procesarTexto(texto);
        };

        reconocimiento.onerror = function (evento) {
            console.warn('Error de reconocimiento de voz:', evento.error);

            // Si el permiso fue bloqueado/revocado, dejamos de intentar reiniciar
            // solos y mostramos el botón de nuevo para que el usuario reactive a mano.
            if (evento.error === 'not-allowed' || evento.error === 'service-not-allowed') {
                debeReiniciar = false;
                localStorage.removeItem(CLAVE_ACTIVADO);
                if (btnActivar) btnActivar.style.display = 'block';
                actualizarEstado('🔴 Permiso de micrófono bloqueado');
                if (badge) badge.classList.remove('voz-activo');
            }
        };

        // El reconocimiento se corta solo tras silencios largos; lo reiniciamos
        // para simular "escucha continua".
        reconocimiento.onend = function () {
            if (debeReiniciar) {
                try {
                    reconocimiento.start();
                } catch (e) {
                    /* ya estaba iniciado, se ignora */
                }
            }
        };

        debeReiniciar = true;
        reconocimiento.start();

        // Si venimos de otra página y hace poco se activó el modo comando
        // (dijiste la frase clave o diste un comando), seguimos ahí directo.
        if (haySesionComandoActiva()) {
            escuchandoComando = true;
            if (badge) badge.classList.add('voz-activo');
            actualizarEstado('🟢 Te escucho...');

            timeoutComando = setTimeout(() => {
                if (escuchandoComando && !modoCreacion) {
                    volverAEspera();
                }
            }, 8000);
        } else {
            volverAEspera();
        }
    }

    function procesarTexto(texto) {

        if (modoCreacion) {
            clearTimeout(timeoutComando);
            continuarCreacion(texto);
            return;
        }

        if (!escuchandoComando) {
            const activado = FRASES_ACTIVACION.some((frase) => texto.includes(frase));

            if (activado) {
                escuchandoComando = true;
                marcarSesionComandoActiva();
                if (badge) badge.classList.add('voz-activo');
                actualizarEstado('🟢 Te escucho...');
                hablar('Te escucho');

                // Si no dice un comando en 8 segundos, vuelve a modo espera
                timeoutComando = setTimeout(() => {
                    if (escuchandoComando && !modoCreacion) {
                        volverAEspera();
                    }
                }, 8000);
            }

            return;
        }

        clearTimeout(timeoutComando);
        ejecutarComando(texto);
    }

    function ejecutarComando(texto) {

        marcarSesionComandoActiva();

        if (texto.includes('crear tarea') || texto.includes('nueva tarea')) {
            hablar('Vamos a crear una tarea. ¿Cuál es el título?');
            modoCreacion = { tipo: 'tarea', paso: 'titulo', datos: {} };
            actualizarEstado('📝 Creando tarea: título');
            return;
        }

        if (texto.includes('crear actividad') || texto.includes('nueva actividad')) {
            hablar('Vamos a crear una actividad. ¿Cuál es el título?');
            modoCreacion = { tipo: 'actividad', paso: 'titulo', datos: {} };
            actualizarEstado('📝 Creando actividad: título');
            return;
        }

        if (texto.includes('mi día') || texto.includes('mi dia')) {
            hablar('Mostrando tu día');
            location.href = 'index.php?accion=miDia';
            return;
        }

        if (texto.includes('actividades')) {
            hablar('Mostrando tus actividades');
            location.href = 'index.php?accion=actividades';
            return;
        }

        if (texto.includes('tareas')) {
            hablar('Mostrando tus tareas');
            location.href = 'index.php?accion=tareas';
            return;
        }

        if (texto.includes('inicio') || texto.includes('dashboard')) {
            hablar('Vamos al inicio');
            location.href = 'index.php?accion=inicio';
            return;
        }

        if (texto.includes('cerrar sesión') || texto.includes('cerrar sesion')) {
            hablar('Cerrando sesión');
            location.href = 'index.php?accion=logout';
            return;
        }

        hablar('No entendí ese comando');
        volverAEspera();
    }

    function continuarCreacion(texto) {
        const c = modoCreacion;

        if (texto.includes('cancelar')) {
            hablar('Cancelado');
            volverAEspera();
            return;
        }

        if (c.tipo === 'tarea') {
            manejarPasoTarea(c, texto);
        } else {
            manejarPasoActividad(c, texto);
        }
    }

    function manejarPasoTarea(c, texto) {
        switch (c.paso) {

            case 'titulo':
                c.datos.titulo = capitalizar(texto);
                c.paso = 'descripcion';
                actualizarEstado('📝 Creando tarea: descripción');
                hablar('¿Cuál es la descripción?');
                return;

            case 'descripcion':
                c.datos.descripcion = capitalizar(texto);
                c.paso = 'prioridad';
                actualizarEstado('📝 Creando tarea: prioridad');
                hablar('¿Prioridad: alta, media o baja?');
                return;

            case 'prioridad':
                c.datos.prioridad = normalizarPrioridad(texto);
                c.paso = 'fecha';
                actualizarEstado('📝 Creando tarea: fecha');
                hablar('¿Para qué fecha? Di, por ejemplo, veinte de agosto.');
                return;

            case 'fecha': {
                const fecha = interpretarFecha(texto);

                if (!fecha) {
                    hablar('No entendí la fecha. Intenta decir, por ejemplo: veinte de agosto.');
                    return;
                }

                c.datos.fecha_limite = fecha;
                c.paso = 'confirmar';
                actualizarEstado('📝 Confirmar tarea');
                hablar(`Voy a crear la tarea ${c.datos.titulo}, prioridad ${c.datos.prioridad}, para el ${fecha}. ¿Confirmas? Di sí o no.`);
                return;
            }

            case 'confirmar':
                if (texto.includes('sí') || texto.includes('si')) {
                    marcarSesionComandoActiva();
                    enviarFormulario('guardarTarea', {
                        titulo: c.datos.titulo,
                        descripcion: c.datos.descripcion,
                        prioridad: c.datos.prioridad,
                        fecha_limite: c.datos.fecha_limite,
                        estado: 'Pendiente',
                    });
                    hablar('Tarea creada');
                } else {
                    hablar('Cancelado');
                    volverAEspera();
                }
                return;
        }
    }

    function manejarPasoActividad(c, texto) {
        switch (c.paso) {

            case 'titulo':
                c.datos.titulo = capitalizar(texto);
                c.paso = 'descripcion';
                actualizarEstado('📝 Creando actividad: descripción');
                hablar('¿Cuál es la descripción?');
                return;

            case 'descripcion':
                c.datos.descripcion = capitalizar(texto);
                c.paso = 'lugar';
                actualizarEstado('📝 Creando actividad: lugar');
                hablar('¿En qué lugar?');
                return;

            case 'lugar':
                c.datos.lugar = capitalizar(texto);
                c.paso = 'fecha';
                actualizarEstado('📝 Creando actividad: fecha');
                hablar('¿Para qué fecha?');
                return;

            case 'fecha': {
                const fecha = interpretarFecha(texto);

                if (!fecha) {
                    hablar('No entendí la fecha. Intenta decir, por ejemplo: veinte de agosto.');
                    return;
                }

                c.datos.fecha = fecha;
                c.paso = 'hora_inicio';
                actualizarEstado('📝 Creando actividad: hora inicio');
                hablar('¿A qué hora empieza? Di, por ejemplo, tres de la tarde.');
                return;
            }

            case 'hora_inicio': {
                const hora = interpretarHora(texto);

                if (!hora) {
                    hablar('No entendí la hora. Intenta de nuevo, por ejemplo: tres de la tarde.');
                    return;
                }

                c.datos.hora_inicio = hora;
                c.paso = 'hora_fin';
                actualizarEstado('📝 Creando actividad: hora fin');
                hablar('¿A qué hora termina?');
                return;
            }

            case 'hora_fin': {
                const hora = interpretarHora(texto);

                if (!hora) {
                    hablar('No entendí la hora. Intenta de nuevo.');
                    return;
                }

                c.datos.hora_fin = hora;
                c.paso = 'confirmar';
                actualizarEstado('📝 Confirmar actividad');
                hablar(`Voy a crear la actividad ${c.datos.titulo}, el ${c.datos.fecha}, de ${c.datos.hora_inicio} a ${c.datos.hora_fin}. ¿Confirmas? Di sí o no.`);
                return;
            }

            case 'confirmar':
                if (texto.includes('sí') || texto.includes('si')) {
                    marcarSesionComandoActiva();
                    enviarFormulario('guardarActividad', {
                        titulo: c.datos.titulo,
                        descripcion: c.datos.descripcion,
                        fecha: c.datos.fecha,
                        hora_inicio: c.datos.hora_inicio,
                        hora_fin: c.datos.hora_fin,
                        lugar: c.datos.lugar,
                    });
                    hablar('Actividad creada');
                } else {
                    hablar('Cancelado');
                    volverAEspera();
                }
                return;
        }
    }

    function capitalizar(texto) {
        return texto.charAt(0).toUpperCase() + texto.slice(1);
    }

    function normalizarPrioridad(texto) {
        if (texto.includes('alta')) return 'Alta';
        if (texto.includes('media')) return 'Media';
        return 'Baja';
    }

    const MESES = {
        enero: '01', febrero: '02', marzo: '03', abril: '04', mayo: '05', junio: '06',
        julio: '07', agosto: '08', septiembre: '09', setiembre: '09', octubre: '10',
        noviembre: '11', diciembre: '12',
    };

    // Interpreta frases como "veinte de agosto" o "el 5 de septiembre" -> "2026-08-20"
    function interpretarFecha(texto) {
        const match = texto.match(/(\d{1,2})\s*(?:de)?\s*([a-zñ]+)/i);

        if (!match) return null;

        const dia = match[1].padStart(2, '0');
        const mes = MESES[match[2].toLowerCase()];

        if (!mes) return null;

        const anio = new Date().getFullYear();

        return `${anio}-${mes}-${dia}`;
    }

    // Interpreta frases como "tres de la tarde", "3 pm" o "15:00" -> "15:00"
    function interpretarHora(texto) {
        const match = texto.match(/(\d{1,2})(?::(\d{2}))?\s*(am|pm|de la tarde|de la mañana|de la noche)?/i);

        if (!match) return null;

        let hora = parseInt(match[1], 10);
        const minutos = match[2] || '00';
        const periodo = (match[3] || '').toLowerCase();

        if ((periodo.includes('pm') || periodo.includes('tarde') || periodo.includes('noche')) && hora < 12) {
            hora += 12;
        }

        if (periodo.includes('mañana') && hora === 12) {
            hora = 0;
        }

        return `${String(hora).padStart(2, '0')}:${minutos}`;
    }

    function enviarFormulario(accion, datos) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `index.php?accion=${accion}`;

        Object.keys(datos).forEach(function (clave) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = clave;
            input.value = datos[clave];
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    function activarAsistente() {
        localStorage.setItem(CLAVE_ACTIVADO, '1');
        iniciarReconocimiento();
        if (btnActivar) btnActivar.style.display = 'none';
    }

    if (btnActivar) {
        btnActivar.addEventListener('click', activarAsistente);
    }

    // Si en una página anterior ya se activó el asistente, lo reactivamos
    // automáticamente aquí (sin pedir clic de nuevo).
    if (localStorage.getItem(CLAVE_ACTIVADO) === '1') {
        if (btnActivar) btnActivar.style.display = 'none';
        iniciarReconocimiento();
    }

})();
