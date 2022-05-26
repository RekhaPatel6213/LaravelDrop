<?php

namespace App\Models\Repositories\Contracts\Hub;

use Prettus\Repository\Contracts\RepositoryInterface;

interface DispensaryCategoryContract extends RepositoryInterface
{
	public function addDefaultCategories(int $dispensaryId);
	public function createCategory(int $categoryId, array $requestData);
	public function getQuery(?string $search, string $sortOn, string $sortOrder, bool $isParent = true);
	public function list(?string $search, string $sortOn, string $sortOrder);
	public function subCategoryList(?int $parentId, string $search = null, string $sortOn, string $sortOrder);
}
