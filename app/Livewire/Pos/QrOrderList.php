<?php

namespace App\Livewire\Pos;

use App\Services\OrderService;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;

class QrOrderList extends Component
{
    public ?int $previousWaitingCount = null;

    #[On('qr-orders-updated')]
    public function render()
    {
        $waitingOrders = Cache::rememberForever('pos_waiting_orders', function () {
            return app(OrderService::class)->getWaitingOrders();
        });

        $currentCount = $waitingOrders->count();
        if ($this->previousWaitingCount !== null && $currentCount > $this->previousWaitingCount) {
            $this->dispatch('new-qr-order');
        }
        $this->previousWaitingCount = $currentCount;

        return view('livewire.pos.qr-order-list', [
            'waitingOrders' => $waitingOrders,
        ]);
    }
}
