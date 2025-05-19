<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Setting;
use App\Traits\RequestResponseTrait;
use App\Traits\UploadMediaTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use RequestResponseTrait;
    use UploadMediaTrait;
    
    public function getSettings()
    {
        try {
            $settings = Setting::all()->pluck('value', 'key')->toArray();
            if(!empty($settings['logo'])) {
                    $media = Media::where('id', $settings['logo'])->first();
                    $settings['logo'] = !empty($media) ? $media->thumbnail_path : null;
            }
            $responseMessage = 'Settings loaded successfully.';
            return $this->successJsonResponse($responseMessage,$settings);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function updateSettings(Request $request)
    {
        try {
            foreach ($request->all() as $key => $value) {
                if ($request->hasFile($key)) {
                    $setting = Setting::where('key', $key)->first();
                    $media = Media::find($setting->value);
                    if ($media) {
                        if (Storage::disk('public')->exists($media->path)) {
                            Storage::disk('public')->delete($media->path);
                        }
                        if (Storage::disk('public')->exists($media->thumbnail_path)) {
                            Storage::disk('public')->delete($media->thumbnail_path);
                        }
                        $media->forceDelete();
                    }

                    $uploadedFile = $request->file($key);
                    $media = $this->uploadAndSaveInDatabase($uploadedFile, 'settings', ucfirst($key) . ' Image');

                    Setting::settingUpdateCreate($key,$media->id);
                } else {
                    Setting::settingUpdateCreate($key,$value);
                }
            }
            return $this->successJsonResponse('Settings updated successfully.');
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }
}
