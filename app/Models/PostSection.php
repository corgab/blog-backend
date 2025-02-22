<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PostSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['post_id','title', 'content', 'order', 'image_path', 'image_alt'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'section_id');
    }
}
