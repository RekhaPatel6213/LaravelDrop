<?php

namespace App\Models\Hub;

use App\Http\Traits\DispensaryTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Category\Models\TaxonProxy;

/**
 * @OA\Schema(
 *      schema="CategoryList",
 *      required={"data"},
 *      @OA\Property(property="data", type="array", @OA\Items(
 *          allOf={
 *              @OA\Schema(ref="#/components/schemas/TaxonData"),
 *              @OA\Schema(
 *                  @OA\Property(property="children", type="array", @OA\Items(ref="#/components/schemas/TaxonData"))
 *              )
 *          }
 *      ))
 * )
 *
 * @OA\Schema(
 *      schema="SubCategoryList",
 *      required={"data"},
 *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TaxonData"))
 * )
 *
 * @OA\Schema(schema="MainTaxonData",
 *      @OA\Property(property="id", type="integer", format="int32", description="Category Id", example="1"),
 *      @OA\Property(property="taxonomy_id",type="integer", format="int32", description="Category Taxonomy Id", example="1"),
 *      @OA\Property(property="name", type="string", description="Category Name", example="Category Name"),
 *      @OA\Property(property="slug", type="string", description="Category Slug", example="category-name"),
 *      @OA\Property(property="priority", type="integer", format="int32", description="Category Priority Number", example="1"),
 *      @OA\Property(property="attribute", type="string", description="Category Attribute", example="UNITS", enum={"UNITS", "PRE-PACKAGED", "GRAMS"}),
 *      @OA\Property(property="state", type="string", description="Category State", example="ACTIVE", enum={"ACTIVE", "INACTIVE"})
 * )
 *
 * @OA\Schema(schema="TaxonData",
 *    allOf={
 *      @OA\Schema(ref="#/components/schemas/MainTaxonData"),
 *      @OA\Schema(
 *        @OA\Property(property="dispensary_category", type="object", ref="#/components/schemas/DispensaryCategory"),
 *        @OA\Property(property="taxonomy", type="object", ref="#/components/schemas/TaxonomyData")
 *      )
 *    }
 * )
 *
 * @OA\Schema(schema="TaxonomyData",
 *      @OA\Property(property="id", type="integer", format="int32", description="Taxonomy Id", example="1"),
 *      @OA\Property(property="name", type="string", description="Taxonomy Name", example="Taxonomy Name"),
 *      @OA\Property(property="slug", type="string", description="Taxonomy Slug", example="taxonomy-name")
 * )
 *
 * @OA\Schema(schema="DispensaryCategory",
 *      @OA\Property(property="id", type="integer", format="int32", description="Taxonomy Id", example="1"),
 *      @OA\Property(property="description", type="string", description="Taxonomy Name", example="Taxonomy Name"),
 *      @OA\Property(property="priority", type="integer", format="int32", description="Category Priority Number", example="1"),
 *      @OA\Property(property="icon", type="string", description="Category Icon Url"),
 *      @OA\Property(property="banner", type="string", description="Category Banner Url")
 * )
 *
 * @OA\Schema(schema="CategoryInputData",
 *      @OA\Property(property="priority", type="integer", format="int32", description="Category Priority Number", example="1"),
 *      @OA\Property(property="description", type="string", description="Category Description", example="Category Description"),
 *      @OA\Property(property="icon", type="file", description="Category Icon Image"),
 *      @OA\Property(property="banner", type="file", description="Category Banner Image"),
 *      @OA\Property(property="remove_icon", type="boolean", description = "Remove Category Icon Image", example="false"),
 *      @OA\Property(property="remove_banner", type="boolean", description = "Remove Category Banner Image", example="false"),
 * )
 *
 * @OA\Schema(schema="CategorySortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "priority"})
 * )
 *
 * @OA\Schema(
 *      schema="CategoryDetail",
 *      required={"data"},
 *      @OA\Property(property="data", type="object",
 *          @OA\Property(property="id", type="integer", format="int32", description="Category Id", example="1"),
 *          @OA\Property(property="description", type="string", description="Category Description", example="Category Description"),
 *          @OA\Property(property="priority", type="integer", format="int32", description="Category Priority Number", example="1"),
 *          @OA\Property(property="icon", type="string", description="Category Icon Image", example="http://example.com/example-thumb.jpg"),
 *          @OA\Property(property="banner", type="string", description="Category Banner Image", example="http://example.com/example-thumb.jpg")
 *      )
 * )
 */
class DispensaryCategory extends Model implements HasMedia
{
    use DispensaryTrait, InteractsWithMedia;

    protected $fillable = [
        'dispensary_id',
        'taxon_id',
        'description',
        'priority'
    ];

    public const ICON = 'icon',
                 BANNER = 'banner';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')
            ->useDisk('DO')
            ->singleFile();

        $this->addMediaCollection('banner')
            ->useDisk('DO')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function ScopeOfTaxonId($query, int $taxonId)
    {
        $taxonId ? $query->where('taxon_id', $taxonId) : $query;
    }
}
