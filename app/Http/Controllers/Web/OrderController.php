<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCancelReq;
use App\Http\Requests\OrderCreateReq;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private CartService $service, private OrderService $orderService) {}

    public function index()
    {

        $total = $this->service->getTotal();
        $orderSettings = getBuisnessSettings('order_settings');
        $buisnessSettings = getBuisnessSettings('buisness-info');
        $isUserFirstOrderDeliveryFree = (! Auth::user()->orders()->exists() && ($orderSettings?->allow_first_order_for_free));

        return view('web.pages.checkout')->with(['total' => $total,  'orderSettings' => $orderSettings, 'buisnessSettings' => $buisnessSettings, 'isFirstOrder' => $isUserFirstOrderDeliveryFree]);

    }

    public function store(OrderCreateReq $request): JsonResponse
    {

        $data = $request->validated();

        $result = $this->orderService->create($data);

        return response()->json($result);
    }

    public function cancel(OrderCancelReq $request): JsonResponse
    {
        $orderId = $request->validated()['order_id'];

        $this->orderService->cancelOrder($orderId);

        return response()->json(['message' => 'Order cancelled successfully']);
    }
}
