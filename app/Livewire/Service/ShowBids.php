<?php

namespace App\Livewire\Service;

use Livewire\Component;
use App\Models\Betting;

class ShowBids extends Component
{
    public $service_id;

    public function mount($service_id)
    {
        $this->service_id = $service_id;
    }

    public function render()
    {
        $bids = Betting::where('service_id', $this->service_id)->with('user')->get();

        return view('livewire.service.show-bids', compact('bids'));
    }
}
