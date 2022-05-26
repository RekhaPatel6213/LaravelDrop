<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Hub\BulkTemplate;
use App\Models\Repositories\Contracts\Hub\BulkTemplateInterface;

class BulkTemplateRepository extends BaseRepository
{

   function model()
   {
      return BulkTemplate::class;
   }

   public function list(string $search, string $sortOn, string $sortOrder)
   {
      return $this->getQueryBuilder($this->model, $search, $sortOn, $sortOrder)->get();
   }
}