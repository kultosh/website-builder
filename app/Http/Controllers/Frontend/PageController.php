<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\RequestResponseTrait;
use Exception;
use Illuminate\Support\Str;

class PageController extends Controller
{
    use RequestResponseTrait;

    public function showPage($slug)
    {
        try {
            $page = Page::select('id', 'title', 'slug', 'is_parent')->where('slug', $slug)->with(['sections:id,page_id,layout_type,description,media_id','sections.media:id,path,alt_text'])->firstOrFail();

            $data = [];
            $data['id'] = $page->id;
            $data['title'] = $page->title;
            $data['slug'] = $page->slug;
            $data['is_parent'] = $page->is_parent;

            foreach($page->sections as $section) {
                $data['sections'][] = [
                    'layout_type' => $section->layout_type,
                    'description' => $section->description ?? '',
                    'image_path' => $section->media->path ?? null,
                    'image_alt_text' => $section->media->alt_text ?? null,
                ];
            }
            $responseMessage = 'Page loaded successfully.';
            return $this->successJsonResponse($responseMessage,$data);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function getChildPages($id)
    {
        try {
            $childPageList = Page::select('id', 'title', 'slug', 'is_parent')->where('parent_id', $id)->with(['sections:id,page_id,layout_type,description,media_id','sections.media:id,path,alt_text'])->get();

            $data = [];
            foreach($childPageList as $childPage) {
                $data[] = [
                    'id' => $childPage->id,
                    'title' => $childPage->title,
                    'slug' => $childPage->slug,
                    'description' => Str::limit($childPage->sections[0]->description ?? '', 100),
                    'image_path' => $childPage->sections[0]->media->path ?? null,
                    'image_alt_text' => $childPage->sections[0]->media->alt_text ?? null,
                ];
            }
            $responseMessage = 'Child page loaded successfully.';
            return $this->successJsonResponse($responseMessage,$data);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }

    public function getHomePageSections()
    {
        try {
            $pages = Page::select('id', 'title', 'slug', 'parent_id')
                    ->where('add_to_home', true)
                    ->with([
                        'sections' => function ($query) {
                            $query->select('id', 'page_id', 'layout_type', 'description', 'media_id')
                            ->with('media:id,path,alt_text');
                        }
                    ])
                    ->get();

            $parentIds = $pages->whereNotNull('parent_id')->pluck('parent_id')->unique();
            $sectionGrouped = [
                'single' => [],
                'parent' => []
            ];

            foreach ($pages as $page) {
                if ($page->parent_id) {
                    continue;
                }

                if ($parentIds->contains($page->id)) {
                    $children = $pages->where('parent_id', $page->id)->map(function($child) {
                        return $this->formatPageData($child);
                    })->values();
                    $sectionGrouped['parent'][] = [
                        'parent_id' => $page->id,
                        'parent_page_title' => $page->title,
                        'children' => $children
                    ];
                } else {
                    $sectionGrouped['single'][] = $this->formatPageData($page,400);
                }
            }

            $responseMessage ='Home pages fetched successfully.';
            return $this->successJsonResponse($responseMessage,$sectionGrouped);
        } catch (Exception $error) {
            return $this->exceptionJsonResponse($error);
        }

    }

    private function formatPageData($page, $limit=100)
    {
        $firstSection = $page->sections->first();
        $media = $firstSection->media ?? null;

        return [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'layout_type' => $firstSection->layout_type,
            'description' => Str::limit($firstSection->description ?? '', $limit),
            'image_path' => $media->path ?? null,
            'image_alt_text' => $media->alt_text ?? null,
        ];
    }

    public function getMenuPages()
    {
        try {
            $menuPages = Page::select('id', 'title', 'slug')
                ->where('is_menu', true)
                ->whereNull('parent_id')
                ->orderBy('order', 'asc')
                ->with(['children' => function ($q) {
                    $q->select('id', 'title', 'slug', 'parent_id')
                    ->where('is_menu', true)
                    ->orderBy('order', 'asc');
                }])
                ->get();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Menu pages fetched successfully.',
                'content' => $menuPages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => 'Something went wrong.',
                'content' => $e->getMessage()
            ]);
        }
    }
}
