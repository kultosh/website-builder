<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'medias';
    
    protected $fillable = ['name', 'mime_type', 'path', 'thumbnail_path', 'alt_text'];

    public static function deleteMedia($entity)
    {
        Storage::disk('public')->delete($entity->media->path);
        Storage::disk('public')->delete($entity->media->thumbnail_path);
        $entity->media->forceDelete();
    }
}
