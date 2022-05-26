<?php

namespace App\Models\Repositories\Admin;

use App\Models\Admin\AdminUser;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Contracts\Admin\AdminUserContract;

/**
 * Class CustomercRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Admin\Customer;
 */
class AdminUserRepository extends BaseRepository implements AdminUserContract
{	
	protected $adminAlias;
    protected $roleAlias;

    public function __construct()
    {
        $this->adminAlias = 'admin_users';
        $this->roleAlias = 'roles';
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AdminUser::class;
    }

    public function list(array $filter, string $sortOn = 'created_at', string $sortOrder = 'desc')
    {
        $queryBuilder = AdminUser::join($this->roleAlias, function ($join) {
				            $join->on($this->adminAlias.'.role', '=', $this->roleAlias.'.name');
				        })
			            ->select($this->adminAlias.'.*')
			            ->distinct();

        // For soft deleted records.
        $softDeletedField = [$this->adminAlias];
        foreach ($softDeletedField as $value) {
            $queryBuilder->whereNull($value.'.deleted_at');
        }

        $queryBuilder = $this->getQueryForSearch($filter, $queryBuilder);
        $queryBuilder = $this->getQueryForSort($sortOn, $sortOrder, $queryBuilder);
        return $queryBuilder;
    }

    private function getQueryForSearch($filter, $queryBuilder)
    {
        foreach ($filter as $key => $value) {
            if (is_string($value) && trim($value) != '') {
                switch ($key) {
                    case 'first_name':
                    case 'last_name':
                        $queryBuilder->where($this->adminAlias.'.'.$key, 'like', '%'.$value.'%');
                        break;
                    case 'created_at_from':
                        $queryBuilder->whereDate(
                            $this->adminAlias.'.created_at',
                            '>=',
                            Carbon::createFromFormat('Y-m-d', $value)
                                ->startOfDay()
                        );
                        break;
                    case 'created_at_till':
                        $queryBuilder->whereDate(
                            $this->adminAlias.'.created_at',
                            '<=',
                            Carbon::createFromFormat('Y-m-d', $value)->endOfDay()
                        );
                        break;
                    case 'except_admin_id':
                        $queryBuilder->where("{$this->adminAlias}.id", '!=', $value);
                        break;
                }
            }
        }
        return $queryBuilder;
    }

    private function getQueryForSort($sortOn, $sortOrder, $queryBuilder)
    {
        $sortableRef = [
            'first_name' => $this->adminAlias.'.first_name',
            'last_name' => $this->adminAlias.'.last_name',
            'email' => $this->adminAlias.'.email',
            'phone_number' => $this->adminAlias.'.phone_number',
            'role' => $this->roleAlias.'.name',
        ];

        if (isset($sortableRef[$sortOn])) {
            return $queryBuilder->orderBy($sortableRef[$sortOn], $sortOrder);
        }
        return $queryBuilder->orderBy($sortOn, $sortOrder);
    }
}
