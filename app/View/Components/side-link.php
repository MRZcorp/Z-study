<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SideLink extends Component
{
    public function __construct(
        public string $link,
        public string $icon,
        public string $label,
        public string $drop = 'off',
        public string $icon2 = ''
    ) {}

    public function render()
    {
        return view('components.side-link');
    }
}
