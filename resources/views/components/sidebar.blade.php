<nav class="sidebar close">
    <header>
        <div class="image-text">
            <span class="image">
                <!-- Puedes agregar un logo aquí -->
                <!--<img src="logo.png" alt="Logo">-->
            </span>
            <div class="text logo-text">
                <span class="name">Tu Institución</span>
                <span class="profession">Gestión Académica</span>
            </div>
        </div>
        <i class='bx bx-chevron-right toggle'></i>
    </header>

    <div class="menu-bar">
        <div class="menu">
            <ul class="menu-links">
                @foreach ($links as $link)
                    <li class="nav-link">
                        <a href="{{ route($link['route']) }}">
                            <i class='bx {{ $link['icon'] }} icon'></i>
                            <span class="text nav-text">{{ $link['text'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bottom-content">
            <li class="nav-link">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="text nav-text logout-button">
                        <i class='bx bx-log-out icon'></i>
                        Cerrar Sesión
                    </button>
                </form>
            </li>
            <li class="mode">
                <div class="sun-moon">
                    <i class='bx bx-moon icon moon'></i>
                    <i class='bx bx-sun icon sun'></i>
                </div>
                <span class="mode-text text">Dark mode</span>
                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>
        </div>
    </div>
</nav>
