<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSections extends Model
{
    protected $fillable = ['media_id', 'layout_type', 'description'];

    public function page() {
        return $this->belongsTo(Page::class);
    }
}
