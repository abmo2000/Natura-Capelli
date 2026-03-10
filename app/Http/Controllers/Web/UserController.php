<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function profile(): View
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->latest()
            ->paginate(10);

        return view('web.pages.users.profile', compact('user', 'orders'));
    }

    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        $request->user()->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully.',
        ], 200);
    }
}
