<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', '✅ تم إرسال رسالتك، سنرد عليك في أقرب وقت ممكن!');
    }

    // ✅ دالة الرد على الرسالة
    public function reply(Request $request, Contact $contact)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        // إرسال إيميل للزبون
        Mail::raw($request->reply_message, function ($msg) use ($contact) {
            $msg->to($contact->email)
                ->subject('رد على استفسارك - متجر الأجهزة');
        });

        // تحديث حالة الرسالة إلى مقروءة
        $contact->update(['is_read' => true]);

        return redirect()->back()->with('success', '✅ تم إرسال الرد بنجاح إلى ' . $contact->email);
    }
}
