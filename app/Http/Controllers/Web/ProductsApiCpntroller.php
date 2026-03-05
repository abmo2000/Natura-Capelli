<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\ProductTrial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsApiCpntroller extends Controller
{
    public function __invoke(Request $request , $is_trial = false)
    {
       $query = $is_trial === "true" 
        ? ProductTrial::with(['product.category', 'product.routines'])
        : Product::with(['category', 'routines']);

        $query = $query->when(($request->has('categories') && is_array($request->categories)), 
            fn($q) => $is_trial === "true"
                ? $q->whereHas('product', fn($subQ) => $subQ->whereIn('category_id', $request->categories))
                : $q->whereIn('category_id', $request->categories)
        );

        $query = $query->when(($request->has('routines') && is_array($request->routines)), 
            fn($q) => $is_trial === "true"
                ? $q->whereHas('product.routines', fn($subQ) => $subQ->whereIn('routines.id', $request->routines))
                : $q->whereHas('routines', fn($subQ) => $subQ->whereIn('routines.id', $request->routines))
        );

        $query = $query->when(($request->has('brands') && is_array($request->brands)),
            fn($q) => $is_trial === "true"
                ? $q->whereHas('product', fn($subQ) => $subQ->whereIn('brand', $request->brands))
                : $q->whereIn('brand', $request->brands)
        );

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
