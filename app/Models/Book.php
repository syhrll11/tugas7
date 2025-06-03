<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use App\Models\Genre;

class Book extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'cover_photo',
        'genre_id',
        'author_id',
    ];

    // Relasi: Satu buku dimiliki oleh satu penulis
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // Relasi: Satu buku memiliki satu genre
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
