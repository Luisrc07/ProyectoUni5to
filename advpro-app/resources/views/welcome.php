<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AudioVisual Pro - Sistema de Gestión Audiovisual</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <style>
        :root {
            --color-morado: #6a0dad;
            --color-morado-claro: #9b59b6;
            --color-negro: #000000;
            --color-blanco: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--color-negro);
        }
        
        .bg-morado {
            background-color: var(--color-morado);
            color: var(--color-blanco);
        }
        
        .bg-morado-claro {
            background-color: var(--color-morado-claro);
            color: var(--color-blanco);
        }
        
        .text-morado {
            color: var(--color-morado);
        }
        
        .btn-morado {
            background-color: var(--color-morado);
            color: white;
            border: none;
        }
        
        .btn-morado:hover {
            background-color: var(--color-morado-claro);
            color: white;
        }
        
        .navbar {
            border-bottom: 3px solid var(--color-morado);
        }
        
        .footer {
            border-top: 3px solid var(--color-morado);
        }
        
        .developer-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            border: none;
            text-align: center;
        }
        
        .developer-card:hover {
            transform: scale(1.05);
        }
        
        .developer-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            border: 5px solid var(--color-morado-claro);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--color-morado);
            margin-bottom: 1rem;
        }
        
        #back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--color-morado);
            color: white;
            border: none;
            font-size: 1.5rem;
            z-index: 99;
        }
        
        #back-to-top:hover {
            background-color: var(--color-morado-claro);
        }
        
        /* Estilos para el banner de imagen única */
        .banner-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }
        
        .banner-container {
            position: relative;
            text-align: center;
            color: white;
        }
        
        .banner-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 5px;
        }
        
        .banner-text h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .banner-text p {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
     <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-film me-2"></i>
                <span class="fw-bold">AudioVisual</span> Pro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#inicio">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#caracteristicas">Características</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#equipo">Equipo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contacto">Contacto</a>
                </li>
            </ul>
            <a href="{{ route('login') }}" class="btn btn-morado ms-lg-3">Probar Demo</a>
        </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="bg-dark text-white py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Gestión Integral para Proyectos Audiovisuales</h1>
                    <p class="lead mb-4">AudioVisual Pro combina la administración de contratos con la contabilidad especializada para proyectos audiovisuales en una sola plataforma.</p>
                    <div class="d-flex gap-3">
                        <a href="#contacto" class="btn btn-morado btn-lg">Contactar</a>
                        <a href="#caracteristicas" class="btn btn-outline-light btn-lg">Saber más</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="caracteristicas" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-morado">Características Principales</h2>
                <p class="lead">Todo lo que necesitas para gestionar tus proyectos audiovisuales</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <h4 class="card-title">Gestión de Contratos y Proyectos</h4>
                            <p class="card-text">Crea, administra y da seguimiento a todos los contratos de tus proyectos con plantillas personalizables y recordatorios automáticos.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <h4 class="card-title">Contabilidad Integral</h4>
                            <p class="card-text">Libro diario y mayor especializado para producciones audiovisuales con reportes financieros detallados.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="card-title">Reportes </h4>
                            <p class="card-text">Genera reportes personalizados para análisis financieros y de producción con gráficos interactivos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="equipo" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-morado">Nuestro Equipo de Desarrollo</h2>
                <p class="lead">Los profesionales detrás de AudioVisual Pro</p>
            </div>
            
            <div class="row g-4">
                <!-- Developer 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card developer-card h-100">
                        <img src="https://image.freepik.com/vector-gratis/diseno-logo-mono_104950-134.jpg" class="developer-img" alt="Desarrollador 1">
                        <div class="card-body text-center">
                            <h5 class="card-title">Moisés Mendoza</h5>
                        </div>
                    </div>
                </div>
                
                <!-- Developer 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card developer-card h-100">
                        <img src="https://img.freepik.com/premium-photo/bold-stencil-wolf-head-design-black-white-wolf-outline-svg-cutout_899449-185786.jpg?w=360" class="developer-img" alt="Desarrollador 2">
                        <div class="card-body text-center">
                            <h5 class="card-title">Victor Villarreta</h5>
                        </div>
                    </div>
                </div>
                
                <!-- Developer 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card developer-card h-100">
                        <img src="https://images.vexels.com/media/users/3/227565/isolated/preview/f77e3012e7b4ee1436dd818c420acafe-german-shephard-front-logo.png" class="developer-img" alt="Desarrollador 3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Bairond Diaz</h5>
                            
                        </div>
                    </div>
                </div>
                
                <!-- Developer 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card developer-card h-100">
                        <img src="https://img.freepik.com/premium-photo/bear-with-black-nose-brown-background_1002436-866.jpg?w=360" class="developer-img" alt="Desarrollador 4">
                        <div class="card-body text-center">
                            <h5 class="card-title">Jesús Pastrán</h5>
                            
                        </div>
                    </div>
                </div>
                
                <!-- Developer 5 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card developer-card h-100">
                        <img src="https://homegrownrexkittens.com/wp-content/uploads/2023/01/cat-head-kitten-symbol-gaming-cat-logo-elegant-element-for-brand-abstract-icon-symbols-vector-e1742417044401.jpg" class="developer-img" alt="Desarrollador 5">
                        <div class="card-body text-center">
                            <h5 class="card-title">Raul Quintero</h5>
                            
                        </div>
                    </div>
                </div>
                
                <!-- Developer 6 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card developer-card h-100">
                        <img src="https://static.vecteezy.com/system/resources/thumbnails/024/118/488/small/horse-head-logo-animal-brand-symbol-vector.jpg" class="developer-img" alt="Desarrollador 6">
                        <div class="card-body text-center">
                            <h5 class="card-title">Luis Rodríguez</h5>
                            
                        </div>
                    </div>
                </div>
                
                <!-- Developer 7 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card developer-card h-100">
                        <img src="https://as2.ftcdn.net/jpg/00/70/33/03/220_F_70330328_aMDRDxZBQvQddp72GpPsRTOg34scAylb.jpg" class="developer-img" alt="Desarrollador 7">
                        <div class="card-body text-center">
                            <h5 class="card-title">Abrahan Parra</h5>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="py-5 bg-morado text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">¿Listo para transformar tu gestión audiovisual?</h2>
                    <p class="lead mb-5">Contáctanos para una demostración personalizada o más información sobre AudioVisual Pro.</p>
                    
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-phone-alt me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-0">Teléfono</h5>
                                    <p class="mb-0">+1 (555) 123-4567</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-envelope me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-0">Email</h5>
                                    <p class="mb-0">info@audiovisualpro.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form class="mt-5 text-start">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Nombre completo" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Correo electrónico" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" placeholder="Asunto">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" rows="4" placeholder="Mensaje" required></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-light btn-lg px-4">Enviar Mensaje</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">AudioVisual Pro</h5>
                    <p>La solución integral para la gestión de proyectos audiovisuales y su contabilidad asociada.</p>
                    <div class="social-icons">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#inicio" class="text-white text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="#caracteristicas" class="text-white text-decoration-none">Características</a></li>
                        <li class="mb-2"><a href="#equipo" class="text-white text-decoration-none">Equipo</a></li>
                        <li><a href="#contacto" class="text-white text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Términos y Condiciones</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Política de Privacidad</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Aviso Legal</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Suscríbete</h5>
                    <p>Recibe las últimas actualizaciones y noticias sobre AudioVisual Pro.</p>
                    <form class="mb-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Tu email">
                            <button class="btn btn-morado" type="button">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2023 AudioVisual Pro. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Desarrollado con <i class="fas fa-heart text-danger"></i> por nuestro equipo</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top" title="Volver arriba">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Back to top button
        window.onscroll = function() {
            scrollFunction();
        };
        
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("back-to-top").style.display = "block";
            } else {
                document.getElementById("back-to-top").style.display = "none";
            }
        }
        
        document.getElementById("back-to-top").addEventListener("click", function() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>