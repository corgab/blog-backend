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
        'image',
        'description',
        'content',
        'featured',
        'user_id',
        'status',
        'meta_description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags() 
    {
        return $this->belongsToMany(Tag::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    
    // Funzione per calcolare il tempo di lettura
    public function getReadingTimeAttribute()
    {
        // Recupera il contenuto del post
        $content = $this->content;
    
        // Rimuove tutti i tag HTML per ottenere solo il testo
        $text = strip_tags($content);
    
        // Conta le parole nel contenuto
        $wordCount = str_word_count($text);
    
        // Considera una velocità di lettura di 220 parole al minuto
        $wordsPerMinute = 220;
    
        // Calcola il tempo di lettura
        $minutes = ceil($wordCount / $wordsPerMinute);
    
        // Restituisce il tempo di lettura formattato
        if ($minutes <= 1) {
            return 'Meno di un minuto';
        } else {
            return "{$minutes} minuti";
        }
    }
    

}
