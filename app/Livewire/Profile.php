<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('پروفایل کاربری')]
class Profile extends Component
{
    public function render()
    {
        return view('livewire.profile');
    }
}
