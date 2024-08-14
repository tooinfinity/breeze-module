<?php

namespace Modules\Auth\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('auth::layouts.app');
    }
}
