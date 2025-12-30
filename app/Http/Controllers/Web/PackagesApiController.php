<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackagesApiController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Package::query()
        ->with(['products'])
        ->withCount(['products']);



      // $query = $query->when(($request->has('categories') && is_array($request->categories)) , fn($q) => $q->whereIn('category_id', $request->categories));
    //    $query = $query->when(($request->has('routines') && is_array($request->routines)) , 
    //    fn($q) => $q->whereRelation('routines', fn($q) => $q->whereIn('products_routines.routine_id' , $request->routines)));

        $perPage = $request->get('per_page', 9);
        $packages = $query->paginate($perPage);

        
        $transformedPackages = $packages
        ->values()
        ->map(function ($package , $index) {
             $packageData['html'] = view('components.package', [
                    'package' => $package,
                    'index' => $index
                ])->render();
            
                return $packageData;

        });

        return response()->json([
            'success' => true,
            'data' => $transformedPackages,
            'total' => $packages->total(),
            'per_page' => $packages->perPage(),
            'current_page' => $packages->currentPage(),
            'last_page' => $packages->lastPage(),
        ]);
    }
}
