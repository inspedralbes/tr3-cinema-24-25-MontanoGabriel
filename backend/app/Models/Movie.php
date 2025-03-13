<?php

// app/Models/Movie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    // Relación con SessionMovie
    public function sessions()
    {
        return $this->hasMany(SessionMovie::class);
    }
}

