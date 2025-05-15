<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = ['parent_id','title', 'slug', 'meta_title', 'meta_description', 'page_type', 'order', 'is_parent', 'is_menu', 'add_to_home', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            $slug = Str::slug($page->title);
            $originalSlug = $slug;
            $counter = 1;

            while (Page::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $page->slug = $slug;
        });
    }

    public function sections() {
        return $this->hasMany(PageSections::class);
    }
}
