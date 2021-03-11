<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OrderResource::collection(Order::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order;
        $order->user_id = auth()->id();
        $order->product_id = $request['product_id'];
        $order->quantity = $request['quantity'];
        if ($order->save()) {
            Inventory::where('product_id', $order->product_id)->decrement('quantity', $order->quantity);
            return new OrderResource($order);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new OrderResource(Order::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $previous_qty = $order->quantity;
        $order->user_id = auth()->id();
        $order->product_id = $request['product_id'];
        $order->quantity = $request['quantity'];

        if ($order->save()) {
            Inventory::where('product_id', $order->product_id)->decrement('quantity', $order->quantity);
            Inventory::where('product_id', $order->product_id)->increment('quantity', $previous_qty);
            return new OrderResource($order);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order->delete()) {
            return new OrderResource($order);
        }
    }
}
