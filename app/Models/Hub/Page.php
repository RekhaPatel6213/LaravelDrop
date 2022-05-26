<?php

namespace App\Models\Hub;

use App\Http\Traits\DispensaryTrait;
use App\Models\Admin\Page as BasePage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * @OA\Schema(
 *   schema="HubPage",
 *   allOf={
 *      @OA\Schema(ref="#/components/schemas/HubPageInputData"),
 *      @OA\Schema(
 *          @OA\Property(property="id",type="integer",format="int32",description="ID", example="1")
 *      ),
 *      @OA\Schema(ref="#/components/schemas/StandardTimestamp"),
 *   }
 *  )
 *
 * @OA\Schema(schema="HubPageInputData",
 *      @OA\Property(property="name", type="string", description="Title", example="Privacy Policy"),
 *      @OA\Property(property="page_code", type="string", description="Slug", example="test"),
 *      @OA\Property(property="group", type="string", description="TERM, POLICY,OTHER", example="TERM"),
 *      @OA\Property(property="html_content", type="string", description="Html Content", example="<p><strong>Introduction</strong></p><p>Drop Technologies, Inc. (&ldquo;Drop&rdquo;) understands and respects our users&rsquo; need for privacy. This Privacy Notice (&ldquo;Notice&rdquo;) describes the types of information we collect, the purposes for which it is used, and the choices you have with respect to its use.</p>"),
 *      @OA\Property(property="priority", type="string", description="Set Page Priority", example="1"),
 *      @OA\Property(property="sub_id", type="string", description="Set Parent Page ID", example="1"),
 *  )
 *
 * @OA\RequestBody(
 *     request="HubPageRequest",
 *     description="Page Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/HubPageInputData")
 *  )
 *
 * @OA\Schema(schema="HubPageSortsOn", type="array",
 *     @OA\Items(type="string", enum={"name", "page_code", "group", "html_content", "priority", "sub_id"})
 * )
 */

/**
 * Class Page.
 *
 * @package namespace App\Models\Hub;
 */
class Page extends BasePage implements Transformable
{
    use TransformableTrait;
    use DispensaryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'dispensary_id',
    ];
}
