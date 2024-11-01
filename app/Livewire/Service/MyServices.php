<?php

namespace App\Livewire\Service;

use Livewire\Component;
use App\Models\Service;

class MyServices extends Component
{
    public function render()
    {
        $services = Service::where('user_id', auth()->user()->id)->get();
        // $services = Service::get();
        // dd($services);
        return view('livewire.service.my-services', compact('services'));
    }
}
