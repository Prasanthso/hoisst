<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Exception;

class WhatsAppController extends Controller
{
    //

    public function index()
    {
        return view('whatsapp');
    }

    public function store(Request $request)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
        $recipientNumber = $request->phone;
        $message = $request->message;

        try {
            $twilio = new Client($twilioSid, $twilioToken);
            $twilio->messages->create(
                $recipientNumber,
                [
                    "from" => "whatsapp:+". $twilioWhatsAppNumber,
                    "body" => $message,
                ]
            );

            return back()->with(['success' => 'WhatsApp message sent successfully!']);
        } catch (Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }

    public function sendMessage($to, $message, $type = 'whatsapp')
    {
        try {
            $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $client->messages->create("whatsapp:" . $to, [
                'from' => env('TWILIO_WHATSAPP_NUMBER'),
                'body' => $message
            ]);
            // Log::info("WhatsApp message sent to: " . $to);
        } catch (\Exception $e) {
            Log::error("Error sending WhatsApp message: " . $e->getMessage());
        }
    }


}
