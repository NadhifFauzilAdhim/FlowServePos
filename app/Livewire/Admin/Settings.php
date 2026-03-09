<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Settings extends Component
{
    public float $taxRate = 8.00;

    public bool $isStoreOpen = true;

    public function mount()
    {
        $this->taxRate = (float) Setting::get('tax_rate', 8.00);
        $this->isStoreOpen = (bool) Setting::get('is_store_open', '1');
    }

    public function saveTaxRate()
    {
        $this->validate([
            'taxRate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('tax_rate', $this->taxRate);
        session()->flash('success_tax', 'Tax rate successfully updated.');
    }

    public function toggleStoreStatus()
    {
        $this->isStoreOpen = ! $this->isStoreOpen;
        Setting::set('is_store_open', $this->isStoreOpen ? '1' : '0');

        session()->flash('success_store', 'Store status successfully updated to '.($this->isStoreOpen ? 'Open' : 'Closed').'.');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
