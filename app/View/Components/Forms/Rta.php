<?php

namespace App\View\Components\Forms;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class Rta extends Component
{
    public $column, $name, $title, $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($column, $name, $title, $value = '')
    {
        $this->column = $column;
        $this->name = $name;
        $this->title = $title;
        $this->value = $value;
        $this->errors = request()->session()->get('errors', new ViewErrorBag);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.forms.rta');
    }
}
