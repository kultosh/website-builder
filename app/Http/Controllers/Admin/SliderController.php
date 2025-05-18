<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Slider;
use App\Traits\RequestResponseTrait;
use App\Traits\UploadMediaTrait;
use Exception;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use RequestResponseTrait;
    use UploadMediaTrait;
    
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 5);
            $sliderList = Slider::with('media')->orderBy('created_at', 'desc')->paginate($perPage);
            $responseMessage = 'Slider loaded successfully.';
            return $this->successJsonResponse($responseMessage,$sliderList);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function store(Request $request)
    {
        try {
            $mediaId = null;
            if ($request->hasFile("image")) {
                $uploadedFile = $request->file("image");
                $media = $this->uploadAndSaveInDatabase($uploadedFile, 'sliders', 'Slider Image');
                $mediaId = $media->id;
            }

            Slider::create([
                'media_id' => $mediaId,
                'title' => $request->title,
                'caption' => $request->caption,
                'url' => $request->url,
                'order' => $request->order,
                'status' => $request->status,
            ]);

            $responseMessage = 'Slider created successfully.';
            return $this->successJsonResponse($responseMessage);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function edit($id)
    {
        try {
            $slider = Slider::where('id', $id)->with('media')->firstOrFail();
            $responseMessage = 'Edit slider loaded successfully.';
            return $this->successJsonResponse($responseMessage,$slider);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $slider = Slider::findOrFail($id);
            
            $mediaId = $request->media_id ?? null;
            if (empty($mediaId) && $request->hasFile("image")) {
                if ($slider->media) {
                    Media::deleteMedia($slider);
                }
                
                $uploadedFile = $request->file("image");
                $media = $this->uploadAndSaveInDatabase($uploadedFile, 'sliders', 'Slider Image');
                $mediaId = $media->id;
            }
            
            $slider->update([
                'media_id' => $mediaId,
                'title' => $request->title,
                'caption' => $request->caption,
                'url' => $request->url,
                'order' => $request->order,
                'status' => $request->status,
            ]);
            $responseMessage = 'Slider updated successfully.';
            return $this->successJsonResponse($responseMessage);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function destroy($id) {
        try {
            $slider = Slider::findOrFail($id);
            if ($slider->media) {
                Media::deleteMedia($slider);
            }
            $slider->delete();
            $responseMessage = 'Slider deleted successfully.';
            return $this->successJsonResponse($responseMessage);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

}
