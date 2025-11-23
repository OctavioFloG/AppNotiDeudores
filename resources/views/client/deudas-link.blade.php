<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Deudas - {{ $client->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-deudas {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .warning-banner {
            background: #fef3c7;
            border-bottom: 2px solid #f59e0b;
            padding: 15px 20px;
            color: #92400e;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .timer-display {
            font-weight: 600;
            background: #fff8e1;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .resumen {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            padding: 20px;
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }
        .stat {
            text-align: center;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .actions-bar {
            padding: 15px 20px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-action {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-descargar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-descargar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .deudas-container {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        .deuda-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fafafa;
            transition: all 0.3s ease;
        }
        .deuda-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .deuda-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .deuda-id {
            font-weight: 600;
            color: #1f2937;
        }
        .deuda-estado {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .estado-vencida {
            background: #fee2e2;
            color: #991b1b;
        }
        .estado-pendiente {
            background: #fef3c7;
            color: #92400e;
        }
        .estado-pagada {
            background: #d1fae5;
            color: #065f46;
        }
        .deuda-monto {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }
        .deuda-info {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            gap: 20px;
        }
        .deuda-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .footer {
            padding: 20px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        .no-deudas {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
        .no-deudas i {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container-deudas">
        <!-- Header -->
        <div class="header">
            <h1>Mis Deudas</h1>
            <p>{{ $client->nombre }}</p>
        </div>

        <!-- Banner de Advertencia con Temporizador -->
        <div class="warning-banner">
            <i class="fas fa-clock"></i>
            <span>Este acceso expirará en <span class="timer-display" id="timerDisplay">00:00</span></span>
        </div>

        <!-- Resumen -->
        <div class="resumen">
            <div class="stat">
                <div class="stat-value">{{ $resumen['total_deudas'] }}</div>
                <div class="stat-label">Deudas</div>
            </div>
            <div class="stat">
                <div class="stat-value">${{ number_format($resumen['monto_total'], 2) }}</div>
                <div class="stat-label">Monto Total</div>
            </div>
        </div>

        <!-- Barra de Acciones -->
        <div class="actions-bar">
            <button class="btn-action btn-descargar" onclick="descargarPDF()">
                <i class="fas fa-download"></i>
                Descargar PDF
            </button>
        </div>

        <!-- Deudas -->
        <div class="deudas-container">
            @if($deudas->isEmpty())
                <div class="no-deudas">
                    <i class="fas fa-check-circle"></i>
                    <h3>Felicidades!</h3>
                    <p>No tienes deudas pendientes</p>
                </div>
            @else
                @foreach($deudas as $deuda)
                    <div class="deuda-item">
                        <div class="deuda-header">
                            <span class="deuda-id">#{{ $deuda->id_cuenta }}</span>
                            <span class="deuda-estado estado-{{ strtolower($deuda->estado) }}">
                                {{ $deuda->estado }}
                            </span>
                        </div>
                        <div class="deuda-monto">${{ number_format($deuda->monto, 2) }}</div>
                        <div class="deuda-info">
                            <span>
                                <i class="fas fa-calendar"></i>
                                Vence: {{ $deuda->fecha_vencimiento}}
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Si tienes preguntas, contacta a la institucion</p>
            <p style="margin-top: 10px;">Si cierras esta ventana o recargas, el token expirará</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        const tokenId = "{{ $tokenId ?? '' }}";
        let tiempoRestante = 300; // 24 horas en segundos

        // Inicializar temporizador
        document.addEventListener('DOMContentLoaded', function() {
            if (tokenId) {
                marcarTokenComoUsado();
            }
            
            actualizarTemporizador();
            setInterval(actualizarTemporizador, 1000);

            // Detector de cierre de ventana
            window.addEventListener('beforeunload', function(e) {
                // Si el usuario intenta salir, el token ya expirara
                console.log('Usuario intenta salir de la pagina');
            });

            // Detector de cambio de pestana
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    console.log('Ventana oculta - el token seguira vigente hasta que expire');
                }
            });
        });

        function actualizarTemporizador() {
            tiempoRestante--;

            if (tiempoRestante <= 0) {
                document.getElementById('timerDisplay').textContent = '0:00';
                mostrarMensajeExpiracion();
                return;
            }

            const horas = Math.floor(tiempoRestante / 3600);
            const minutos = Math.floor((tiempoRestante % 3600) / 60);
            const segundos = tiempoRestante % 60;

            const horasFormato = String(horas).padStart(2, '0');
            const minutosFormato = String(minutos).padStart(2, '0');
            const segundosFormato = String(segundos).padStart(2, '0');

            document.getElementById('timerDisplay').textContent = `${horasFormato}:${minutosFormato}:${segundosFormato}`;

            // Cambiar color si queda menos de 5 minutos
            if (tiempoRestante < 300) {
                document.querySelector('.timer-display').style.background = '#fee2e2';
                document.querySelector('.timer-display').style.color = '#991b1b';
            }
        }

        function mostrarMensajeExpiracion() {
            document.querySelector('.deudas-container').innerHTML = `
                <div class="no-deudas">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
                    <h3>Token Expirado</h3>
                    <p>El tiempo para ver tus deudas ha finalizado</p>
                    <p style="font-size: 12px; color: #9ca3af; margin-top: 15px;">Solicita un nuevo link a la institucion</p>
                </div>
            `;
            document.querySelector('.actions-bar').innerHTML = '';
        }

        async function marcarTokenComoUsado() {
            try {
                fetch(`/api/marcar-token-usado/${tokenId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                }).then(r => r.json())
                  .then(data => console.log('Token marcado como usado'))
                  .catch(e => console.error('Error:', e));
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function descargarPDF() {
            const elemento = document.querySelector('.container-deudas');
            const opcion = {
                margin: 10,
                filename: 'Reporte_Deudas_{{ $client->nombre }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
            };

            html2pdf().set(opcion).from(elemento).save();
        }
    </script>
</body>
</html>
