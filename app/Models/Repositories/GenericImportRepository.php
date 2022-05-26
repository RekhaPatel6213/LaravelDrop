<?php

namespace App\Models\Repositories;

use App\Models\GenericImport;
use App\Models\Repositories\Contracts\GenericImportRepository as GenericImportInterface;

/**
 * Class GenericImportRepositoryEloquent.
 */
class GenericImportRepository extends BaseRepository implements GenericImportInterface
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return GenericImport::class;
    }

    public function getLastImportData($importType)
    {
        return $this->model->where('import_type', $importType)->orderBy('created_at', 'DESC')->first();
    }

    public function getPendingPreviewData($previewId)
    {
        return $this->getPreviewData($previewId, GenericImport::PENDING);
    }

    public function getPreviewData($previewId, $status = null)
    {
        return $this->model->where('id', $previewId)->ofStatus($status)->first();
    }
}
