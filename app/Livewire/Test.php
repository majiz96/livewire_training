<?php

namespace App\Livewire;

use Livewire\Component;

class Test extends Component
{
    public string $message;

    public function messageRun()
    {
        return $this->message;
    }

    public function render()
    {
        return view('livewire.test');
    }
}
