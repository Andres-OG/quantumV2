<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pago</title>
    <link href="{{ asset('css/payment.css') }}" rel="stylesheet">
    
    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <!-- Vista previa de la tarjeta -->
        <div class="card-preview">
            <div class="card-header">
                <span class="bank-name">Banco</span>
                <span class="card-type">Visa</span>
            </div>
            <div class="card-number" id="display-card-number">•••• •••• •••• ••••</div>
            <div class="card-details">
                <div>
                    <span id="display-card-name">NOMBRE APELLIDO</span>
                </div>
                <div>
                    <span id="display-expiry-date">MM/AA</span>
                </div>
                <div>
                    <span id="display-cvv">CVV</span>
                </div>
            </div>
        </div>

        <!-- Formulario de Pago -->
        <div class="register-container_form">
            <h2>Simular Pago</h2>

            <!-- Mensajes de Error de Laravel -->
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payment.process') }}" method="POST" class="payment-form" id="payment-form" onsubmit="return validateExpiryDate()">
                @csrf
                <!-- Número de Tarjeta -->
                <input
                    type="text"
                    id="card-number"
                    name="card_number"
                    class="form-input"
                    maxlength="19"
                    placeholder="1234 5678 9012 3456"
                    required
                    pattern="\d{4} \d{4} \d{4} \d{4}"
                    title="Debe ser un número de tarjeta en el formato 1234 5678 9012 3456"
                    oninput="formatCardNumber(this)">

                <!-- Nombre en la Tarjeta -->
                <input
                    type="text"
                    id="card-name"
                    name="card_name"
                    class="form-input"
                    placeholder="NOMBRE APELLIDO"
                    required
                    maxlength="50"
                    oninput="updateCardName(this)">

                <!-- Fecha de Expiración y CVV -->
                <div class="form-group-inline">
                    <div class="form-group">
                        <input
                            type="text"
                            id="expiry-date"
                            name="expiry_date"
                            class="form-input-doble"
                            maxlength="5"
                            placeholder="MM/AA"
                            required
                            pattern="\d{2}/\d{2}"
                            title="Debe estar en el formato MM/AA"
                            oninput="formatExpiryDate(this)">
                    </div>

                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input
                            type="text"
                            id="cvv"
                            name="cvv"
                            class="form-input-doble"
                            maxlength="3"
                            placeholder="123"
                            required
                            pattern="\d{3}"
                            title="Debe ser un CVV de 3 dígitos"
                            oninput="formatCVV(this)">
                    </div>
                </div>

                <!-- Botón de Enviar -->
                <button type="submit" class="payment-button">Pagar</button>
            </form>
        </div>
    </div>

    <!-- JavaScript para SweetAlert2 y Validación -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '').slice(0, 16);
            input.value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            document.getElementById('display-card-number').textContent = input.value || '•••• •••• •••• ••••';
        }

        function updateCardName(input) {
            document.getElementById('display-card-name').textContent = input.value.toUpperCase() || 'NOMBRE APELLIDO';
        }

        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, '').slice(0, 4);
            input.value = value.length >= 2 ? value.substring(0, 2) + '/' + value.substring(2) : value;
            document.getElementById('display-expiry-date').textContent = input.value || 'MM/AA';
        }

        function formatCVV(input) {
            input.value = input.value.replace(/\D/g, '').slice(0, 3);
            document.getElementById('display-cvv').textContent = input.value || 'CVV';
        }

        function validateExpiryDate() {
            const expiryDate = document.getElementById("expiry-date").value;
            const [month, year] = expiryDate.split("/").map(Number);
            const currentYear = new Date().getFullYear() % 100; // Últimos dos dígitos del año actual
            const currentMonth = new Date().getMonth() + 1;

            // Validar mes y año
            if (month < 1 || month > 12 || year < currentYear || (year === currentYear && month < currentMonth)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Fecha de expiración inválida',
                    text: 'Por favor, ingresa una fecha válida y futura en el formato MM/AA.',
                    confirmButtonColor: '#FA653E'
                });
                return false;
            }
            return true;
        }

        // Mostrar alerta de éxito si el pago fue exitoso
        @if(session('payment_success'))
            Swal.fire({
                icon: 'success',
                title: 'Pago exitoso',
                text: 'Su pago ha sido procesado con éxito.',
                confirmButtonColor: '#FA653E'
            }).then(() => {
                // Redirige a la página de inicio u otra URL después de cerrar la alerta
                window.location.href = "{{ route('registerAdmin') }}";
            });
        @endif
    </script>
</body>
</html>
