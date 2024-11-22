<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $links;

    /**
     * Crea una nueva instancia del componente.
     *
     * @param array $links
     */
    public function __construct($links = [])
    {
        $this->links = $links;
    }

    /**
     * Obtiene la vista que representa el componente.
     */
    public function render()
    {
        return view('components.sidebar');
    }
}
