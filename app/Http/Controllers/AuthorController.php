<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    // Menampilkan semua author
    public function index()
    {
        $authors = Author::all();

        return response()->json([
            "success" => true,
            "message" => "Data Author berhasil ditampilkan",
            "data" => $authors
        ], 200);
    }

    // Menambahkan author baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $author = Author::create($request->only(['name', 'bio']));

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil ditambahkan',
            'data' => $author
        ], 201);
    }

    // Menampilkan satu author berdasarkan ID
public function show($id)
{
    $author = Author::find($id);

    if (!$author) {
        return response()->json([
            'success' => false,
            'message' => 'Author tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Detail Author',
        'data' => $author
    ], 200);
}

// Update author
public function update(Request $request, $id)
{
    $author = Author::find($id);

    if (!$author) {
        return response()->json([
            'success' => false,
            'message' => 'Author tidak ditemukan'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    $author->update($request->only(['name', 'bio']));

    return response()->json([
        'success' => true,
        'message' => 'Author berhasil diperbarui',
        'data' => $author
    ], 200);
}

// Menghapus author
public function destroy($id)
{
    $author = Author::find($id);

    if (!$author) {
        return response()->json([
            'success' => false,
            'message' => 'Author tidak ditemukan'
        ], 404);
    }

    $author->delete();

    return response()->json([
        'success' => true,
        'message' => 'Author berhasil dihapus'
    ], 200);
}

public function __construct()
{
    // Semua method butuh autentikasi
    $this->middleware('auth:sanctum');

    // Hanya admin yang bisa melihat semua dan menghapus
    $this->middleware('role:admin')->only(['index', 'destroy']);

    // Hanya customer yang bisa create, update, dan lihat detail
    $this->middleware('role:customer')->only(['store', 'update', 'show']);
}


}
