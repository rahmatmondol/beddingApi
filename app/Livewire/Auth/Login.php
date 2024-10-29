<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('message', 'User logged in successfully.');
            return $this->redirect('/auth/dashboard', navigate: true);
        } else {
            session()->flash('message', 'Invalid credentials.');
        }
    }
}
