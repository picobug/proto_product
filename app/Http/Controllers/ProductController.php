<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lists()
    {
        $products = Product::paginate();

        return \response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Product::count() > 10000) {
            return \response()->json(['message' => 'Product is Full'], 422);
        }
        $validator = $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required',
            'stock' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'buy_price' => 'required|numeric',
        ]);
        $save = Product::create($request->all());

        return \response()->json(['product' => $save, 'status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Product $product
     * @param mixed        $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required',
            'stock' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'buy_price' => 'required|numeric',
        ]);
        $product = Product::where(['id' => $id])->update($request->all());

        return \response()->json(['status' => 'success', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @param mixed        $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find(['id_product' => $id])->first();

        return \response()->json(['status' => $product->delete()]);
    }
}
