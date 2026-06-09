<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Mail\AdminOrderCancelNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderCancellationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCancelled $event): void
    {
        $adminEmail = getBuisnessSettings('buisness-info')?->email;
        Mail::to($adminEmail)
            ->send(new AdminOrderCancelNotification($event->order));
    }

    public function failed(OrderCancelled $event, \Throwable $exception): void
    {
        // Log the failure or notify admins
        Log::error('Order cancel notification email failed', [
            'order_id' => $event->order->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
