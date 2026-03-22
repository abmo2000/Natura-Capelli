<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderInvoiceController extends Controller
{
    public function __invoke(Order $order): View
    {
        $user = Auth::user();

        if ($user instanceof User && $user->isSalesAdmin() && $order->admin_creator_id !== $user->id) {
            abort(403, 'You are not allowed to view this invoice.');
        }

        $order->loadMissing(['customer', 'items.typeable', 'adminCreator', 'coupon']);

        return view('dashboard.orders.invoice', [
            'order' => $order,
        ]);
    }
}
