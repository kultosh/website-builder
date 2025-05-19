<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

trait UploadMediaTrait
{
    protected ?ImageManager $imageManager = null;

    protected function getImageManager(): ImageManager
    {
        if (!$this->imageManager) {
            $this->imageManager = new ImageManager(new GdDriver());
        }
        return $this->imageManager;
    }
    
    /**
     * Upload an image with thumbnail support using Image Intervention.
     *
     * @param UploadedFile $uploadedFile
     * @param string $location (folder inside /public)
     * @param string $altText (optional)
     * @return array
     */
    protected function upload(UploadedFile $uploadedFile, string $location, string $altText)
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $mimeType = $uploadedFile->getClientMimeType();

        $filename = uniqid($location . '_') . '.' . $uploadedFile->getClientOriginalExtension();
        $thumbFilename = 'thumb_' . $filename;

        $basePath = "public/{$location}";
        $thumbPath = "{$basePath}/thumbnails";

        // Ensure directories exist
        File::ensureDirectoryExists(storage_path("app/{$basePath}"));
        File::ensureDirectoryExists(storage_path("app/{$thumbPath}"));


        // Read and save original image
        $image = $this->getImageManager()->read($uploadedFile->getPathname());
        $image->save(storage_path("app/{$basePath}/{$filename}"));

        // Create and save thumbnail
        $thumbnail = $image->scale(width: 300, height: 200);
        $thumbnail->save(storage_path("app/{$thumbPath}/{$thumbFilename}"));

        return [
            'name'            => $originalName,
            'mime_type'       => $mimeType,
            'path'            => "{$location}/{$filename}",
            'thumbnail_path'  => "{$location}/thumbnails/{$thumbFilename}",
            'alt_text'        => $altText ?? "{$location}_image",
        ];
    }

    /**
     * Upload and create a media record in the database.
     *
     * @param UploadedFile $file
     * @param string $location
     * @param string $altText
     * @return \App\Models\Media
     */
    protected function uploadAndSaveInDatabase(UploadedFile $file, string $location, $altText = 'Image')
    {
        $details = $this->upload($file, $location, $altText);
        return Media::create($details);
    }
}
