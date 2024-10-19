<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    //
    public function addToWishList(Request $request)
    {
        // Get the authenticated user
        $user = $request->user('providers')->id;
        // Validate the request (e.g., product_id)

        $validator = Validator::make($request->all(), [
            // 'serviceId' => 'required|exists:services,id',
            'serviceId' => 'required|exists:services,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400); // Bad Request
        }

        // Check if the product is already in the wishlist
        if (Cart::Where('provider_id',$user)->where('service_id',$request->serviceId)->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in the wishlist.',
            ], 400);
        }
        Cart::create([
            'provider_id'=> $user,
            'service_id' => $request->serviceId,
        ]);
        // Add the product to the wishlist


        return response()->json([
            'success' => true,
            'message' => 'Product added to the wishlist successfully.',
        ]);
    }

    /**
     * Remove a product from the user's wishlist.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromWishList(Request $request, $id)
    {
        // Get the authenticated user
        $user = $request->user('providers')->id;

        // Validate the request (e.g., product_id)
//        $request->validate([
//            'product_id' => 'required|exists:products,id',
//        ]);


        // Check if the product is in the wishlist
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not in the wishlist.',
            ], 400);
        }

//        // Remove the product from the wishlist
//        $user->wishList()->detach($request->product_id);
            $cart->delete();
        return response()->json([
            'success' => true,
            'message' => 'Product removed from the wishlist successfully.',
        ]);
    }

    public function getWishList(Request $request){
        try{

            $user = $request->user('providers')->id;

            $data = Cart::with('service')->whereProviderId($user)->get();

            if($data->isEmpty()){
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => " Your wishlist is eampty.",
                ], 404);
            }

            return response()->json([
                "success"=> true,
                "status"=> 200,
                'data'      => [
                    'wishlist' => $data
                ]
            ], 200);

        }catch (\Exception $e){
            return response()->json([
                'success'=> false,
                'message'=> $e->getMessage(),
            ],500);
        }
    }
}
