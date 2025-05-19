<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Slider;
use App\Traits\RequestResponseTrait;

class DashboardController extends Controller
{
    use RequestResponseTrait;
    
    public function index()
    {
        try {
            $data = [
                [
                    'count' => Page::count(),
                    'label' => 'Total Pages',
                    'icon' => 'bi bi-files',
                    'bg_class' => 'bg-primary',
                    'text_class' => 'text-white',
                ],
                [
                    'count' => Page::parentPage()->count(),
                    'label' => 'Total Parent Pages',
                    'icon' => 'bi bi-diagram-3',
                    'bg_class' => 'bg-success',
                    'text_class' => 'text-white',
                ],
                [
                    'count' => Page::child()->count(),
                    'label' => 'Total Child Pages',
                    'icon' => 'bi bi-diagram-2',
                    'bg_class' => 'bg-warning',
                    'text_class' => 'text-dark',
                ],
                [
                    'count' => Page::active()->count(),
                    'label' => 'Total Active Pages',
                    'icon' => 'bi bi-lightning',
                    'bg_class' => 'bg-success',
                    'text_class' => 'text-white',
                ],
                [
                    'count' => Page::menu()->count(),
                    'label' => 'Total Menus',
                    'icon' => 'bi bi-list',
                    'bg_class' => 'bg-info',
                    'text_class' => 'text-white',
                ],
                [
                    'count' => Slider::count(),
                    'label' => 'Total Sliders',
                    'icon' => 'bi bi-images',
                    'bg_class' => 'bg-secondary',
                    'text_class' => 'text-white',
                ],
            ];

            $responseMessage = 'Dashboard records loaded successfully.';
            return $this->successJsonResponse($responseMessage,$data);
        } catch (\Exception $error) {
            return $this->exceptionJsonResponse($error);
        }
    }
}
