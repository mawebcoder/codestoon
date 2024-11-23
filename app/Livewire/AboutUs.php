<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('درباره ما')]
class AboutUs extends Component
{
    public function render()
    {
        return view('livewire.about-us');
    }
}
