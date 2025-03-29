<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TwilioController extends Controller
{
    //

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
