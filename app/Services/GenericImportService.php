<?php

namespace App\Services;

use App\Models\Repositories\GenericImportRepository;

class GenericImportService
{
    protected $importRepository;

    public function __construct(GenericImportRepository $importRepository)
    {
        $this->importRepository = $importRepository;
    }
}
