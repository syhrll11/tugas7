<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Menampilkan semua buku beserta penulisnya
    public function index(){
        $books = Book::all();

        if ($books->isEmpty()) {
            return response()->json([
                "succes" => true,
                "message" => "Data not found"
            ], 200);
        }

        return response()->json([
            "succes" => true,
            "message" => "Get all resources",
            "data" => $books
        ]);
    }

    public function store(Request $request) {
        // 1. validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'cover_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'genre_id' => 'required|exists:genres.id',
            'author_id' => 'required|exists:authors.id',
        ]);

        //2. checkk validator error
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => $validator->errors()
            ], 422);
        }

        //3. upload image
        $image = $request->file('cover_photo');
        $image->store('books', 'public');


        //4. insert data
        $book = Book::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'cover_photo' => $images->HashName(),
            'genre_id' => $request->genre_id,
            'author_id' => $request->author_id,
        ]);

        //5. response
        return response()->json([
            'succes' => false,
            'message' => 'resources added successfully',
            'data' => $book
        ], 201);
    }

    public function show(string $id) {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'succes' => false,
                'message' => 'resources not found',
            ], 404);
        }

        return response()->json([
            'succes' => false,
            'message' => 'Get Detail resource',
            'data' => $book
            ], 200);
    }

    public function update(string $id, Request $request) {
        //1. mencari data
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'succes' => false,
                'message' => 'resources not found',
            ], 404);
        }

        // 2. validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'cover_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'genre_id' => 'required|exists:genres.id',
            'author_id' => 'required|exists:authors.id',
        ]);

            //3. checkk validator error
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => $validator->errors()
            ], 422);
        }

        //4. siapkan data yang ingin diupdate
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'cover_photo' => $images->HashName(),
            'genre_id' => $request->genre_id,
            'author_id' => $request->author_id,
        ];

                //5. handle image (upload and delete image)
        if ($request->hasFile('cover_photo')) {
            $image = $request->file('cover_photo');
            $image->store('books', 'public');

            if ($book->cover_photo) {
                Storage::disk('public')->delete('books/' . $book->cover_photo);
            }

            $data['cover_photo'] = $image->hashName();
        }

        //6. update data buku
        $book->update($data);

        //7. response
        return response()->json([
            'succes' => true,
            'message' => 'resources updated successfully',
            'data' => $book
        ], 200);
    }
}
