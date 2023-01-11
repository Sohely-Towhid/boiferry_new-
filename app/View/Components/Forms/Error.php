<?php

namespace App\View\Components\Forms;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class Error extends Component
{
    public $errors, $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->errors = request()->session()->get('errors', new ViewErrorBag);
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.error');
    }
}
