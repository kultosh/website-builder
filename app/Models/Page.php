<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use SoftDeletes;
    
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

    public function parent()
    {
        return $this->hasOne(Page::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    // Scope for active pages
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope for parent pages
    public function scopeParentPage($query)
    {
        return $query->where('is_parent', 1);
    }

    // Scope for child pages
    public function scopeChild($query)
    {
        return $query->where('is_parent', 0)->whereNotNull('parent_id');
    }

    // Scope for menu pages
    public function scopeMenu($query)
    {
        return $query->where('is_menu', 1);
    }
}
