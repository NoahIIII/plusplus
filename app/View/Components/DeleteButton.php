<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteButton extends Component
{
    public $route;
    public $title;

    public function __construct($route, $title)
    {
        $this->route = $route;
        $this->title = $title;
    }

    public function render()
    {
        return view('components.delete-button');
    }
}
