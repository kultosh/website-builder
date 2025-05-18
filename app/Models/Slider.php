<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'media_id',
        'title',
        'caption',
        'url',
        'order',
        'status',
    ];

    public function media()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }
}
