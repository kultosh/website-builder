<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\RequestResponseTrait;
use App\Traits\UploadMediaTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PageController extends Controller
{
    use RequestResponseTrait;
    use UploadMediaTrait;
    
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 5);
            $pageList = Page::withCount('sections')->orderBy('created_at', 'desc')->paginate($perPage);
            $responseMessage = 'Page loaded successfully.';
            return $this->successJsonResponse($responseMessage,$pageList);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
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

            $responseMessage = 'Page created successfully.';
            return $this->successJsonResponse($responseMessage);
        } catch (Exception $error) {
            DB::rollback();
            return $this->exceptionJsonResponse($error);
        }
    }

    private function createPageSections($requestData, $page)
    {
        foreach ($requestData->sections as $index => $sectionData) {
            $mediaId = null;
            if ($requestData->hasFile("sections.$index.image")) {
                $uploadedFile = $requestData->file("sections.$index.image");
                $media = $this->uploadAndSaveInDatabase($uploadedFile, 'sections', 'Section Image');
                $mediaId = $media->id;
            }

            // Save section
            $page->sections()->create([
                'layout_type' => $sectionData['layout'],
                'description' => $sectionData['content'],
                'media_id' => $mediaId,
            ]);
        }
    }

    public function parentPages()
    {
        try {
            $parents = Page::where('is_parent', 1)->select('id', 'title')->get();

            $responseMessage = 'Parent page list loaded successfully.';
            return $this->successJsonResponse($responseMessage,$parents);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }
}
