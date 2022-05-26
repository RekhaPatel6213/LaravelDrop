<?php

namespace App\Models\Repositories\Contracts\Admin\Dispensary;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DispensaryRepository.
 *
 * @package namespace App\Models\Repositories\Contracts\Admin\Dispensary;
 */
interface DispensaryRepository extends RepositoryInterface
{
    public function isAlphaExists($alphaId): bool;

    public function getListingData(string $searchString, string $sortOn, string $sortOrder, string $status);

    public function getDispensaryNames(string $ids);
}
