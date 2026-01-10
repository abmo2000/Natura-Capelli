<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Rules\PhoneValidationRule;
use Illuminate\Http\Request;
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

       $email = getBuisnessSettings('buisness-info')?->email ?? config('mail.from.address');
        Mail::send('web.emails.contact', $data, function ($message) use ($data , $email) {
            $message->to($email) 
                    ->subject('New Contact Form Submission from ' . $data['name'])
                    ->replyTo($data['email'], $data['name']);
        });

      return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
