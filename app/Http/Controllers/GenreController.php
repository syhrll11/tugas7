<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    // Read all genres
    public function index()
    {
        $genres = Genre::all();

        return response()->json([
            'success' => true,
            'message' => 'Data genre berhasil ditampilkan',
            'data' => $genres
        ], 200);
    }

    // Create genre
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $genre = Genre::create($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Genre berhasil ditambahkan',
            'data' => $genre
        ], 201);
    }

    // Menampilkan satu genre berdasarkan ID
public function show($id)
{
    $genre = Genre::with('books')->find($id);

    if (!$genre) {
        return response()->json([
            'success' => false,
            'message' => 'Genre tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Detail Genre',
        'data' => $genre
    ], 200);
}

// Update genre
public function update(Request $request, $id)
{
    $genre = Genre::find($id);

    if (!$genre) {
        return response()->json([
            'success' => false,
            'message' => 'Genre tidak ditemukan'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    $genre->update($request->only(['name', 'description']));

    return response()->json([
        'success' => true,
        'message' => 'Genre berhasil diperbarui',
        'data' => $genre
    ], 200);
}

// Menghapus genre
public function destroy($id)
{
    $genre = Genre::find($id);

    if (!$genre) {
        return response()->json([
            'success' => false,
            'message' => 'Genre tidak ditemukan'
        ], 404);
    }

    $genre->delete();

    return response()->json([
        'success' => true,
        'message' => 'Genre berhasil dihapus'
    ], 200);
}


public function __construct()
{
    $this->middleware('auth:sanctum'); // Jika pakai Sanctum

    // Role-based access
    $this->middleware('role:admin')->only(['index', 'destroy']);
    $this->middleware('role:customer')->only(['store', 'update', 'show']);
}



}

