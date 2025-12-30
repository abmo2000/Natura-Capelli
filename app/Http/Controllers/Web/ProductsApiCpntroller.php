<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsApiCpntroller extends Controller
{
    public function __invoke(Request $request)
    {
         $query = Product::with(['category' , 'routines']);


       $query = $query->when(($request->has('categories') && is_array($request->categories)) , fn($q) => $q->whereIn('category_id', $request->categories));
       $query = $query->when(($request->has('routines') && is_array($request->routines)) , 
       fn($q) => $q->whereRelation('routines', fn($q) => $q->whereIn('products_routines.routine_id' , $request->routines)));

        $perPage = $request->get('per_page', 9);
        $products = $query->paginate($perPage);

        
        $transformedProducts = $products
        ->values()
        ->map(function ($product , $index) {
             $productData['html'] = view('components.product', [
                    'product' => $product,
                    'index' => $index
                ])->render();
            
                return $productData;

        });

        return response()->json([
            'success' => true,
            'data' => $transformedProducts,
            'total' => $products->total(),
            'per_page' => $products->perPage(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }
}
