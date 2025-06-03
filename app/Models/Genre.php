<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Genre extends Model
{
    protected $fillable = ['name', 'description'];

    // Relasi: Satu genre memiliki banyak buku
    public function books()
    {
        return $this->hasMany(Book::class);
        
    }
}
