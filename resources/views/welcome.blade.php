<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to StockFlow - Inventory & Billing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .navbar {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .nav-links a.login-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-links a.login-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-links a.register-btn {
            background: white;
            color: #764ba2;
        }

        .nav-links a.register-btn:hover {
            background: #f0f0f0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .hero-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 20px;
            z-index: 10;
        }

        .hero-text-wrapper {
            max-width: 800px;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            animation: fadeInDown 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 300;
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .cta-buttons {
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        .cta-buttons a {
            display: inline-block;
            margin: 0 10px;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .cta-primary {
            background: white;
            color: #764ba2;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
            color: #667eea;
        }

        .cta-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.5);
        }

        .cta-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: white;
        }

        .features-section {
            padding: 100px 20px;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #333;
        }

        .section-title p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 10px auto 0;
        }

        .feature-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 30px;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .feature-desc {
            color: #666;
            line-height: 1.6;
        }

        /* Decorative Background Shapes */
        .shape {
            position: absolute;
            filter: blur(50px);
            z-index: 1;
            opacity: 0.5;
        }

        .shape-1 {
            top: -10%;
            left: -10%;
            width: 40vw;
            height: 40vw;
            background: #ff7eb3;
            border-radius: 50%;
        }

        .shape-2 {
            bottom: -20%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            background: #8e2de2;
            border-radius: 50%;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .cta-buttons a { display: block; margin: 10px auto; width: 80%; }
            .navbar { flex-direction: column; gap: 15px; }
            .nav-links { display: flex; gap: 10px; }
            .nav-links a { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>

        <nav class="navbar">
            <a href="/" class="navbar-brand">
                <i class="fas fa-boxes-stacked"></i> StockFlow
            </a>
            <div class="nav-links">
                <a href="{{ route('login') }}" class="login-btn">Log In</a>
                <a href="{{ route('register') }}" class="register-btn">Get Started</a>
            </div>
        </nav>

        <div class="hero-content">
            <div class="hero-text-wrapper">
                <h1 class="hero-title">Streamline Your Inventory & Billing</h1>
                <p class="hero-subtitle">
                    StockFlow is the ultimate all-in-one platform to manage your products, track inventory, handle customer orders, and generate professional invoices seamlessly.
                </p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="cta-primary">Start for Free</a>
                    <a href="#features" class="cta-secondary">Explore Features</a>
                </div>
            </div>
        </div>
    </div>

    <section id="features" class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>Everything You Need to Scale</h2>
                <p>Powerful tools designed to simplify your daily operations and boost your business productivity.</p>
            </div>
            
            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="feature-title">Inventory Control</h3>
                        <p class="feature-desc">Keep accurate track of your stock levels in real-time. Never run out of your best-selling items again.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h3 class="feature-title">Smart Invoicing</h3>
                        <p class="feature-desc">Generate professional, customizable invoices instantly and track payments with ease.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Customer CRM</h3>
                        <p class="feature-desc">Manage customer profiles, purchase history, and build stronger relationships.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Order Tracking</h3>
                        <p class="feature-desc">Monitor orders from draft to completion and keep your sales pipeline flowing smoothly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer style="background: #1a1a2e; color: #888; padding: 40px 0; text-align: center;">
        <div class="container">
            <p>&copy; {{ date('Y') }} StockFlow. All rights reserved.</p>
            <p style="font-size: 0.9rem; margin-top: 10px;">Built for modern businesses.</p>
        </div>
    </footer>

</body>
</html>
