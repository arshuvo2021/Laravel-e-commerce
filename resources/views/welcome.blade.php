<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel E-Commerce') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }
        .logo-section {
            background-color: #fff8f8;
            position: relative;
            overflow: hidden;
        }
        .logo-section.dark {
            background-color: #1a0002;
        }
        .logo-container {
            position: relative;
            z-index: 1;
        }
        .logo-overlay {
            position: absolute;
            inset: 0;
            border-radius: inherit;
            box-shadow: inset 0 0 0 1px rgba(26, 26, 0, 0.1);
        }
        .btn-primary-custom {
            background-color: #1b1b18;
            border-color: #1b1b18;
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: black;
            border-color: black;
        }
        .text-accent {
            color: #f53003;
        }
        .dark .text-accent {
            color: #ff4433;
        }
        .nav-link-custom {
            padding: 0.5rem 1rem;
            border: 1px solid rgba(25, 20, 0, 0.2);
            border-radius: 0.25rem;
            color: #1b1b18;
        }
        .nav-link-custom:hover {
            border-color: rgba(25, 21, 1, 0.3);
        }
        .dark .nav-link-custom {
            color: #ededec;
            border-color: #3e3e3a;
        }
        .dark .nav-link-custom:hover {
            border-color: #62605b;
        }
        .search-box {
            border-radius: 1.5rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Header with Navigation -->
        <header class="mb-5">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link-custom text-decoration-none">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-custom text-decoration-none">
                            <i class="fas fa-sign-in-alt me-1"></i> Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link-custom text-decoration-none">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        

        <!-- Featured Categories -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Shop by Category</h2>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://via.placeholder.com/300x200?text=Electronics" class="card-img-top" alt="Electronics">
                    <div class="card-body">
                        <h5 class="card-title">Electronics</h5>
                        <p class="card-text">Latest gadgets and devices</p>
                        <a href="/category/electronics" class="btn btn-sm btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://via.placeholder.com/300x200?text=Fashion" class="card-img-top" alt="Fashion">
                    <div class="card-body">
                        <h5 class="card-title">Fashion</h5>
                        <p class="card-text">Trendy clothes and accessories</p>
                        <a href="/category/fashion" class="btn btn-sm btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://via.placeholder.com/300x200?text=Home" class="card-img-top" alt="Home">
                    <div class="card-body">
                        <h5 class="card-title">Home & Living</h5>
                        <p class="card-text">Everything for your home</p>
                        <a href="/category/home" class="btn btn-sm btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mt-5">
            <div class="col-12 text-center py-4 bg-primary text-white rounded">
                <h2>Ready to start shopping?</h2>
                <p class="lead">Join thousands of satisfied customers today</p>
                <a href="/register" class="btn btn-light btn-lg px-4">Create Account</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>About Us</h5>
                    <p>Your trusted e-commerce platform with quality products and excellent service.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="/products" class="text-white text-decoration-none">Products</a></li>
                        <li><a href="/about" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="/contact" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-pinterest fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel E-Commerce') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="/privacy" class="text-white text-decoration-none me-3">Privacy Policy</a>
                    <a href="/terms" class="text-white text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Dark mode toggle script -->
    <script>
        // Simple dark mode toggle for demonstration
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.createElement('button');
            darkModeToggle.className = 'btn btn-sm btn-outline-secondary position-fixed bottom-0 end-0 m-3';
            darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            darkModeToggle.onclick = function() {
                document.body.classList.toggle('dark');
                document.querySelector('.logo-section').classList.toggle('dark');
                this.innerHTML = document.body.classList.contains('dark') ? 
                    '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            };
            document.body.appendChild(darkModeToggle);
        });
    </script>
</body>
</html>