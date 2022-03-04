<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'e_id', 'title', 'description', 'poster', 'banner', 'release_date', 'vote', 'vote_count'];

    protected $appends = ['poster_path', 'banner_path'];

    // attr
    public function getPosterPathAttribute()
    {
        return 'https://image.tmdb.org/t/p/w500' . $this->poster;
    }

    public function getBannerPathAttribute()
    {
        return 'https://image.tmdb.org/t/p/w500' . $this->banner;
    }

    //rel
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_movie');
    }

    // scope
    public function scopeWhenGenreId($query, $genreId)
    {
        return $query->when($genreId, function ($q) use ($genreId) {
            return $q->whereHas('genres', function ($qu) use ($genreId) {
                return $qu->where('genres.id', $genreId);
            });
        });
    }
}
