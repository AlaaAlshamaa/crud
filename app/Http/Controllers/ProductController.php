<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\ProductRequest;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json(
            [
                'status' => 'success',
                'products' => $products,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest  $request)
    {
        try {
            DB::beginTransaction();
            $products = Product::create([
                'name' => $request->name,
                'color' => $request->color,
                'size' => $request->size,
                'price' => $request->price,

            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $newData = [];

        if (isset($request->name)){
            $newData['name'] = $request->name;
        }
        if (isset($request->color)){
            $newData['color'] = $request->color;
        }
        if (isset($request->size)){
            $newData['size'] = $request->size;
        }
        if (isset($request->price)){
            $newData['price'] = $request->price;
        }

        $product->update();

        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }
}
