<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanilo\Category\Models\Taxon;
use App\Http\Requests\Hub\CategoryRequest;
use App\Transformers\Hub\CategoryTransformer;
use App\Transformers\Hub\DispensaryCategoryTransformer;

class CategoryController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('categoryService');
        $this->transformer = new CategoryTransformer;
        $this->dispensaryTransformer = new DispensaryCategoryTransformer;
    }

    public function list(Request $request)
    {
        try {
            $categories = $this->service->list($request);
            return $this->collection($categories, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function subCategoryList(Request $request)
    {
        try {
            $categories = $this->service->subCategoryList($request);
            return $this->collection($categories, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function update($categoryId, CategoryRequest $request)
    {
        try {
            $category = $this->service->update($categoryId, $request->all());
            return $this->item($category, $this->dispensaryTransformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
