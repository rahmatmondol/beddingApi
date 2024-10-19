<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReviewRequest;
use App\Models\Provider;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
    public function addReview(StoreReviewRequest $request)
    {
        // Validate and save the review

        try {


            $providerId = $request->input('provider_id');
            $customerId = $request->user('customers')->id;

            $review = new Review();
            $review->review_rating = $request->input('review_rating');
            $review->review_comment = $request->input('review_comment');
            $review->service_id = $request->input('service_id ');
            $review->provider_id = $providerId;
            $review->customer_id = $customerId; // Assuming you're using authentication

            $review->save();

            // Retrieve the associated provider by its ID
            $provider = Provider::findOrFail($providerId);

            // Update the individual star counts
            switch ($review->review_rating) {
                case 1:
                    $provider->one_star++;
                    break;
                case 2:
                    $provider->two_star++;
                    break;
                case 3:
                    $provider->three_star++;
                    break;
                case 4:
                    $provider->four_star++;
                    break;
                case 5:
                    $provider->five_star++;
                    break;
                default:
                    // Handle invalid ratings if needed
                    break;
            }

            // // Update the total service count
            // $provider->Total_service_count++;

            // Calculate the new average rating
            $totalRating = ($provider->one_star * 1 + $provider->two_star * 2 + $provider->three_star * 3 + $provider->four_star * 4 + $provider->five_star * 5);
            $reviewCount = $provider->Total_service_count;
            $averageRating = ($reviewCount > 0) ? round($totalRating / $reviewCount, 1) : 0.0;

            // Update the average rating
            $provider->avg_rating = $averageRating;

            // Save the provider model
            $provider->save();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Review added successfully'

            ],200);

            // return redirect()->back()->with('success', 'Review submitted successfully.');

        }catch (\Exception $e) {

            return response()->json([
                'success'   => false,
                'status'    => 403,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  403); // 200
        }
    }
}
