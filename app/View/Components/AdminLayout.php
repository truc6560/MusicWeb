<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminLayout extends Component
{
    public string $title;

    public function __construct(string $title = 'Admin Dashboard')
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('components.admin-layout');
    }
}
