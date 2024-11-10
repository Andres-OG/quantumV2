<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Institution;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('payment');
    }

    public function processPayment(Request $request)
    {
        $paymentSuccess = true; // Simulamos que el pago es exitoso para este ejemplo
    
        if ($paymentSuccess) {
            try {
                $institutionName = Session::get('institution_name');
                $institution = Institution::where('name', $institutionName)->first();
    
                if ($institution) {
                    $institution->update(['payment' => 7000.00]);
                }
    
                // Establece la variable de sesión para mostrar la alerta de éxito
                Session::flash('payment_success', true);
    
                // Redirige de vuelta al formulario de pago para que se muestre la alerta de éxito
                return redirect()->route('payment.show');
            } catch (\Exception $e) {
                return redirect()->route('payment.show')->withErrors('Error al guardar el pago: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('payment.show')->withErrors('Error en el pago. Inténtalo de nuevo.');
        }
    }
    
}
