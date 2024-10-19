<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CateogryController extends Controller
{
    public function index() : JsonResponse
    {
        try {
            $data = Category::where('parent_id','0')->get()->all();

            if (!$data) {
                return response()->json([
                    'success'   => false,
                    'status' =>  404,
                    'message'=> 'Category not found'
                ],404);
            }


            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "Customer successfully authorized.",
                'data'      => [
                    'category' => $data
                ]
            ],  200); // 200
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 404,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  404); // 200
        }
    }

    //sub category function
    public function SubCategory( $category) : JsonResponse
    {
        try {

            $data = Category::where('parent_id',$category)->get();


            if ($data->isEmpty()) {
                return response()->json([
                    'success'   => false,
                    'status' =>  404,
                    'message'=> 'Sub Category not found'
                ],404);
            }


            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => "Customer successfully authorized.",
                'data'      => [
                    'sub-category' => $data
                ]
            ],  200); // 200
        } catch (\Exception $e) {
            //throw $e;
            return response()->json([
                'success'   => false,
                'status'    => 404,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  404); // 200
        }
    }
}
