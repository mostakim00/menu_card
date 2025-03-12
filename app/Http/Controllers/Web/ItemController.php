<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Item;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = asset($data->image);
                    return '<img src="' . $url . '" alt="image" width="50px" height="50px">';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('item.edit', ['id' => $data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                              <i class="bi bi-trash"></i>
                            </a>
                            </div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('layouts.category.index');
    }


    public function create()
    {
        return view('layouts.category.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $item = new Item();
            $item->name = $request->name;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = Helper::fileUpload($image, 'Product', $filename);
                $item->image = $path;
            }

            $item->save();

            return redirect()->route('item.index')->with('t-success', 'Category created successfuly');
        } catch (Exception) {
            return redirect()->route('item.index')->with('t-error', 'Category failed to created');
        }
    }

    public function edit($id)
    {

        $item = Item::find($id);

        return view('layouts.category.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $item = Item::findOrFail($id);
            $item->name = $request->name;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = Helper::fileUpload($image, 'Product', $filename);
                $item->image = $path;
            }

            $item->save();

            return redirect()->route('item.index')->with('t-success', 'Category updated successfuly');
        } catch (Exception) {
            return redirect()->route('item.index')->with('t-error', 'Category failed to updated');
        }
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);

            if (isset($item->image) && File::exists(public_path($item->image))) {
                File::delete(public_path($item->image));
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ]);
        } catch (Exception) {
            return response()->json([
                'error' => true,
                'message' => 'Category failed to deleted',
            ]);
        }
    }
}
