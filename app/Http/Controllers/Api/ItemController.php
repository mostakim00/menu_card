<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);
        }
        $item = new Item();
        $item->name = $request->name;
        if ($request->hasFile('image')) {

            $image = $request->file('image');


            $filename = uniqid() . '.' . $image->getClientOriginalExtension();


            $path = Helper::fileUpload($image, 'Product', $filename);


            $item->image = $path;
        }

        $item->save();
        return response()->json(['message' => 'Item Added Successfully', 'data' => $item]);
    }

    public function getItemData()
    {
        $item = Item::all();

        return response()->json(['message' => 'Item Added Successfully', 'data' => $item]);
    }

    public function show($id)
    {
        $item = Item::with('products')->find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json(['message' => 'Item Show Successfully', 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->name = $request->name;

        if ($request->hasFile('image')) {

            $image = $request->file('image');


            $filename = uniqid() . '.' . $image->getClientOriginalExtension();


            if (!empty($item->image)) {
                @unlink(public_path($item->image));
            }


            $path = Helper::fileUpload($image, 'Product', $filename);


            $item->image = $path;
        } else {

            $item->image = null;
        }

        $item->save();
        return response()->json(['message' => 'Item Updated Successfully', 'data' => $item]);
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->delete();
        return response()->json(['message' => 'Item Deleted Successfully']);
    }
}
