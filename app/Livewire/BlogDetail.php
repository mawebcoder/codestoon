<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Blog')]
class BlogDetail extends Component
{


    public function render()
    {
        return view('livewire.blog-detail');
    }

}
