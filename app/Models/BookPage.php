<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookPage extends Model
{
    //
    use HasFactory;
    protected $fillable = ['name', 'slug', 'categories', 'description', 'file_path', 'file_path_pdf', 'status'];

}
