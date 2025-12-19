<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    public function index(){

         $categories = Category::query()->select(['id'])->get();
          
          
          return view('web.pages.shop.index')->with(['categories' => $categories]);
    }
}
