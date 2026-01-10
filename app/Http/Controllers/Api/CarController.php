<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with('category'); 


        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }


        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $cars = $query->get();

        return response()->json([
            'success' => true,
            'data'    => $cars
        ], 200);
    }


    public function show($id)
    {
        $car = Car::with(['category', 'reviews.user'])->find($id);
        
        if (!$car) {
            return response()->json(['message' => 'Mobil tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $car
        ], 200);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string',
            'brand'            => 'required|string',
            'model'            => 'required|string',
            'plate_number'     => 'required|unique:cars',
            'daily_rent_price' => 'required|numeric',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cars', 'public'); 
        }

        $car = Car::create([
            'category_id'      => $request->category_id,
            'name'             => $request->name,
            'brand'            => $request->brand,
            'model'            => $request->model,
            'plate_number'     => $request->plate_number,
            'daily_rent_price' => $request->daily_rent_price,
            'is_available'     => true,
            'image'            => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mobil berhasil ditambahkan',
            'data'    => $car
        ], 201);
    }

    public function update(Request $request, $id)
    {
     
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $car = Car::find($id);
        if (!$car) return response()->json(['message' => 'Mobil tidak ditemukan'], 404);
        
        $dataToUpdate = $request->only([
            'category_id', 
            'name', 
            'brand', 
            'model', 
            'plate_number', 
            'daily_rent_price', 
            'is_available'
        ]);

     
        $car->update($dataToUpdate);

       
        if ($request->hasFile('image')) {
          
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $car->image = $request->file('image')->store('cars', 'public');
            $car->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data mobil diperbarui',
            'data'    => $car
        ], 200);
    }

    
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $car = Car::find($id);
        if (!$car) return response()->json(['message' => 'Mobil tidak ditemukan'], 404);

        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mobil berhasil dihapus'
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

      
        $car = Car::find($id);
        if (!$car) {
            return response()->json(['message' => 'Mobil tidak ditemukan'], 404);
        }

       
        $validator = Validator::make($request->all(), [
            'is_available' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

      
        $car->update(['is_available' => $request->is_available]);

        return response()->json([
            'success' => true,
            'message' => 'Status mobil berhasil diubah',
            'data'    => $car
        ], 200);
    }

}