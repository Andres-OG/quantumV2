<!-- resources/views/components/navbar.blade.php -->
<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="/" class="navbar-logo">
            <img src="{{ asset('storage/logoNaranja.png') }}" alt="Logo" class="navbar-logo_img">
            <span class="logo-text">Quantum Innovators</span>
        </a>

        <!-- Links -->
        <ul class="navbar-links">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Productos</a>
                <ul class="dropdown-menu">
                    <li><a href="#">Producto 1</a></li>
                    <li><a href="#">Producto 2</a></li>
                    <li><a href="#">Producto 3</a></li>
                </ul>
            </li>
            <li><a href="#">Precios</a></li>
            <li><a href="#">Recursos</a></li>
        </ul>

        <!-- Action Button -->
        <div class="navbar-action">
            <a href="{{ route('register') }}" class="btn-primary">{{ $buttonText }}</a>
            <a href="{{ route('login') }}" class="btn-secondary">{{ $buttonText2 }}</a>

        </div>
    </div>
</nav>
