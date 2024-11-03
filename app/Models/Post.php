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
        'description',
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
    
    // Funzione per calcolare il tempo di lettura
    public function getReadingTimeAttribute()
    {
        // Inizializza il conteggio delle parole
        $wordCount = 0;

        // Recupera le sezioni del post corrente, ordinate per 'order'
        $sections = PostSection::where('post_id', $this->id)
            ->orderBy('order')
            ->get();

        // Calcola il numero di parole per ciascuna sezione
        foreach ($sections as $section) {
            $wordCount += str_word_count(strip_tags($section->content));
        }

        // Considera una velocità di lettura di 220 parole al minuto
        $wordsPerMinute = 220;

        // Calcola il tempo di lettura
        $minutes = ceil($wordCount / $wordsPerMinute);

        if ($minutes <= 1) {
            return 'Meno di un minuto';
        } else {
            return "{$minutes} minuti";
        }
    }

}
