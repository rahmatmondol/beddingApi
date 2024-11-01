<?php

namespace App\Livewire\Service;

use Livewire\Component;
use App\Models\Betting;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBidPlacedNotification;

class BidPopup extends Component
{
    public $service;
    public $bidAmount;
    public $additionalNotes;    

    public function mount($service)
    {
        $this->service = $service;
        $this->bidAmount = $service->price;
    }

    public function render()
    {
        return view('livewire.service.bid-popup');
    }

    public function submitBid()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->to('/auth');
        } 

        // Validation rules for the bid
        $this->validate([
            'bidAmount' => 'required|numeric',
            'additionalNotes' => 'nullable'
        ]);

        // Create a new betting record
        $betting = new Betting();
        $betting->betting_amount = $this->bidAmount;
        $betting->additional_details = $this->additionalNotes;
        $betting->user()->associate(auth()->user());
        $betting->service()->associate($this->service);
        $betting->save();

        // Send notification to service owner
        Notification::send($this->service->user, new NewBidPlacedNotification($betting));

        // Flash success message and redirect
        session()->flash('success', 'Successfully placed a bid.');
        return redirect()->to('/service/' . $this->service->id);
    }
}
