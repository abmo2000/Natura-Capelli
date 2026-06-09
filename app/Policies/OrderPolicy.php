<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user can cancel the order.
     */
    public function cancelOrder(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true; // Admin can cancel any order
        }
        // Only the order owner can cancel the order
        if ($user->id !== $order->customer_id) {
            return false;
        }

        // Can only cancel if order is in a cancellable state
        return $order->status === OrderStatus::PENDING->value;
    }
}
