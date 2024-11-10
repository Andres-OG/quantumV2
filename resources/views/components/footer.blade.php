<!-- resources/views/components/footer.blade.php -->

<footer class="site-footer">
    <div class="footer-container">
        <!-- Enlaces principales -->
        <div class="footer-links">
            <h3>Quantum Innovators</h3>
            <ul>
                <li><a href="#about">Sobre Nosotros</a></li>
                <li><a href="#services">Servicios</a></li>
                <li><a href="#contact">Contacto</a></li>
                <li><a href="#blog">Blog</a></li>
            </ul>
        </div>
        
        <!-- Redes Sociales -->
        <div class="footer-social">
            <h3>Síguenos</h3>
            <ul>
                <li><a href="#"><img src="{{ asset('images/facebook-icon.png') }}" alt="Facebook"></a></li>
                <li><a href="#"><img src="{{ asset('images/twitter-icon.png') }}" alt="Twitter"></a></li>
                <li><a href="#"><img src="{{ asset('images/linkedin-icon.png') }}" alt="LinkedIn"></a></li>
                <li><a href="#"><img src="{{ asset('images/instagram-icon.png') }}" alt="Instagram"></a></li>
            </ul>
        </div>
        
        <!-- Información de Contacto -->
        <div class="footer-contact">
            <h3>Contáctanos</h3>
            <p>Email: info@quantuminnovators.com</p>
            <p>Teléfono: +123 456 7890</p>
        </div>
    </div>
    <div class="footer-bottom">
        <img src="{{ asset('storage/LogoST.png') }}" alt="Quantum Innovators">
        <p>&copy; 2024 Quantum Innovators. Todos los derechos reservados.</p>
    </div>
</footer>
