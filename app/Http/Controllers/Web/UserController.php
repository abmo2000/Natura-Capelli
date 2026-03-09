<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function profile(): View
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->latest()
            ->paginate(8);

        return view('web.pages.users.profile', compact('user', 'orders'));
    }

    public function updateProfile(UpdateUserProfileRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return back()->with('status', 'Profile updated successfully.');
    }
}
