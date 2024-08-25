<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Book extends Model
{
    use HasFactory;

    // Used to specify the table name in the database (from model name and make it plural)
    protected $table = 'books';

    // Used to specity the columns that can be filled
    protected $fillable = ['title', 'author', 'description', 'price', 'image_url', 'published_at'];

    protected function imageUrl() : Attribute
    {
        return Attribute::make(
            get: fn($image_url) => url('storage/'. $image_url) 
        );
    }
}
