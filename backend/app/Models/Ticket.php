<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'SessionMovies_id', 'quantity', 'date'];

    public function session()
    {
        return $this->belongsTo(SessionMovies::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

