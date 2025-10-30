<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportNavigate;

class Dashboard extends Component
{
    public string $message = 'Hello World!';

    public function messPrint()
    {
        $this->message = 'Well done!';
    }

    public function resetMessage()
    {
        $this->reset('message');
    }

    public function logout()
    {
        Auth::logout();
        session()->regenerate();
        session()->regenerateToken();
        return redirect('/users');
    }

    public function render()
    {
        return view('livewire.dashboard',['user'=>Auth::user()?->name]);
    }
}
