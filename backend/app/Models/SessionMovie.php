<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionMovie extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'date', 'time', 'seats'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
