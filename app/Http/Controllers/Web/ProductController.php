<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Item;
use App\Helper\Helper;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with('item')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('item_name', function ($data) {
                    return $data->item->name;
                })
                ->addColumn('image', function ($data) {
                    $url = asset($data->image);
                    return '<img src="' . $url . '" alt="image" width="50px" height="50px">';
                })
                ->addColumn('status', function ($data) {
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('product.edit', ['id' => $data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                              <i class="bi bi-trash"></i>
                            </a>
                            </div>';
                })
                ->rawColumns(['item_name', 'description', 'image', 'action'])
                ->make();
        }
        return view('layouts.product.index');
    }

    public function create()
    {
        $items = Item::all();
        return view('layouts.product.create', compact('items'));
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
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

            return redirect()->route('product.index')->with('t-success', 'Product created successfuly');
        } catch (Exception) {
            return redirect()->route('product.index')->with('t-error', 'Product failed to create');
        }
    }

    public function edit($id)
    {
        $items = Item::all();
        $product = Product::find($id);

        return view('layouts.product.edit', compact('product', 'items'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            $product = Product::findOrFail($id);
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

            return redirect()->route('product.index')->with('t-success', 'Product updated successfuly');
        } catch (Exception) {
            return redirect()->route('product.index')->with('t-error', 'Product failed to update');
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if (isset($product->image) && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
            ]);
        } catch (Exception) {
            return response()->json([
                'error' => true,
                'message' => 'Product failed to deleted',
            ]);
        }
    }
}
