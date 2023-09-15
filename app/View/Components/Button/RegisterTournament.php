<?php

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RegisterTournament extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button.register-tournament');
    }
}
