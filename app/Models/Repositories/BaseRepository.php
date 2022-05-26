<?php
namespace App\Models\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository as CoreRepository;

class BaseRepository extends CoreRepository
{
    public function model()
    {
        return '';
    }

    public function store(array $data, $model = null, $isPassword = false)
    {
        if ($model === null) {
            $model = $this->model;

            if ($isPassword) {
                $model->password = $data['password'] ?? Hash::make(Str::random(32));
            }
        }
        $modelFill = $model->getFillable();

        $modelData = array_filter(
            $data,
            function ($key) use ($modelFill) {
                return in_array($key, $modelFill) >= 0;
            },
            ARRAY_FILTER_USE_KEY
        );
        $model->fill($modelData);
        $model->save();
        return $model;
    }

    public function withFind($relations, $id)
    {
        return $this->model->with($relations)->find($id);
    }

    public function insertData(array $data)
    {
        return $this->model->insert($data);
    }

    public function updateData(array $data, $id)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function findWhereNotNull(string $column)
    {
        return $this->model->whereNotNull($column)->get();
    }

    public function getQueryBuilder($model, ?string $search, string $sortOn, string $sortOrder)
    {
        $queryBuilder = $model->when($search !== null, function ($query1) use($search, $model) {
                                    $query1->where( function($query2) use($search, $model) {
                                        foreach ($model::SEARCH_FIELDS as $field) {
                                            $query2->orWhere($field, 'LIKE', '%' . $search . '%');
                                        }
                                    });
                                })
                                ->orderBy($sortOn, $sortOrder);
        return $queryBuilder;
    }
}
