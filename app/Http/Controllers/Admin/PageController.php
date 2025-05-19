<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
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

    public function edit($id)
    {
        try {
            $editPage = Page::where('id', $id)->with('sections.media')->firstOrFail();
            $responseMessage = 'Edit Page loaded successfully.';
            return $this->successJsonResponse($responseMessage,$editPage);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
                $page = Page::find($id);
                $page->update([
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
                    $this->updatePageSections($request, $page);
                }
            DB::commit();
            $responseMessage = 'Page updated successfully.';
            return $this->successJsonResponse($responseMessage);
        } catch (Exception $error) {
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
                'order' => $sectionData['order'],
                'description' => $sectionData['content'],
                'media_id' => $mediaId,
            ]);
        }
    }

    private function updatePageSections($requestData, $page)
    {
        $existingIds = collect($requestData->sections)->pluck('id')->filter()->toArray();

        // Delete Section Removed From Admin Panel of Page Form
        $page->sections()->whereNotIn('id', $existingIds)->each(function ($section) {
            if ($section->media) {
                Media::deleteMedia($section);
            }
            $section->delete();
        });

        // Update Section If Exist Else Create New Section With Media
        foreach ($requestData->sections as $index => $sectionData) {
            $mediaId = $sectionData['media_id'] ?? null;

            $section = $page->sections()->find($sectionData['id']);
            if (!empty($sectionData['id']) && is_numeric($sectionData['id']) && $requestData->hasFile("sections.$index.image")) {
                if ($section && $section->media) {
                    Media::deleteMedia($section);
                }

                $uploadedFile = $requestData->file("sections.$index.image");
                $media = $this->uploadAndSaveInDatabase($uploadedFile, 'sections', 'Section Image');
                $mediaId = $media->id;
            }

            if ($section) {
                $section->update([
                    'layout_type' => $sectionData['layout'],
                    'order' => $sectionData['order'],
                    'description' => $sectionData['content'],
                    'media_id' => $mediaId,
                ]);
            } else {
                if ($requestData->hasFile("sections.$index.image")) {
                    $uploadedFile = $requestData->file("sections.$index.image");
                    $media = $this->uploadAndSaveInDatabase($uploadedFile, 'sections', 'Section Image');
                    $mediaId = $media->id;
                }

                $page->sections()->create([
                    'layout_type' => $sectionData['layout'],
                    'order' => $sectionData['order'],
                    'description' => $sectionData['content'],
                    'media_id' => $mediaId,
                ]);
            }
        }
    }

    public function destroy($id) {
        try {
            $page = Page::findOrFail($id);
            if($page) {
               $page->sections()->each(function ($section) {
                    if ($section->media) {
                        Media::deleteMedia($section);
                    }
                    $section->delete();
                });
                $page->delete();
            }
            $responseMessage = 'Page deleted successfully.';
            return $this->successJsonResponse($responseMessage);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
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
