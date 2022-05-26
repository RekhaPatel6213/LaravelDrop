<?php

namespace App\Models\Repositories\Hub;

use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\MessageBoxInterface;
use App\Models\Hub\MessageBox;
use Illuminate\Support\Facades\DB;

/**
 * Class MessageBoxRepository.
 *
 * @package namespace App\Models\Repositories\Hub;
 */
class MessageBoxRepository extends BaseRepository implements MessageBoxInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MessageBox::class;
    }


    public function getPosition()
    {
        $obj = $this->model->select(DB::raw('max(position) as position'))->pluck('position');
        return $obj[0] + 1;
    }
    
}
