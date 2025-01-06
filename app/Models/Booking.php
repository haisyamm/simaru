<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'roomId',
        'userId',
        'startDate',
        'endDate',
        'bookingDate',
    ];

    public function room(){
        return $this->belongsTo(Room::class, 'roomId', 'id');
    }

    public function by(){
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
