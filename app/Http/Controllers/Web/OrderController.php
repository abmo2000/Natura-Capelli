<?php

namespace App\Http\Controllers\Web;

use App\Models\CartItem;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderCreateReq;
use App\Services\CartService;

class OrderController extends Controller
{

    public function __construct(private CartService $service)
    {
        
    }
    public function index(){
       
      $total =  $this->service->getTotal();
      $orderSettings = getBuisnessSettings('order_settings');
      $buisnessSettings = getBuisnessSettings('buisness-info');
      $isUserFirstOrderDeliveryFree = (!Auth::user()->orders()->exists() && ($orderSettings?->allow_first_order_for_free));
     
        return view('web.pages.checkout')->with(['total' => $total ,  'orderSettings' => $orderSettings , 'buisnessSettings' => $buisnessSettings , 'isFirstOrder' => $isUserFirstOrderDeliveryFree]);

    }
    public function store(OrderCreateReq $request):JsonResponse{
        
         $data = $request->validated();
         
         $result = (new OrderService())($data);

         return response()->json($result);
    }
}
