<?php

namespace App\Livewire\Members;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Members')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.members.index');
    }
}
