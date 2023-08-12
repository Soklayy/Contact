<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::orderBy('created_at', 'desc')->get();

        return response($contact);
    }

    /**
     * crete new contact
     */
    public function store(Request $request)
    {
        // Validate the contact form data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // Save the contact form data to the database
        $contactForm = Contact::create($request->all());

        // Send an email to the admin
        Mail::to('yornsoklay81@gmail.com')->send(new MailNotify([
            'subject' => 'From ' . $request->email . ' : ' . $request->subject,
            'message' => $request->message,
        ]));


        // Redirect the user to the success page
        return response([
            'message' => 'success'
        ]);
    }

    public function sendMail(Request $request, Contact $contact)
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        // 
        // Send an email to the admin

        try {
            Mail::to($contact->email)->send(new MailNotify([
                'subject' => $request->subject,
                'message' => $request->message,
            ]));
    
            $contact->update([
                'reply' => true
            ]);
    
            return response()->json([
                'message' => 'success',
            ]);
        } catch (Exception $th) {
            return response(['message'=>'can not send'],500);
        }
    }

    public function show(Contact $contact){
        return response($contact);
    }

}
