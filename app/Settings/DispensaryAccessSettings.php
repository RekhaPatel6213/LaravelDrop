<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 *  @OA\Schema(schema="DispensaryAccess",
 *      @OA\Property(property="standalone", type="boolean", description="Standalone Desktop"),
 *      @OA\Property(property="smart_deals", type="boolean", description="Smart Deals"),
 *      @OA\Property(property="scheduled_delivery", type="boolean", description="Scheduled Delivery"),
 *      @OA\Property(property="iframe_code", type="boolean", description="Iframe Code"),
 *      @OA\Property(property="seo_location", type="boolean", description="SEO Location"),
 *      @OA\Property(property="driver_optimization", type="boolean", description="Driver Optimization"),
 *      @OA\Property(property="inventory_feature", type="boolean", description="Inventory Feature"),
 *  )
 *
 *  @OA\RequestBody(
 *     request="DispensaryAccessUpdate",
 *     description="Admin Api Settings Data Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/DispensaryAccess")
 *  )
 */

class DispensaryAccessSettings extends Settings
{
    public bool $standalone;

    public bool $smart_deals;

    public bool $scheduled_delivery;

    public bool $iframe_code;

    public bool $seo_location;

    public bool $driver_optimization;

    public bool $inventory_feature;
    
    public static function group(): string
    {
        return 'dispensary_access';
    }
}
