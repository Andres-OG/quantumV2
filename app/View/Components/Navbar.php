<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Navbar extends Component
{
    public $buttonText;
    public $buttonText2;

    public function __construct($buttonText = 'Obtener Quantum Innovators Gratis', $buttonText2 = 'Iniciar Sesión')
    {
        $this->buttonText = $buttonText;
        $this->buttonText2 = $buttonText2;
    }

    public function render()
    {
        return view('components.navbar');
    }
}
