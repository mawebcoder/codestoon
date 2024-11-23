<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('فرم ثبت نام')]
class Signup extends Component
{
    public function render()
    {
        return view('livewire.signup');
    }
}