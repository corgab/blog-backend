<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'difficulty',
        'featured',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags() 
    {
        return $this->belongsToMany(Tag::class);
    }

    public function sections()
    {
        return $this->hasMany(PostSection::class)->orderBy('order');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // Tempo di lettura
    public function getReadingTimeAttribute()
    {
        // Calcola il numero di parole nel contenuto del post
        $wordCount = str_word_count(strip_tags($this->content));
        
        // Considera una velocità di lettura di 200 parole al minuto
        $wordsPerMinute = 220;

        // Calcola il tempo di lettura
        $minutes = ceil($wordCount / $wordsPerMinute);

        if($minutes <= 1) {
            return 'Less than 1 minute';    
        } else
            return "{$minutes} minutes";
    }

    protected $appends = ['reading_time'];

}
