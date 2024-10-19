<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\ProviderLoginRequest;
use App\Http\Requests\Api\StoreProviderRequest;
use App\Http\Requests\Api\UpdateProviderRequest;
use App\Http\Resources\Api\ProviderResource;
use App\Models\Provider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProviderAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function provider(Request $request)
    {
        $provider = $request->user("providers");
        try {
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "Provider successfully authorized.",
                'data'   => [
                    "provider" => new ProviderResource($provider),
                    // "services" => $provider->services
                ]
            ],  200); 
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  403); 
        }
    }


    /**
     * Logout provider.
     */
    public function logout(Request $request)
    {
        try {
            if ($request->user('providers')) {
                $request->user('providers')->tokens()->delete();
                return response()->json([
                    'success'   => true,
                    'status'    => 200,
                    'message'   => "Logged out successfully.",
                ],  200); 
            } else {
                return response()->json([
                    'success'   => true,
                    'status'    => 200,
                    'message'   => "You have already logged out.",
                ],  200); 
            }
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  403); 
        }
    }


    /**
     * Crete a newly created providers in database.
     */
    public function login(ProviderLoginRequest $request)
    {
        $loginField = $request->input('login_field');
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

            $provider = Provider::where($credentials)->first();

            if (!$provider || !Hash::check($password, $provider->password)) {
                return response()->json([
                    'success'   => false,
                    'status'    => 401,
                    'message'   => "Unauthenticated provider credentials.",
                    'errors' => 'Invalid credentials.'
                ],  401); 
            }

            if ($provider->phone_verify === 0) {
                return response()->json([
                    'success'   => false,
                    'status'    => 403,
                    'message'   => "Provider phone is not verified.",
                    'errors' => 'Phone is not verified.'
                ],  403); 
            }

            // $provider->tokens()->delete(); // for live need to enable
            $token = $provider->createToken('authToken')->plainTextToken;

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "Provider successfully authorized.",
                'token' => $token
            ],  200); 
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  403); 
        }
    }

    /**
     * Crete a newly created provider in database.
     */
    public function register(StoreProviderRequest $request)
    {
        try {

            $provider = Provider::create([

                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'zone_id' => $request->zone_id,
                'country'=> $request->country,
            ]);

//            event(new RegisteredProvider($provider));
//           // Handle image upload and update
//            if ($request->hasFile('image')) {
//                $image = $request->file('image');
//                $imageName = "provider_$provider->id.png";
//                $imagePath = "assets/images/provider/$imageName";
//
//                try {
//                    // Create the "public/images" directory if it doesn't exist
//                    if (!File::isDirectory(public_path("assets/images/provider"))) {
//                        File::makeDirectory((public_path("assets/images/provider")), 0777, true, true);
//                    }
//
//                    // Save the image to the specified path
//                    $image->move(public_path('assets/images/provider'), $imageName);
//                    $provider->image = $imagePath;
//                    $provider->save();
//                } catch (\Exception $e) {
//                    //throw $e;
//                    // skip if not uploaded
//                }
//            }


            // Send the OTP to the user's phone (You can adjust this based on your SMS service provider and configuration)
            // sendOtpToPhone($request->phone, $otp);'
            return response()->json([
                'success'   => true,
                'status'    => 201,
                'message'   => "Provider registered successfully.",
            ],  201); 
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  403); 
        }
    }

//    this function is user for providor account delete

    public function delete(Request $request)
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
            ],  422); 
        }

        // get customer
        $customer = $request->user('providers');

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
                ],  422); 
            }

            return response()->json([
                'success'   => true,
                'status'    => 202,
                'message'   => "Account deleted successfully.",
            ],  202); 
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                // 'err' => $e->getMessage(),
            ],  403); 
        }
    }

//    THIS FUNCTION IS USE FOR PROFILE UPDATE

    public function update(UpdateProviderRequest $request)
    {

        // get customer
        if(Empty($request->all())){
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message' => 'Nothing to update'
            ],403);
        }
        $customer = $request->user('providers');

        $credentials = Arr::only($request->all(), [
            'name',
            'email',
            'image',
            'bio'
        ]);

        try {
            // Handle image upload and update
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = "customer_$customer->id.png";
                $imagePath = "assets/images/provider/$imageName";

                try {
                    // Create the "public/images" directory if it doesn't exist
                    if (!File::isDirectory(public_path("assets/images/provider"))) {
                        File::makeDirectory((public_path("assets/images/provider")), 0777, true, true);
                    }

                    // Save the image to the specified path
                    $image->move(public_path('assets/images/provider'), $imageName);
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
            ],  200); 
        } catch (\Exception $e) {
//            throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                // 'err' => $e->getMessage(),
            ],  403); 
        }
    }

    public function changePassword(ChangePasswordRequest $request){
        try {
            $user = $request->user('providers');

            $password = $user->password;


            if (Hash::check($request->input('old-password'), $password)){
                $user->password = Hash::make($request->input('password'));
                $user->save();

                return response()->json([
                    'success'   => true,
                    'status'    => 200,
                    'message'   => "Provider password update successfully.",
                ],  200);

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
            ],  403); 

        }
//        $user = $request->user('customers')->getAuthPassword();

    }
}
