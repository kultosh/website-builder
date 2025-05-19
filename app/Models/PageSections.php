<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSections extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['media_id', 'layout_type', 'description', 'order'];

    public function page() {
        return $this->belongsTo(Page::class);
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }

}
