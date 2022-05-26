<?php

namespace App\Services\Admin;

use DB;
use Vanilo\Category\Models\Taxonomy;
use App\Models\Hub\Category;

class MigrationService
{
	protected $databaseNew,$databaseOld;

    public function __construct()
    {
        $this->databaseNew = DB::connection('mysql');
        $this->databaseOld = DB::connection('mysql2');
    }

	public function getOldDbData($table, $orderBy, $isList = true, $joinTable = null, $joinId = null, $id = null)
	{
		$query = $this->databaseOld->table($table);
		if($joinTable){
			$query->join($joinTable, $joinTable.'.'.$joinId, '=', $table.'.'.$joinId);
		}
		foreach ($orderBy as $field => $value) {
			$query->orderBy($field, $value);
		}

		if($joinTable){
			return $query->get();
		}

		return $query->first();
	}

	public function migrateCategories($isParent = true, $parentId = null, $newParentId = null)
	{
        $categories = $this->getCategories($isParent, $parentId);
        if($categories)
        {
            foreach($categories as $key => $category)
            {
            	//if(!Category::whereName($category->cat_name)->first()){
	        		$newTaxonId = $this->storeSingalCategory($category->cat_name, $category->type_name, $key+1, $category->attr_id , $category->status, $newParentId);
	        		if($isParent){
		        		$this->migrateCategories(false, $category->parent_id, $newTaxonId);
		        	}
	            //}
            }
        }
	}

	public function getCategories($isParent = true, $parentId = null)
	{
		$queryBuilder = $this->databaseOld->table('categories')
			->select('categories.*', 'category_types.name as type_name')
			->join('category_types', 'categories.cat_type', '=', 'category_types.cat_type');

		if($isParent){
			$queryBuilder->whereRaw('categories.cat_id = categories.parent_id')
				->orderBy('cat_id', 'ASC');
		} else if($parentId > 0) {
			$queryBuilder->where('parent_id', '=', $parentId)
				->whereRaw('cat_id != parent_id')
				->orderBy('cat_id', 'ASC')->orderBy('position', 'ASC');
		}
		return $queryBuilder->get();
	}

	public function storeSingalCategory($name, $type, $priority, $attrId, $state, $parentId = null)
	{
		$taxonomies = Taxonomy::pluck('id', 'name')->toArray();
		$attributes = config('admin_setting.category_attributes');
		$attrId = $attrId === 0 ? 2 : (int) $attrId - 1;

		$taxon = Category::create([
			'name' => $name,
			'taxonomy_id' => $taxonomies[$type],
            'priority' => $priority,
            'attribute' => $attributes[$attrId],
            'parent_id' => $parentId ?? NULL,
            'state' => $state === 1 ? Category::ACTIVE : Category::INACTIVE
		]);
		return $taxon->id;
	}
}