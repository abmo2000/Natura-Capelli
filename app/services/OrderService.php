<?php

namespace App\Services;

use App\Commands\CreateGuestCommand;
use App\Commands\CreateOrderCommand;
use App\Enums\OrderStatus;
use App\Events\OrderCancelled;
use App\Exceptions\NotAllowedToCancelOrder;
use Illuminate\Pipeline\Pipeline;

class OrderService
{
    protected array $pipes = [
        CreateGuestCommand::class,
        CreateOrderCommand::class,
    ];

    public function create(array $data)
    {
        return app(Pipeline::class)
            ->send($data)
            ->through($this->pipes)
            ->thenReturn();
    }

    public function cancelOrder(string $orderId): void
    {

        $order = auth()->user()
            ->orders()
            ->pending()
            ->whereNotIn('status', [OrderStatus::CANCELLED->value, OrderStatus::COMPLETED->value, OrderStatus::SHIPPED->value])
            ->where('order_id', $orderId)
            ->firstOrFail();

        if (! auth()->user()->can('cancelOrder', $order)) {
            throw new NotAllowedToCancelOrder;
        }

        $order->update(['status' => OrderStatus::CANCELLED->value]);

        event(new OrderCancelled($order));

    }
}
