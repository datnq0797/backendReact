<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'status' => 200,
            'data' => $brand,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->status = $request->status;
        $brand->save();

        return response()->json([
            'status' => 200,
            'message' => 'Brand created successfully',
            'data' => $brand,
        ], 200); // 200 HTTP Status Code
    }

    public function show($id)
    {
        $brand = Brand::find($id);

        if (! $brand) {
            return response()->json([
                'status' => 404,
                'message' => 'Brand not found',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $brand,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (! $brand) {
            return response()->json([
                'status' => 404,
                'message' => 'Brand not found',
                'data' => [],
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }

        $brand->name = $request->name;
        $brand->status = $request->status;
        $brand->save();

        return response()->json([
            'status' => 200,
            'message' => 'Brand updated successfully',
            'data' => $brand,
        ], 200); // 200 HTTP Status Code
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (! $brand) {
            return response()->json([
                'status' => 404,
                'message' => 'Brand not found',
                'data' => [],
            ], 404);
        }

        $brand->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Brand deleted successfully',
            'data' => [],
        ], 200);
    }
}
