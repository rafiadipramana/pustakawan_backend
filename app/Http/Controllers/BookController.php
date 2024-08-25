<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'image_url' => ['required', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
            'published_at' => ['required', 'date'],
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "Kesalahan validasi",
                "data" => $validator->errors()
            ], 422);
        }
        // dd($request->all());
        // Handle file upload
        $image = $request->file('image_url');
        $originalName = $image->getClientOriginalName(); // Get the original file name
        $timestamp = now()->timestamp; // Get the current timestamp

        // Combine timestamp with original name to create a unique file name
        $fileName = "{$timestamp}_{$originalName}";

        // Store the file with the generated name in 'public/images' directory
        $imagePath = $image->storeAs('images', $fileName, 'public');

        // Create and save the new book with the uploaded image URL
        $book = Book::create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'image_url' => $imagePath, // Store the path in the database
            'published_at' => $request->input('published_at'),
        ]);

        return response()->json([
            "message" => "Buku berhasil ditambahkan",
            "data" => $book
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::find($id);

        if(!$book){
            return response()->json([
                "message" => "Buku tidak ditemukan",
                "data" => null
            ], 404);
        }

        return response()->json([
            "message" => "Buku dengan id: $book->id ditemukan",
            "data" => $book
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if(!$book){
            return response()->json([
                "message" => "Buku tidak ditemukan",
                "data" => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'image_url' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
            'published_at' => ['required', 'date'],
        ]);

        if($validator->fails()){
            return response()->json([
                "message" => "Kesalahan validasi",
                "data" => $validator->errors()
            ], 422);
        }
        // Update the book with the provided data
        $book->update([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'published_at' => $request->input('published_at'),
        ]);

        // Handle file upload if provided
        if ($request->hasFile('image_url')) {
            $oldImage = basename($book->image_url);

            // Delete the old image if it exists
            if ($oldImage && Storage::disk('public')->exists('images/'. $oldImage)) {
                Storage::disk('public')->delete('images/' . $oldImage);
            }

            $image = $request->file('image_url');
            $originalName = $image->getClientOriginalName(); // Get the original file name
            $timestamp = now()->timestamp; // Get the current timestamp

            // Combine timestamp with original name to create a unique file name
            $fileName = "{$timestamp}_{$originalName}";

            // Store the file with the generated name in 'public/images' directory
            $imagePath = $image->storeAs('images', $fileName, 'public');

            // Update the book with the new image URL
            $book->image_url = $imagePath;
            $book->save();
        }

        return response()->json([
            "message" => "Buku berhasil diupdate",
            "data" => $book
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        
        if(!$book){
            return response()->json([
                "message" => "Buku tidak ditemukan",
                "data" => null
            ], 404);
        }

        $oldImage = basename($book->image_url);
        // Delete the book
        if ($oldImage && Storage::disk('public')->exists('images/'. $oldImage)) {
            Storage::disk('public')->delete('images/' . $oldImage);
        }

        $book->delete();

        return response()->json([
            "message" => "Buku berhasil dihapus",
        ]);
    }
}
