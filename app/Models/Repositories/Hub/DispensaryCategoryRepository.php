<?php

namespace App\Models\Repositories\Hub;

use App\Models\Hub\Category;
use App\Models\Hub\DispensaryCategory;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\DispensaryCategoryContract;
use Carbon\Carbon;

class DispensaryCategoryRepository extends BaseRepository implements DispensaryCategoryContract
{
    protected $dispensaryId;

    public function __construct()
    {
        $this->dispensaryId = tenant('id');
    }

    public function model()
    {
        return DispensaryCategory::class;
    }

    public function addDefaultCategories(int $dispensaryId)
    {
        $categories = [];
        $taxons = Category::where('state', Category::ACTIVE)->get();
        foreach ($taxons as $taxon) {
            $categories[] = [
                'dispensary_id' => $dispensaryId,
                'taxon_id' => $taxon->id,
                'priority' => $taxon->priority,
                'description' => $taxon->name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
           ];
        }
        if (!empty($categories)) {
            DispensaryCategory::insert($categories);
        }
    }

    public function createCategory(int $categoryId, array $requestData)
    {
        $taxon = Category::find($categoryId);

        $category = DispensaryCategory::create([
            'taxon_id' => $taxon->id,
            'priority' => $requestData->priority ?? $taxon->priority,
            'description' => $requestData->description ?? $taxon->name,
        ]);

        return $category;
    }

    public function getQuery(?string $search, string $sortOn, string $sortOrder, bool $isParent = true)
    {
        $queryBuilder = Category::with(['dispensaryCategory'])
                        ->where('state', Category::ACTIVE)
                        ->when($search !== null, function ($query1) use ($search) {
                            $query1->where(function ($query2) use ($search) {
                                $query2->where('name', 'LIKE', '%'.$search.'%');
                                $query2->orWhereHas('children', function ($query3) use ($search) {
                                    $query3->where('name', 'LIKE', '%'.$search.'%');
                                });
                            });
                        });

        if (!$isParent) {
            $queryBuilder->with(['taxonomy', 'dispensaryCategory.media'])
                         ->whereNotNull('parent_id');
        }

        if ($isParent) {
            $queryBuilder->with([
                                    'taxonomy',
                                    'dispensaryCategory.media',
                                    'children' => function ($query) {
                                        $query->with(['taxonomy', 'dispensaryCategory.media']);
                                    },
                                ])->whereNull('parent_id');
        }

        return $queryBuilder->orderBy($sortOn, $sortOrder);
    }

    public function list(?string $search, string $sortOn, string $sortOrder)
    {
        return $this->getQuery($search, $sortOn, $sortOrder)->get();
    }

    public function subCategoryList(?int $parentId, string $search = null, string $sortOn, string $sortOrder)
    {
        return $this->getQuery($search, $sortOn, $sortOrder, false)
            ->when($parentId !== null, function ($query1) use ($parentId) {
                $query1->where('parent_id', $parentId);
            })
            ->get();
    }
}
