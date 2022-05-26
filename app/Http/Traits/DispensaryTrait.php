<?php

namespace App\Http\Traits;

use App\Models\Admin\Dispensary\Dispensary;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

trait DispensaryTrait
{
    use BelongsToTenant;

    public static function bootDispensaryTrait()
    {
        BelongsToTenant::$tenantIdColumn = 'dispensary_id';
    }

    public function dispensary()
    {
        return $this->belongsTo(Dispensary::class);
    }

    public function generateUniqueId($modelName, $field)
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $model = '\\App\\Models\\'.$modelName;
        $uniqueId = substr(str_shuffle($chars), 0, 8);
        if ($model::where($field, $uniqueId)->exists()) {
            $this->generateAlphaId();
        }

        return $uniqueId;
    }

    public function stringToArray($string, bool $single=false)
    {
        $strArray = [];
        $checkKey = [];
        $arrayKey = 0;
        $newString = str_replace(["{\n","\n","\""," ",'}'], '', (explode(',', $string)));

        if($single) {
            return $newString;
        }
        if (is_array($newString)) {
            foreach ($newString as $key => $value) {
                $stringValue = explode(':', $value);
                $keyName = $stringValue[0];

                if ($key === 0 && (empty($checkKey) || !in_array($keyName, $checkKey))) {
                    $checkKey[] = $keyName;
                }

                if ($key !== 0 && in_array($keyName, $checkKey)) {
                    $arrayKey++;
                }

                $strArray[$arrayKey][$keyName] = $stringValue[1];
            }
        }
        return $strArray;
    }

    public function usPhoneFormat($phoneNumber = null)
    {
        if ($phoneNumber) {
            $phoneNumber = str_replace('+1', '', $phoneNumber);
            if (strlen($phoneNumber) > 6) {
                $phoneNumber = '(' . substr($phoneNumber, 0, 3) . ') ' . substr($phoneNumber, 3, 3) . '-' . substr($phoneNumber, 6);
            }
        }
        return $phoneNumber;
    }
}
