<?php

namespace App\Http\Controllers;

use App\Models\Salon; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class QRController extends Controller
{
    public function generarQR($idSalon, $nombre)
    {
        $salon = Salon::find($idSalon);
        
        if (!$salon) {
            return response()->json(['error' => 'Salon not found'], 404);
        }

        $horarios = $salon->horarios;

        $qrData = "/salones/{$idSalon}/{$nombre}"; 

        $qrUrl = QrCode::format('png')->size(200)->generate($qrData);

        return response()->json([
            'qrUrl' => $qrUrl,
            'horarios' => $horarios 
        ]);
    }
}
