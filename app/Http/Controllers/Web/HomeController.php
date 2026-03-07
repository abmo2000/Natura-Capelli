<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
    $categories = Category::query()
      ->with('translations')
      ->latest('id')
      ->get();

    $categorySections = Category::query()
      ->with('translations')
      ->get()
      ->map(function (Category $category) {
        $products = Product::query()
          ->where('category_id', $category->id)
          ->where('in_stock', true)
          ->with(['sale', 'translations'])
          ->latest('id')
          ->limit(5)
          ->get();

        return [
          'id' => $category->id,
          'title' => $category->title,
          'products' => $products,
        ];
      })
      ->filter(fn (array $section) => $section['products']->isNotEmpty())
      ->values();

    return view('web.pages.home')->with([
      'categories' => $categories,
      'categorySections' => $categorySections,
    ]);
    }
}
