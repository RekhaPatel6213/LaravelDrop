<?php

namespace App\Models\Repositories\Hub;

//use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Hub\AuditInterface;
use App\Models\Hub\Audit;
use App\Models\Hub\Product;
use DB;

class AuditRepository extends BaseRepository
{
    public function model()
    {
        return Audit::class;
    }

    public function list(?string $search, string $sortOn, string $sortOrder)
    {
        $dispensaryId = tenant('id');
        $queryBuilder = $this->model->select('audits.*', DB::raw('CONCAT(du.first_name, " ", du.last_name) as created_by'))
                            ->leftjoin('dispensary_users as du', function($join1) use($dispensaryId)
                                {
                                    $join1->on('audits.created_by', '=', 'du.id');
                                    $join1->where('du.dispensary_id','=', $dispensaryId);
                                }
                            )
                            ->where('audits.dispensary_id', $dispensaryId)
                            ->with(['model'])
                            ->when($search !== null, function ($query1) use($search) {
                                $query1->where( function($query2) use($search) {
                                    foreach ($this->model::SEARCH_FIELDS as $field) {
                                        $query2->orWhere('audits.'.$field, 'LIKE', '%' . $search . '%');
                                    }
                                    $query2->orWhereHas('dispensaryUser', function ($query3) use($search) {
                                        $query3->where('first_name', 'LIKE', '%'.$search.'%');
                                        $query3->orWhere('last_name', 'LIKE', '%'.$search.'%');
                                    });
                                    $query2->orWhereHas('model', function ($query4) use($search) {
                                        $query4->where('name', 'LIKE', '%'.$search.'%');
                                    });
                                }); 
                            });

        if($sortOn !== null && $sortOrder !== null) {
            $queryBuilder->orderBy($sortOn, $sortOrder);
        } else {
            $queryBuilder->orderBy('audits.id', 'ASC');
        }

        return $queryBuilder->get();
    }

    public function getAudit(int $auditId)
    {
        $audit = $this->with(['model'])->find($auditId);

        $products = [];
        if ($audit && $audit->products) {
            $productIds = data_get($audit->products, '*.product_id');
            $productObjects = Product::select('id','name','brand')->whereIn('id', $productIds)->get();

            foreach ($audit->products as $product) {
                $productObject = $productObjects->where('id',$product['product_id'])->first();
                $products[] = [
                    'product_id' => $product['product_id'],
                    'product_name' => $productObject->name ?? $product['product_name'],
                    'brand' => $productObject->brand ?? $product['brand'],
                    'product_details' => $product['product_details'],
                    'quantity_type' => $product['quantity_type'],
                ];
            }
            $audit->products = $products;
        }

        return $audit;
    }
}
