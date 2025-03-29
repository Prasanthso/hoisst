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

    public function twilioaccount()
    {
        return view('twilioauthentication');
    }

    public function storeTwiliokey(Request $request)
    {
        $twilioSid = $request->sid;
        $twilioToken = $request->authtoken;
        $twilioWhatsAppNumber = $request->twiliophone;

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

    // twilio authentication update
    public function updateSid(Request $request)
    {
        $request->validate([
            'sid' => 'required|string',
        ]);

        // Update .env file
        $this->updateEnvVariable('TWILIO_SID', $request->sid);

        return redirect()->back()->with('success', 'Twilio SID updated successfully!');
    }

    private function updateEnvVariable($key, $value)
    {
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            $envContents = File::get($envPath);
            $pattern = "/^{$key}=.*/m";

            if (preg_match($pattern, $envContents)) {
                // Update existing key
                $envContents = preg_replace($pattern, "{$key}={$value}", $envContents);
            } else {
                // Append new key
                $envContents .= "\n{$key}={$value}\n";
            }

            File::put($envPath, $envContents);
        }

        // Clear config cache
        \Artisan::call('config:clear');
    }
}
