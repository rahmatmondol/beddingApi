<?php

namespace App\Livewire;
use App\Models\Service;
use Livewire\Component;

class ReletedServicesSlider extends Component
{
    public $categoryId;
    public function mount($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function render()
    {
        $services = Service::where('category_id', $this->categoryId)->get();
        return view('livewire.releted-services-slider', compact('services'));
    }

}

