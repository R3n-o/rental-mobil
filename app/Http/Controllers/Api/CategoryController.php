<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'data'    => $categories
        ], 200);
    }

    public function store(Request $request)
    {
       
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data'    => $category
        ], 201);
    }


    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $category = Category::find($id);
        if (!$category) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        $category->update(['name' => $request->name ?? $category->name]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diupdate',
            'data'    => $category
        ], 200);
    }

  
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $category = Category::find($id);
        if (!$category) return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ], 200);
    }
}