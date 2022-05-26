<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * @OA\Schema(schema="WMAttributeDetail",
 *      @OA\Property(property="data", type="object", ref="#/components/schemas/WMAttributes")
 * )
 *
 * @OA\Schema(schema="WMAttributes",
 *      @OA\Property(property="wm_client_id", type="string", description="Weedmap Client Id"),
 *      @OA\Property(property="wm_client_secret", type="string", description="Weedmap Client Secret")
 * )
 *
 * @OA\RequestBody(
 *     request="WeedmapsSettings",
 *     description="Admin Api Settings Data Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/WMAttributes")
 * )
 */

class WeedmapsSettings extends Settings
{
    public string $wm_client_secret;

    public string $wm_client_id;
    
    public static function group(): string
    {
        return 'weedmaps';
    }
}
