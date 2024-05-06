<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    use HasFactory;
    protected $guarded = ['_token'];
    public $timestamps = false;
    protected $table = 'bookings_seats';
}
