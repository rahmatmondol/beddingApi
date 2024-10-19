<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    //
    public function getZone(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => "Validation failed.",
                'errors' => $validator->errors()
            ], 422);
        }

        $lat = $request->lat;
        $lng = $request->lng;

        try {
            // Create a point for the given lat and lng
            $point = "POINT($lng $lat)"; // Note that we switch the order of lat and lng

            // Find all zones that contain the given point
            $zones = DB::table('zones')
                ->whereRaw("ST_CONTAINS(coordinates, ST_GeomFromText('$point'))")
                ->get(['id', 'name']);

            if ($zones->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'status' => 404, // 404 for no zones found
                    'message' => "No zones available in this area.",
                ], 404);
            }

            // Return the matching zones as a JSON response
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => "Successfully Authorized.",
                'data' =>[
                    'zone' =>$zones
                ] ,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500, // 500 for internal server error
                'message' => "Zone Unavailable.",
                'errors' => [
                    [
                        'code' => 'coordinates',
                        'message' => 'Service is not available in this area.'
                    ]
                ]
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function zones(Request $request) : JsonResponse
    {

        try {

            $zones = Zone::select("id", "name")->get();

            if ($zones->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'status' => 404, // 404 for no zones found
                    'message' => "No zones available in this area.",
                ], 404);
            }

            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => "Successfully Authorized.",
                'data'      => [
                    'zones' => $zones
                ]
            ],  200); // 200
        } catch (\Exception $e) {
            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => "Zone Unavailable.",
                'errors' => [
                    [
                        'code' => 'coordinates',
                        'message' => 'Service is not available in this area.'
                    ]
                ]
            ],  200); // 200
        }
    }
}
