<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateReq;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;


class OrderController extends Controller
{
    public function store(OrderCreateReq $request):JsonResponse{
        
         $data = $request->validated();
         
         $result = (new OrderService())($data);

         return response()->json($result);
    }
}
