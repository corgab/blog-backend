<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'section_id',
        'path', 
        'is_featured'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    public function postSection()
    {
        return $this->belongsTo(PostSection::class);
    }
}
