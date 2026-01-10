<?php

namespace App\Http\Controllers\Web;

use App\Jobs\ContactMessageJob;
use Illuminate\Http\Request;
use App\Rules\PhoneValidationRule;
use function Laravel\Prompts\info;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Mail;


class ContactController extends Controller
{
    public function __invoke(Request $request)
    {
       

     $request->merge(['phone' => $request->full_phone]);
      
        $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email',
        'phone' => ['required', new PhoneValidationRule()],
        'message' => 'required|string',
    ]);

     $data = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'userMessage' => $request->message,  
    ];

      dispatch(new ContactMessageJob($data));
     
      return back()->with('success' , trans('contact.success-msg'));
    }
}
