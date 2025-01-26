<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $array = [];
        $products = Product::with('reviews')->get();
        $products->each(function ($product) {
            $product->average_rating = $product->reviews->avg('rating');
        });

        // get the best product based on the average rating
        $best_product = $products->sortByDesc('average_rating')->first();
        $best_product->average_rating = $best_product->reviews->avg('rating');

        // get the worst product based on the average rating
        $worst_product = $products->sortBy('average_rating')->first();
        $worst_product->average_rating = $worst_product->reviews->avg('rating');
        // create the response array
        $array['best_product'] = $best_product;
        $array['worst_product'] = $worst_product;
        $array['products'] = $products;

        return ResponseHelper::formatResponse(200,"Succes",$array);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return ResponseHelper::formatResponse(201,"Succes",$product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->average_rating = $product->reviews->avg('rating');
        return ResponseHelper::formatResponse(200,"Succes",$product);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->fill($request->validated());
        if ($product->isDirty()) {
            $product->save();
        }
        return ResponseHelper::formatResponse(200,"Succes",$product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return ResponseHelper::formatResponse(200,"Succes",null);
    }

    



}
