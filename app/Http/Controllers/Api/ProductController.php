<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);
        }
        $product = new Product();
        $product->item_id = $request->item_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        if ($request->hasFile('image')) {

            $image = $request->file('image');


            $filename = uniqid() . '.' . $image->getClientOriginalExtension();


            $path = Helper::fileUpload($image, 'Product', $filename);

            $product->image = $path;
        }

        $product->save();
        return response()->json([
            'message' => 'Product saved successfully',
            'data' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->item_id = $request->item_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;

        if ($request->hasFile('image')) {
            // Get the uploaded file
            $image = $request->file('image');

            // Generate a unique filename for the image
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Delete the old image file if it exists
            if (!empty($product->image)) {
                @unlink(public_path($product->image));
            }

            // Use the fileUpload helper function to upload the new image
            $path = Helper::fileUpload($image, 'Product', $filename);


            $product->image = $path;
        } else {

            $product->image = null;
        }

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    public function show($id)
    {
        $item = Product::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json(['message' => 'Item Show Successfully', 'data' => $item]);
    }

    public function getProductData()
    {
        $item = Product::all();

        return response()->json(['message' => 'All Product Data', 'data' => $item]);
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product Deleted Successfully']);
    }
}
