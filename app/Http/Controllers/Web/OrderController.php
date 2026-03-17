<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCancelReq;
use App\Http\Requests\OrderCreateReq;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(private CartService $service, private OrderService $orderService) {}

    public function index()
    {
        return view('web.pages.checkout'); // no need to pass any data — mount() handles it all
    }

    public function store(OrderCreateReq $request): JsonResponse
    {

        $data = $request->validated();

        $result = $this->orderService->create($data);

        return response()->json(['redirect_url' => route('cart'), ...$result]);
    }

    public function cancel(OrderCancelReq $request): JsonResponse
    {
        $orderId = $request->validated()['order_id'];

        $this->orderService->cancelOrder($orderId);

        return response()->json(['message' => 'Order cancelled successfully']);
    }
}
