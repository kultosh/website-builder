<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index()
    {
        return response('index');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
                $page = Page::create([
                'parent_id' => $request->parent_id === 'null' ? null : (int) $request->parent_id,
                'title' => $request->title,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'page_type' => $request->page_type,
                'order' => $request->order,
                'is_parent' => $request->is_parent,
                'is_menu' => $request->add_to_menu,
                'add_to_home' => $request->add_to_home,
                'status' => $request->status,
            ]);

            if(!$request->is_parent) {
                $this->createPageSections($request, $page);
            }
            DB::commit();
            return response()->json(['message' => 'Page created successfully.']);
        } catch (Exception $error) {
            DB::rollback();
            \Log::info(['pageError' => $error->getMessage()]);
            return response()->json(['message' => $error->getMessage()]);
        }
    }

    private function createPageSections($requestData, $page)
    {
        foreach ($requestData->sections as $index => $sectionData) {
            $mediaId = null;

            if ($requestData->hasFile("sections.$index.image")) {
                $uploadedFile = $requestData->file("sections.$index.image");

                // Store the image
                $path = $uploadedFile->store('sections', 'public');

                // Save metadata in the medias table
                $media = Media::create([
                    'name' => $uploadedFile->getClientOriginalName(),
                    'mime_type' => $uploadedFile->getClientMimeType(),
                    'path' => $path,
                    'thumbnail_path' => null, // Optional: generate if needed
                    'alt_text' => '',         // Optional: extend your form if needed
                ]);

                $mediaId = $media->id;
            }


            $page->sections()->create([
                'layout_type' => $sectionData['layout'],
                'description' => $sectionData['content'],
                'media_id' => $mediaId,
            ]);
        }
    }

    public function parentPages()
    {
        $parents = Page::where('is_parent', 1)->select('id', 'title')->get();
        return response()->json($parents);
    }
}
