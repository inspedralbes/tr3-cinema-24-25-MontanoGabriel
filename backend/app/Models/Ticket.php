<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'email', 'session_movie_id', 'seats','total_price'];

    public function session()
    {
        return $this->belongsTo(SessionMovies::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

