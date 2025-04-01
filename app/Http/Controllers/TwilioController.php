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
     public function updateTwilio(Request $request)
     {
         $request->validate([
             'sid' => 'required|string',
             'token' => 'required|string',
             'whatsappphone' => 'required|string',
         ]);

        //  // Update .env file
        //  $this->setEnv([
        //     'TWILIO_SID' => $request->sid,
        //     'TWILIO_AUTH_TOKEN' => $request->token,
        //     'TWILIO_WHATSAPP_NUMBER' => $request->whatsappphone,
        // ]);

        // $this->setEnv('TWILIO_SID', $request->sid);
        // $this->setEnv('TWILIO_AUTH_TOKEN', $request->token);
        // $this->setEnv('TWILIO_WHATSAPP_NUMBER', $request->whatsappphone);

        $this->updateEnvVariable('TWILIO_SID', $request->sid);
        $this->updateEnvVariable('TWILIO_AUTH_TOKEN', $request->token);
        $this->updateEnvVariable('TWILIO_WHATSAPP_NUMBER', $request->whatsappphone);

         return redirect()->back()->with('success', 'Twilio key(s) updated successfully!');
     }

    private function setEnv($key, $value)
    {
        $envFilePath = base_path('.env');

        if (!file_exists($envFilePath)) {
            return;
        }

        $envContents = file_get_contents($envFilePath);
        $escapedValue = trim($value);

        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $envContents)) {
            // If key exists, update it
            $envContents = preg_replace($pattern, "{$key}=\"{$escapedValue}\"", $envContents);
        } else {
            // If key does not exist, append it
            $envContents .= "\n{$key}=\"{$escapedValue}\"";
        }

        file_put_contents($envFilePath, $envContents);

        // Refresh Laravel environment variables
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
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
