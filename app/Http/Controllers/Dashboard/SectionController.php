<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Sections\StoreSectionRequest;
use App\Http\Requests\Dashboard\Sections\UpdateSectionRequest;
use App\Models\BusinessType;
use App\Models\Section;
use App\Models\SectionProduct;
use App\Services\SectionService;
use App\Services\TranslateService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct(private SectionService $sectionService) {}
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        // check if the business type exists
        $businessType = BusinessType::where('slug', $slug)
            ->select('id')
            ->first();
        $businessType ?? abort(404);
        $sections = Section::where('business_type_id', $businessType->id)->paginate(20);
        return view('sections.index', compact('sections'));
    }
    /**
     * the create form view
     */
    public function create()
    {
        $businesses = BusinessType::all();
        return view('sections.create', compact('businesses'));
    }
    /**
     * store new section
     * @param StoreSectionRequest $request
     */
    public function store(StoreSectionRequest $request)
    {
        $service = $this->sectionService->storeService($request->validated());
        if ($service instanceof \Illuminate\Http\JsonResponse) return $service;
        return ApiResponseTrait::successResponse([], __('messages.added'));
    }
    /**
     * show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $section = Section::with('sectionProducts')->findOrFail($id);
        $businesses = BusinessType::all();
        return view('sections.edit', get_defined_vars());
    }
    /**
     * update section
     */
    public function update(UpdateSectionRequest $request, Section $section) {
        $service = $this->sectionService->updateService($section, $request->validated());
        if ($service instanceof \Illuminate\Http\JsonResponse) return $service;
        return ApiResponseTrait::successResponse([], __('messages.updated'));
    }

    /**
     * destroy a section
     */
    public function destroy(Section $section)
    {
        // delete section products
        SectionProduct::where('section_id', $section->section_id)->delete();
        $section->delete();
        return back()->with('Success', __('messages.deleted'));
    }
}
