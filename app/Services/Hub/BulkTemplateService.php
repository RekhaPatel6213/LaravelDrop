<?php

namespace App\Services\Hub;

use App\Models\Repositories\Hub\BulkTemplateRepository;

class BulkTemplateService
{
	protected $repository;
	
    public function __construct($bulkTemplateRepository)
    {
        $this->repository = $bulkTemplateRepository;
    }

    public function list(array $requestData)
    {
        $search = $requestData['search'] ?? '';
        $sortOn = $requestData['sortOn'] ?? 'id';
        $sortOrder = $requestData['sort'] ?? 'asc';
        return $this->repository->list($search, $sortOn, $sortOrder);
    }

    public function create(array $requestData)
    {
        return $this->repository->create($requestData);
    }

    public function get(int $templateId)
    {
        return $this->repository->find($templateId);
    }

    public function update(array $requestData, int $templateId)
    {
        return $this->repository->update($requestData, $templateId);
    }

    public function delete(int $templateId)
    {
        $this->repository->delete($templateId);
        return ['message' => __('message.deleteSuccess', ['name' => __('product.BulkTransferTemplate')])];
    }
}