<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OtpController extends Controller
{
    public function sendOtp($phone)
    {
        $phoneNumber = $phone;

        $otp = $this->generateOtp(); // Implement your OTP generation logic

        $firebaseApiKey = 'AIzaSyCIEYpnSUCwf0yS5ANSlYqrkweYuxyBjUg'; // Replace with your Firebase Web API Key

        // Construct the request to Firebase Authentication's "sendOobCode" endpoint
        $firebaseUrl = "https://identitytoolkit.googleapis.com/v1/accounts:sendOobCode?key=$firebaseApiKey";
        $client = new Client();

        $response = $client->post($firebaseUrl, [
            'json' => [
                'requestType' => 'VERIFY_PHONE_NUMBER', // Corrected requestType
                'phoneNumber' => $phoneNumber,
            ],
        ]);

        return $response;

        // Handle the response, log errors, etc.
        if ($response->getStatusCode() === 200) {
            return response()->json(['message' => 'OTP sent successfully']);
        }

        return response()->json(['message' => 'Failed to send OTP'], 500);
    }

    private function generateOtp()
    {
        // Implement your OTP generation logic, e.g., using random numbers
        return mt_rand(1000, 9999);
    }
}
