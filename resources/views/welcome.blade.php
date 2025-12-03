<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppNotiDeudores - Gestión de Deudores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1f2937; }

        /* Navbar */
        .navbar {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-logo { font-size: 24px; font-weight: 700; color: #047857; }
        .navbar-links { display: flex; gap: 30px; }
        .navbar-links a { text-decoration: none; color: #1f2937; font-weight: 500; transition: color .3s; }
        .navbar-links a:hover { color: #047857; }
        .navbar-btn { background: #047857; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all .3s; }
        .navbar-btn:hover { background: #065f46; transform: translateY(-2px); }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            padding: 120px 40px 100px;
            margin-top: 70px;
            text-align: center;
        }
        .hero h1 { font-size: 48px; margin-bottom: 20px; line-height: 1.2; }
        .hero p { font-size: 20px; margin-bottom: 30px; opacity: 0.9; max-width: 600px; margin-left: auto; margin-right: auto; }
        .hero-buttons { display: flex; gap: 20px; justify-content: center; }
        .btn-primary { background: white; color: #047857; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: 700; transition: all .3s; border: none; cursor: pointer; }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,.2); }
        .btn-secondary { background: transparent; color: white; padding: 15px 40px; border: 2px solid white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all .3s; }
        .btn-secondary:hover { background: white; color: #047857; }

        /* Features Section */
        .features {
            padding: 80px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-title { font-size: 36px; text-align: center; margin-bottom: 60px; color: #1f2937; }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        .feature-card {
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.1);
            text-align: center;
            transition: all .3s;
            border-left: 4px solid #047857;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,.15);
        }
        .feature-icon { font-size: 48px; color: #047857; margin-bottom: 15px; }
        .feature-card h3 { font-size: 20px; margin-bottom: 10px; }
        .feature-card p { color: #6b7280; line-height: 1.6; }

        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            padding: 60px 40px;
        }
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }
        .stat-item h4 { font-size: 40px; margin-bottom: 10px; }
        .stat-item p { font-size: 16px; opacity: 0.9; }

        /* How It Works */
        .how-it-works {
            padding: 80px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        .step { padding: 30px; text-align: center; }
        .step-number {
            width: 50px;
            height: 50px;
            background: #047857;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 auto 20px;
        }
        .step h4 { font-size: 18px; margin-bottom: 10px; }
        .step p { color: #6b7280; line-height: 1.6; }

        /* CTA Section */
        .cta {
            background: #f9fafb;
            padding: 60px 40px;
            text-align: center;
        }
        .cta h2 { font-size: 32px; margin-bottom: 20px; }
        .cta p { font-size: 18px; color: #6b7280; margin-bottom: 30px; }

        /* Footer */
        .footer {
            background: #1f2937;
            color: white;
            padding: 40px;
            text-align: center;
        }
        .footer p { opacity: 0.8; }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar { padding: 15px 20px; }
            .navbar-links { gap: 15px; display: none; }
            .hero { padding: 80px 20px 60px; margin-top: 60px; }
            .hero h1 { font-size: 32px; }
            .hero p { font-size: 16px; }
            .hero-buttons { flex-direction: column; }
            .features { padding: 50px 20px; }
            .section-title { font-size: 28px; }
            .how-it-works { padding: 50px 20px; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <i class="fas fa-bell"></i> AppNotiDeudores
        </div>
        <div class="navbar-links">
            <a href="#features">Características</a>
            <a href="#how">Cómo funciona</a>
            <a href="#contact">Contacto</a>
            <a href="/login" class="navbar-btn">Inicia Sesión</a>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <h1>Gestión inteligente de deudores</h1>
        <p>Automatiza el seguimiento de cuentas por cobrar y notifica a tus clientes de forma eficiente con AppNotiDeudores</p>
        <div class="hero-buttons">
            <a href="/login" class="btn-primary">Comienza ahora</a>
            <a href="#features" class="btn-secondary">Conocer más</a>
        </div>
    </section>

    <!-- Features -->
    <section class="features" id="features">
        <h2 class="section-title">Características principales</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h3>Gestión de Clientes</h3>
                <p>Registra y administra todos tus clientes en un único lugar centralizado</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <h3>Control de Deudas</h3>
                <p>Monitorea todas las cuentas por cobrar con estados actualizados</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-paper-plane"></i></div>
                <h3>Notificaciones automáticas</h3>
                <p>Envía recordatorios automáticos por WhatsApp a tus clientes</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Reportes analíticos</h3>
                <p>Genera reportes detallados de seguimiento y cobranza</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-lock"></i></div>
                <h3>Seguridad garantizada</h3>
                <p>Protección de datos con autenticación basada en tokens</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>Acceso desde cualquier lugar</h3>
                <p>Interfaz responsive disponible en computador y móvil</p>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <h4>+100</h4>
                <p>Instituciones activas</p>
            </div>
            <div class="stat-item">
                <h4>+50K</h4>
                <p>Clientes registrados</p>
            </div>
            <div class="stat-item">
                <h4>+1M</h4>
                <p>Notificaciones enviadas</p>
            </div>
            <div class="stat-item">
                <h4>99.9%</h4>
                <p>Confiabilidad</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how">
        <h2 class="section-title">¿Cómo funciona?</h2>
        <div class="steps-grid">
            <div class="step">
                <div class="step-number">1</div>
                <h4>Registra tu institución</h4>
                <p>Crea una cuenta y configura los detalles de tu organización</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h4>Añade tus clientes</h4>
                <p>Importa o registra manualmente los datos de tus clientes</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h4>Registra las deudas</h4>
                <p>Crea cuentas por cobrar con montos y fechas de vencimiento</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h4>Envía notificaciones</h4>
                <p>El sistema automáticamente envía recordatorios por WhatsApp</p>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <h4>Monitorea resultados</h4>
                <p>Visualiza reportes de seguimiento y cobranza en tiempo real</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta" id="contact">
        <h2>¿Listo para mejorar tu gestión de cobranzas?</h2>
        <p>Únete a cientos de instituciones que confían en AppNotiDeudores</p>
        <a href="/login" class="btn-primary">Comenzar ahora</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 AppNotiDeudores. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
