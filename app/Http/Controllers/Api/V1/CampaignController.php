<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    //
    public function index()
    {
        try {
            $current_date_time = Carbon::now()->toDateTimeString();

            $data = Campaign::where('end', '>', $current_date_time)->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => "Your campaign is empty.",
                ], 404);
            }

            return response()->json([
                "success" => true,
                "status" => 200,
                'data' => [
                    'campaign' => $data
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
