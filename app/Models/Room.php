<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'categoryId',
        'userId',
        'image',
        'capacity',
        'price'

    ];

    public function category(){
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }
}
