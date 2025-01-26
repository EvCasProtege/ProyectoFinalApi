<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreUserReviewProductRequest;


class UserReviewController extends Controller
{

    //save or update review for a user
    public function store(StoreUserReviewProductRequest $request, Product $product)
    {
        $user = auth()->user();
        if(Review::where('user_id', $user->id)->where('product_id', $product->id)->exists()){
            //update review
            $review = Review::where('user_id', $user->id)->where('product_id', $product->id)->first();
        }else{
            $review = new Review();
            $review->product_id = $product->id;
            $review->user_id = $user->id;
        }        
        $review->content = $request->content;
        $review->rating = $request->rating;
        $review->save();
        return ResponseHelper::formatResponse(201,"Succes",$review);
    }
    

    // get all reviews with product for a authenticated user
    public function index()
    {
        $user = auth()->user();
        $reviews = Review::where('user_id', $user->id)->with('product')->get();
        return ResponseHelper::formatResponse(200,"Succes",$reviews);
    }

    // delete review for a user
    public function destroy(Review $review)
    {
        $user = auth()->user();
        if ($review->user_id == $user->id) {
            $review->delete();
            return ResponseHelper::formatResponse(204,"Succes",null);
        }
        return ResponseHelper::formatResponse(403,"Error",null);
    }

}
