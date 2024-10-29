<?php

namespace App\Livewire;

use Livewire\Component;

class ServiceSlider extends Component
{
    public $services;
    public function render()
    {
        return view('livewire.service-slider');
    }

    public function mount()
    {
        // get all services with images
        $this->services = \App\Models\Service::with('images')->get();
    }
}
