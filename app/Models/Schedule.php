<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'notes',
        'request_id',
        'user_id',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
