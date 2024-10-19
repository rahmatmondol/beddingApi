<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetBettingRequest;
use App\Http\Requests\Api\StoreBettingRequest;
use App\Models\Betting;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;

class BettingController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = Betting::paginate($request->per_page);
        return response()->json($data);

    }

    public function store(StoreBettingRequest $request)
    {
        try {
            $user = $request->user('providers')->id;
            $serviceId = $request->input('service_id');

            // Check if a betting entry with the same provider_id and service_id already exists
            $existingBetting = Betting::where('provider_id', $user)->where('service_id', $serviceId)->first();

            if ($existingBetting) {
                return response()->json([
                    'success' => false,
                    'status' => 422,
                    'message' => 'You already have a betting entry for this service',
                ], 422);
            }

            $metaData = Service::where('id', $request->input('service_id'))->first();

            // Create a new betting entry
            $data = Betting::create([
                "provider_id" => $user,
                "service_id" => $serviceId,
                "additional_details" => $request->input("additional_details"),
                "metadata" => $metaData,
                "status" => 'false',
            ]);

            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Successfully added the service',
                'data'      => [
                    'Betting' => $data
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => "Something went wrong. Try again later.",
                'err' => $e->getMessage(),
            ], 403);
        }
    }

    public function bettingList(GetBettingRequest $request){

        try{

            $betting = Betting::with('provider.reviews')->where('service_id',$request->service_id)->get();

            if ($betting->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'status' => 404, // 404 for no zones found
                    'message' => "No betting is available.",
                ], 404);
            }


            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Successfully added the service',
                'data' => [
                    'Betting'=> $betting
                ],
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => "Something went wrong. Try again later.",
                'err' => $e->getMessage(),
            ], 403);
        }



    }

    public function accept(Request $request, Betting $betting)
    {
        try{

            $metadata = json_decode($betting->metadata);

            $service = Service::whereId($betting->service_id)->get();
            $data['service'] = $service;
            $data['customer'] = Customer::whereId(auth('customers')->id())->get();
            $provider = Provider::whereId($betting->provider_id)->firstOrFail();
            $data['provider'] = $provider;
            $data['betting'] = $betting;

            $existingBooking = Booking::where('customer_id', auth('customers')->id())
                ->where('service_id',$betting->service_id)
//            ->where('is_paid', 1) // Assuming 'is_paid' is set to 1 for accepted bookings
                ->first();

            if ($existingBooking) {
                // If an accepted booking already exists for the customer, return an error response
                return response()->json([
                    'success' => false,
                    'status' => 400, // You can choose an appropriate HTTP status code
                    'message' => 'You have already accepted another provider.'
                ], 400);
            }


            $service->status = 'accept';
            $provider->save();

            $provider->Total_service_count = $provider->Total_service_count++;
            $provider->save();

            $booking = new  Booking([
                'provider_id' => $betting->provider_id,
                'customer_id' => auth('customers')->id(),
                'category_id' => $metadata->category_id, // Use -> notation
                'service_id' => $betting->service_id,
                'payment_method' => 'online',
                'is_paid' => 0,
                'metadata' => json_encode($data), // Encode the $data array back to JSON
                'total_amount' => $metadata->price, // Use -> notation
                'service_charge'=> $metadata->commotion, // Use -> notation
                'provider_service_charge'=> $metadata->provider_amount, // Use -> notation
            ]);

            $booking->save();

            $betting->status = 1;
            $betting->save();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Betting accepted successfully'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => "Something went wrong. Try again later.",
                'err' => $e->getMessage(),
            ], 403);
        }

    }
}
