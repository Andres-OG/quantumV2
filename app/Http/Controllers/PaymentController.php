<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Institution;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    // Muestra el formulario de pago
    public function showPaymentForm()
    {
        $step = session('step_completed');

        if ($step !== 'institution_registered') {
            return redirect()->route('register')->with('error', 'Por favor, registra una institución primero.');
        }

        return view('payment');
    }

    public function processPayment(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'card_number' => ['required', 'regex:/^\d{4} \d{4} \d{4} \d{4}$/'],
            'card_name' => [
                'required',
                'string',
                'regex:/^[A-Za-z\s]+$/', // Solo permite letras sin acentos y espacios
                'min:3', // Al menos 3 letras en total
                'max:50',
                function ($attribute, $value, $fail) {
                    // Verifica que tenga al menos dos palabras
                    if (str_word_count($value) < 2) {
                        $fail('El nombre debe tener al menos dos palabras.');
                    }
                    // Verifica que no tenga más de 3 letras repetidas consecutivas
                    if (preg_match('/(.)\\1{3,}/', $value)) {
                        $fail('El nombre no puede contener letras repetidas en exceso.');
                    }
                },
            ],
            'expiry_date' => ['required', 'regex:/^\d{2}\/\d{2}$/', function ($attribute, $value, $fail) {
                [$month, $year] = explode('/', $value);
                $month = (int) $month;
                $year = (int) $year;
                $currentYear = (int) date('y');
                $currentMonth = (int) date('m');

                if ($month < 1 || $month > 12 || $year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
                    $fail('La fecha de expiración debe ser válida y futura.');
                }
            }],
            'cvv' => ['required', 'digits:3'],
        ], [
            'card_number.regex' => 'El número de tarjeta debe estar en el formato 1234 5678 9012 3456.',
            'card_name.regex' => 'El nombre solo puede contener letras sin acentos y espacios.',
            'card_name.min' => 'El nombre debe contener al menos 3 letras.',
            'card_name.max' => 'El nombre no puede exceder los 50 caracteres.',
            'card_name.required' => 'El nombre es obligatorio.',
            'expiry_date.regex' => 'La fecha de expiración debe estar en el formato MM/AA.',
            'cvv.digits' => 'El CVV debe tener exactamente 3 dígitos.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simula el éxito del pago
        $paymentSuccess = true;

        if ($paymentSuccess) {
            try {
                $institutionName = Session::get('institution_name');

                if (!$institutionName) {
                    return redirect()->back()->withErrors(['error' => 'No se encontró una institución en la sesión.']);
                }

                $institution = Institution::where('name', $institutionName)->first();

                if (!$institution) {
                    return redirect()->back()->withErrors(['error' => 'Institución no encontrada.']);
                }

                $institution->update(['payment' => 7000.00]);

                session(['step_completed' => 'payment_completed']);


                return redirect()->route('registerAdmin')->with('payment_success', true);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Error al procesar el pago: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->withErrors(['error' => 'Error en el pago. Inténtalo de nuevo.']);
    }
}
