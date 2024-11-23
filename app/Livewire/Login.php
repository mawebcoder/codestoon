<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('فرم ورود')]
class Login extends Component
{
    public function render()
    {
        return view('livewire.login');
    }
}
