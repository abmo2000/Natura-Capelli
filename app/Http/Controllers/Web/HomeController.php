<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
      
  
         $products =Product::query()
           ->where('featured' , true)
          ->where('in_stock' , true)
          ->get();

          $data['products'] = $products;

          return view('web.pages.home')->with($data);
    }
}
