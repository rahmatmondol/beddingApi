<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function  getService(Request $request)
    {
        try {
            $zoneId = $request->user('providers')->zone_id;

            if ($request->has('category_id')){

                try {
                    $categoryId =  $request->category_id;
                    $services = Service::with('customer','images')->whereJsonContains('zone', ['id' => $zoneId])->where('category_id', $categoryId)->where('status','request')->get();
                    if ($services->isEmpty()) {
                        return response()->json([
                            'success' => false,
                            'status' =>404,
                            'message' => "This category service is not available for your zone.",
                        ],404);
                    }
                    return response()->json([
                        'success' => true,
                        'status' => 200,
                        'message' => "Customer successfully authorized.",
                        'data' => [
                            'service' => $services
                        ]
                    ], 200); // 200
                }catch (\Exception $e){
                    return response()->json([
                        'success' => false,
                        'status' => 403,
                        'message' => "Something went wrong. Try after sometimes.",
                        'err' => $e->getMessage(),
                    ], 403); // 200
                }
            }

            $services = Service::with('customer','images')->whereJsonContains('zone', ['id' => $zoneId])->where('status','request')->get();

            if ($services->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'status' =>404,
                    'message' => "No Service is available for your zone.",
                ],404);
            }
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => "Customer successfully authorized.",
                'data' => [
                    'service' => $services
                ]
            ], 200); // 200
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ], 403); // 200
        }
    }

    public function getProviderDetails($id){

        try{
            $data = Provider::With('reviews')->where('id', $id)->get();
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'status' =>404,
                    'message' => "Provider not found.",
                ],404);
            }
            return response()->json([
                "success"=> true,
                "status"=> 200,
                "message"=> "Successfully authorized",
                'data'=> [
                    'provider' => $data
                ]
            ], 200);

        }catch (\Exception $e){

            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ], 403);
        }
    }
}
