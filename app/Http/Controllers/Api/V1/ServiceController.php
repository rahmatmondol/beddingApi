<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class ServiceController extends Controller
{
    //
    public function index($id){

        try {


            $data = Service::with('customer','bettings.provider','images')->find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => "This Service not available for your.",
                ], 404);
            }

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "customer successfully authorized.",
                'data'   => [
                    'data' => $data
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

    public function createService(Request $request)
    {

//        dd($request->all());
        $customerID = $request->user('customers')->id;

        // Validation rules for creating a service
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'lat' => 'required',
            'lng' => 'required',
            'price' => 'required|integer',
            'price_type' => 'required|in:fixed,negotiable',
            'level' => 'required|in:entry,intermediate,expert',
            'images' => 'required|array|max:5', // Adjust the 'max' value as needed
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Per-image validation rules
            'currency' => 'string',
            'status' => 'boolean',
            'skill' => 'required|array',
            'description' => 'required|string',
            'address' => 'required|string',
            'location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => "Validation failed.",
                'errors' => $validator->errors()
            ], 422);
        }

        if (Service::where('name', $request->input('name'))
            ->where('category_id', $request->input('category_id'))
            ->where('customer_id', $customerID)->first()) {
            return response()->json([
                'success'=> false,
                'status'=> 422,
                'message'=> 'This service already exists'
            ], 422);
        }

        $commotion = number_format($request->input('price') * 0.10, 2, '.', '');
        $provider_amount = number_format($request->input('price') - $commotion, 2, '.', '');


//        $commotion = $request->input('price') * 0.10;
//        $provider_amount = $request->input('price') - $commotion;

        $lat = $request->lat;
        $lng = $request->lng;

        // Create a point for the given lat and lng
        $point = "POINT($lng $lat)";

        // Find all zones that contain the given point
        $zones = DB::table('zones')
            ->whereRaw("ST_CONTAINS(coordinates, ST_GeomFromText('$point'))")
            ->get(['id', 'name']);

        if ($zones->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => "No zones available in this area.",
                'data' => [
                    'message' => "No zones available in this area.",

                ]
            ], 404);
        }

        DB::beginTransaction(); // Begin a database transaction

        try {
            $service = new Service([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'customer_id' => $customerID,
                'zone' => $zones,
                'price' => $request->input('price'),
                'price_type' => $request->input('price_type'),
                'level' => $request->input('level'),
                'currency' => $request->has('currency') ? $request->input('currency') : 'usd',
                'commotion' => $commotion,
                'provider_amount' => $provider_amount,
                'status' => "request",
                'skill' => $request->input('skill'),
                'description' => $request->input('description'),
                'address' => $request->input('address'),
                'location' => $lat.','.$lng,
            ]);

            $service->save();



            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $addImage;
                foreach ($images as $image) {
                    $postImage = "service_" . $service->id . "_" . uniqid('', true) . ".png";
                    $destinationPath = public_path('assets/images/service/');
                    $fullPath = $destinationPath . $postImage;

                    Image::make($image)->resize(200, 200)->save($fullPath);

                    $imageModel = new ServiceImage(); // Use the updated ServiceImage model
                    $imageModel->service_id = $service->id;
                    $imageModel->path = 'assets/images/service/' . $postImage;
                    $imageModel->save();// Save the image to the database
                    $addImage[]=$imageModel;
                }
            }

            $service['image'] = $addImage;


            DB::commit(); // Commit the transaction if everything is successful

            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => "Service created successfully.",
                'data' => [
                    'service' => $service
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction if there's an error

            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => "An error occurred while creating the service.",
            ], 500);
        }
    }

    public function update(Request $request, $id){
        $service = Service::with('images')->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => "Service not found.",
            ], 404);
        }

        // Define validation rules based on the fields that may be updated
        $validationRules = [
            'name' => 'string',

            'customer_id' => 'integer',

            'price' => 'string',
            'price_type' => 'in:USD,UAED',
            'status' => 'boolean',
            'skill' => 'nullable|string',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'images' => 'array|max:5', // Adjust the 'max' value as needed
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Per-image validation rules

//            'location' => 'nullable|string',
        ];

        // Filter the request data to include only the fields that are present in the request
        $requestData = $request->only(array_keys($validationRules));

        if($requestData['price']){

            $commotion = number_format($request->input('price') * 0.10, 2, '.', '');
            $provider_amount = number_format($request->input('price') - $commotion, 2, '.', '');
            $requestData['commotion']=$commotion;
            $requestData['provider_amount']=$provider_amount;
        }


        // Create a new validator based on the filtered request data and validation rules
        $validator = Validator::make($requestData, $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => "Validation failed.",
                'errors' => $validator->errors()
            ], 422);
        }

        // Update the service with the provided fields
        $service->fill($requestData);
        $service->save();
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $addImage;
            foreach ($images as $image) {
                $postImage = "service_" . $service->id . "_" . uniqid('', true) . ".png";
                $destinationPath = public_path('assets/images/service/');
                $fullPath = $destinationPath . $postImage;

                Image::make($image)->resize(200, 200)->save($fullPath);

                $imageModel = new ServiceImage(); // Use the updated ServiceImage model
                $imageModel->service_id = $service->id;
                $imageModel->path = 'assets/images/service/' . $postImage;
                $imageModel->save();// Save the image to the database
                $addImage[]=$imageModel;
            }
            $service['image'] = $addImage;
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => "Service updated successfully.",
            'data' =>[
                'service' => $service
            ] ,
        ], 200);
    }

    public function delete($id){
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => "Service not found.",
            ], 404);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => "Service deleted successfully.",
        ], 200);
    }

    //service search
    public function searchServices(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'keyword' => 'required|string', // Keyword search
            ]);


            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400); // Bad Request
            }

            // Get the keyword from the request
            $keyword = $request->input('keyword');

            // Start building the query
            $query = Service::query();

            // Apply keyword search
            $query->with('customer','bettings.provider','images')->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
            })->where('status','request');

            // Fetch the results without sorting or pagination
            $results = $query->get();
            if($results->isEmpty()){
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => "Service not found.",
                ], 404);
            }

            // Return the fetched results as a JSON response
            return response()->json([
                'success' => true,
                'message' => 'Services fetched successfully.',
                'data' => [
                    'services' => $results
                ] ,
                'status' => 200,

            ]);
        }catch (\Exception $e){
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],);
        }
        // Define validation rules for the keyword

    }

    public function imageDelete($id) {
        try {
            // Find the ServiceImage record with the given $id
            $service = ServiceImage::find($id);

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => "Service Image not found.",
                ], 404);
            }

            if (!$service->image){
                $position = strpos($service->image, '/assets');

                if ($position !== false) {
                    // Extract the part of the path after "/assets"
                    $imagePath = substr($service->image, $position);

                    // Construct the full path to the image file
                    $fullImagePath = public_path($imagePath);


                    // Check if the image file exists
                    if (file_exists($fullImagePath)) {
                        // Delete the image file
                        unlink($fullImagePath);
                    }
                }
            }




            // Delete the image file associated with the record (if needed)
            // Assuming the 'path' column in the 'service_images' table contains the file path
            // You can use Laravel's Filesystem for this.
            // Example: Storage::delete($serviceImage->path);

            // Delete the ServiceImage record from the database
            $service->delete();

            // You can return a success response or perform other actions.
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => "Service Image deleted successfully.",
            ], 200);

        } catch (\Exception $e){
            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],);
        }
    }

    public function get(Request $request){
        try{

            $user = $request->user('customers');



            $data = Service::with('images')->where('customer_id',$user->id)->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => "Service not found.",
                ], 404);
            }

            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "customer successfully authorized.",
                'data'   => [
                    'data' => $data
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




}
