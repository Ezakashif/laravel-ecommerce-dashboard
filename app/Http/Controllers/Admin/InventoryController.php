<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inventory = new Inventory;
        $inventory->categories_id =$request->input('categories_id');
        $inventory->tittle =$request->input('tittle');
        $inventory->quantity =$request->input('quantity');
        $inventory->price_per_item =$request->input('price_per_item');
        $inventory->total_price =$request->input('total_price');
        $inventory->stock =$request->input('stock');
        $inventory-> save();

        return $inventory;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventory = Inventory::find($id);
        return $inventory;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $inventory = Inventory::find($id);
        $inventory->categories_id =$request->input('categories_id');
        $inventory->tittle =$request->input('tittle');
        $inventory->quantity =$request->input('quantity');
        $inventory->price_per_item =$request->input('price_per_item');
        $inventory->total_price =$request->input('total_price');
        $inventory->stock =$request->input('stock');
        $inventory-> save();

        return $inventory;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventory = Inventory::find($id);
        $inventory->delete();

        return $inventory;
    }
}
