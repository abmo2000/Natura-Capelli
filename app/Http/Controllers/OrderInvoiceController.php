<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderInvoiceController extends Controller
{
    public function __invoke(Order $order): View
    {
        $order->loadMissing(['customer', 'items.typeable']);

        return view('dashboard.orders.invoice', [
            'order' => $order,
        ]);
    }
}
