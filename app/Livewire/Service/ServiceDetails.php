<?php

namespace App\Livewire\Service;

use Livewire\Component;
use App\Models\Service;

class ServiceDetails extends Component
{
    public $serviceId;

    public function mount($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function render()
    {
        $service = Service::findOrFail($this->serviceId);

        return view('livewire.service.service-details', compact('service'));
    }
}
