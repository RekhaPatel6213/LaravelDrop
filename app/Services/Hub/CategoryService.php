<?php

namespace App\Services\Hub;

use App\Http\Traits\MediaTrait;
use App\Models\Hub\DispensaryCategory;

class CategoryService
{
    use MediaTrait;

    protected $repository;
    protected $dispensaryId;

    public function __construct($categoryRepository)
    {
        $this->repository = $categoryRepository;
        $this->dispensaryId = tenant('id');
    }

    public function list(?object $request)
    {
        $search = $request->query('search', null);
        $sortOn = $request->query('sortOn', 'priority');
        $sortOrder = $request->query('sort', 'asc');

        return $this->repository->list($search, $sortOn, $sortOrder);
    }

    public function subCategoryList(?object $request)
    {
        $parent_id = $request->query('parent_id', null);
        $search = $request->query('search', null);
        $sortOn = $request->query('sortOn', 'priority');
        $sortOrder = $request->query('sort', 'asc');

        return $this->repository->subCategoryList($parent_id, $search, $sortOn, $sortOrder);
    }

    public function update(int $categoryId, array $requestData)
    {
        $category = DispensaryCategory::ofTaxonId($categoryId)->first();

        if ($category === null) {
            $category = $this->repository->createCategory($categoryId, $requestData);
        } elseif ($category) {
            $category->description = $requestData['description'] ?? $category->description;
            $category->priority = $requestData['priority'] ?? $category->priority;
            $category->save();
        }

        //Add Icon & Banner Image if request file available
        $this->createMedia($category, DispensaryCategory::ICON);
        $this->createMedia($category, DispensaryCategory::BANNER);

        //Remove Icon & Banner Image if request flag available
        $this->removeMedia($category, DispensaryCategory::ICON, $requestData['remove_icon']);
        $this->removeMedia($category, DispensaryCategory::BANNER, $requestData['remove_banner']);

        return $category;
    }
}
