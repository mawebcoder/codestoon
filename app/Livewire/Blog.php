<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('لیست مقالان')]
class Blog extends Component
{
    public function render()
    {
        return view('livewire.blog');
    }
}
