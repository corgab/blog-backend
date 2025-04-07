<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = ['email','name', 'terms_accepted', 'terms_accepted_at', 'consent_ip','privacy_accepted','privacy_accepted_at'];

    protected $casts = [
        'terms_accepted' => 'boolean',
        'privacy_accepted' => 'boolean',
    ];

}
