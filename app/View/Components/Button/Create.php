<?php

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Create extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $url, public string $params = '', public string $label = 'Tambah Data')
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button.create');
    }
}
