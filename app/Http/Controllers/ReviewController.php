<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ResponseHelper::formatResponse(200,"Succes",Review::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        return ResponseHelper::formatResponse(201,"Succes",Review::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return ResponseHelper::formatResponse(200,"Succes",$review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        return ResponseHelper::formatResponse(200,"Succes", $review->update($request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        return ResponseHelper::formatResponse(200,"Succes",$review->delete());
    }
}
