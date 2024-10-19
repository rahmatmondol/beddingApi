<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OtpController;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\StoreCustomerRequest;
use App\Http\Requests\Api\CustomerLoginRequest;
use App\Http\Requests\Api\UpdateCustomerRequest;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use http\Env\Response;
use Illuminate\Support\Facades\File;
use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{

    public function customer(Request $request) : JsonResponse
    {
        try {
            $customer = $request->user('customers');
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "Customer successfully authorized.",
                'data'   => [
                    'info'=> $customer
                ]
            ],  200); // 200
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  403); // 200
        }
    }
    public function logout(Request $request) : JsonResponse
    {
        try {
            if ($request->user('customers')) {
                $request->user('customers')->tokens()->delete();
                return response()->json([
                    'success'   => true,
                    'status'    => 200,
                    'message'   => "Logged out successfully.",
                ],  200); // 200
            } else{
                return response()->json([
                    'success'   => true,
                    'status'    => 200,
                    'message'   => "You have already logged out.",
                ],  200); // 200
            }
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    =>403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ], 403); // 200
        }
    }

    /**
     * Customer Login with mail and phone.
     */
    public function test(): JsonResponse{
        return response()->json([
                'message'=> true
            ]

        );
    }
    public function login(CustomerLoginRequest $request) : JsonResponse
    {
        $loginField = $request->input('email');
        $password = $request->input('password');

        try {
            $credentials = [];
            if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
                // The input is an email
                $credentials['email'] = $loginField;
            } else {
                // The input is a phone number
                $credentials['phone'] = $loginField;
            }

            $customer = Customer::where($credentials)->first();

            if (!$customer || !Hash::check($password, $customer->password)) {
                return response()->json([
                    'success'   => false,
                    'status'    => 401,
                    'message'   => "Unauthenticated customer credentials.",
                    'errors' => 'Invalid credentials.'
                ],  401); // 200
            }
            if ($customer->phone_verify === 0) {
                return response()->json([
                    'success'   => false,
                    'status'    =>403,
                    'message'   => "Customer phone is not verified.",
                    'errors' => 'Phone is not verified.'
                ], 403); // 200
            }
            // $request->user('customers')->tokens()->delete();

            // $customer->tokens()->delete(); // uncomment for live server
            $token = $customer->createToken('authToken')->plainTextToken;
//            Session::put("customer", $token); // for next.js frontend

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "Customer successfully authorized.",
                'token' => $token
            ],  200); // 200
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    =>403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ], 403); // 200
        }
    }

    public function register(StoreCustomerRequest $request)
    {


        try {
            // If the user is not registered, proceed with registration
//            $otp = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $phone = $request->phone;
//            $ttl = 1; // 1 min lock for otp
            $customer = new Customer([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->input("status", false),

            ]);

////            $otpController = new OtpController();
////            $otpResponse =  $otpController->sendOtp($phone);
//
//            return $otpResponse;

            $customer->save();

//            event(new RegisteredCustomer($customer));




            // Handle image upload and update
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = "customer_$customer->id.png";
                $imagePath = "assets/images/customer/$imageName";

                try {
                    // Create the "public/images" directory if it doesn't exist
                    if (!File::isDirectory(public_path("assets/images/customer"))) {
                        File::makeDirectory((public_path("assets/images/customer")), 0777, true, true);
                    }

                    // Save the image to the specified path
                    $image->move(public_path('assets/images/customer'), $imageName);
                    $customer->image = $imagePath;
                    $customer->save();
                } catch (\Exception $e) {
                    //throw $e;
                    // skip if not uploaded
                }
            }


//            // Send the OTP to the user's phone
//            try {
//                Cache::remember("$phone", 60 * $ttl, function () { // disabled for 2 minutes
//                    return true;
//                });
//
//                // start::sending otp
//                $this->sendOtp($phone, $otp);
//                // end::sending otp
//            } catch (\Exception $e) {
//                //throw $e;
//
//                // skip error for first time send OTP
//            }

            $token = $customer->createToken('authToken')->plainTextToken;
            return response()->json([
                'success'   => true,
                'status'    => 201,
                'message'   => "Customer registered successfully.",
                "verify_token" => $token
            ],  201); // 200
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    =>403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ], 403); // 200
        }
    }
    //
    public function delete(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'status'    => 422,
                'message'   => "Validation failed.",
                'errors' => $validator->errors()
            ],  422); // 200
        }

        // get customer
        $customer = $request->user('customers');

        try {
            // Verify the provided password
            if (password_verify($request->password, $customer->password)) {
                // Delete the account from the database
                $customer->tokens()->delete();
                $customer->delete();
            } else {
                return response()->json([
                    'success'   => false,
                    'status'    => 422,
                    'message'   => "Invalid password. Please try again.",
                ],  422); // 200
            }

            return response()->json([
                'success'   => true,
                'status'    => 202,
                'message'   => "Account deleted successfully.",
            ],  202); // 200
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                // 'err' => $e->getMessage(),
            ],  403); // 200
        }
    }
    public function update(UpdateCustomerRequest $request) : JsonResponse
    {
        // get customer
        $customer = $request->user('customers');



        if(Empty($request->all())){
           return response()->json([
               'success'   => false,
               'status'    => 403,
               'message' => 'Nothing to update'
           ],403);
        }

        $credentials = Arr::only($request->all(), [
            'name',
            'email',
            'image',
        ]);

        try {
            // Handle image upload and update
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = "customer_$customer->id.png";
                $imagePath = "assets/images/customer/$imageName";

                try {
                    // Create the "public/images" directory if it doesn't exist
                    if (!File::isDirectory(public_path("assets/images/customer"))) {
                        File::makeDirectory((public_path("assets/images/customer")), 0777, true, true);
                    }

                    // Save the image to the specified path
                    $image->move(public_path('assets/images/customer'), $imageName);
                    $credentials["image"] = $imagePath;
                } catch (\Exception $e) {
                    // throw $e;
                    // skip if not uploaded
                }
            }


            // Update the customer data
            $customer->update($credentials);

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "Profile updated successfully.",
            ],  200); // 200
        } catch (\Exception $e) {
//            throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                // 'err' => $e->getMessage(),
            ],  403); // 200
        }
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse{
        try {
            $user = $request->user('customers');
            $password = $user->getAuthPassword();

            if (Hash::check($request->input('old-password'), $password)){
                $user->password = Hash::make($request->input('password'));
                $user->save();

                return response()->json([
                    'success'   => true,
                    'status'    => 202,
                    'message'   => "Customer password update successfully.",
                ],  202);

            }

            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Old password doesn't match.",
            ],  403);

        }catch (\Exception $e){
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  403); // 202

        }

    }

//    public function sendOTP(Request $request)
//    {
//        // Retrieve the phone number from the request
//        $phoneNumber = $request->input('phone_number');
//
//        // Generate a random 6-digit OTP
//        $otp = rand(100000, 999999);
//
////        // Store the OTP in the session or your preferred storage mechanism, associated with the phone number
////        $request->session()->put($phoneNumber, $otp);
//
//        // Replace 'YOUR_MESSAGING_SERVICE_SID' with your Firebase Cloud Messaging service SID
//        $messagingServiceSid = '427407948665';
//
//        // Send the OTP to the user's phone number using Firebase Cloud Messaging
//        try {
//            // Retrieve the Firebase service account JSON from your Laravel configuration
//            $firebaseServiceAccountJson = config('app.env');
//
//            return $firebaseServiceAccountJson;
//
//            // Parse the JSON string to an array
//            $serviceAccount = json_decode($firebaseServiceAccountJson, true);
//
//            // Create the Firebase factory
//            $firebase = (new Factory)->withServiceAccount($serviceAccount);
//            $messaging = $firebase->createMessaging();
//
//            $message = CloudMessage::withTarget('messaging', $messagingServiceSid)
//                ->withNotification(['title' => 'Your OTP', 'body' => "Your OTP is: $otp"])
//                ->withData(['otp' => $otp]);
//
//            // Send the message
//            $messaging->send($message);
//
//            return response()->json(['message' => 'OTP sent successfully']);
//        } catch (\Exception $e) {
//            // Handle exceptions that may occur during Firebase messaging
//            return response()->json(['error' => 'Failed to send OTP']);
//        }
//    }
}
